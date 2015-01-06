<?php
/*~ mysql.php
.---------------------------------------------------------------------------.
|  Software: SencilloCache                                                  |
|   Version: 2014.002                                                       |
|   Contact: ph@mastery.sk                                                  |
| ------------------------------------------------------------------------- |
|    Author: Bc. Peter Horváth (original founder)                           |
| Copyright (c) 2014, Bc. Peter Horváth. All Rights Reserved.               |
| ------------------------------------------------------------------------- |
|   License: Distributed under the General Public License (GPL)             |
|            http://www.gnu.org/copyleft/gpl.html                           |
| This program is distributed in the hope that it will be useful - WITHOUT  |
| ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or     |
| FITNESS FOR A PARTICULAR PURPOSE.                                         |
'---------------------------------------------------------------------------'
~*/

/* quickcache_db_connect()
 * Makes connection to the database
 */
function quickcache_db_connect() {
  $GLOBALS["sql_link"] = @mysql_connect($GLOBALS["QUICKCACHE_DB_HOST"],
                                        $GLOBALS["QUICKCACHE_DB_USERNAME"],
                                        $GLOBALS["QUICKCACHE_DB_PASSWORD"]);
}

/* quickcache_db_disconnect()
 * Closes connection to the database
 */
function quickcache_db_disconnect() {
  mysql_close($GLOBALS["sql_link"]);
}

/* quickcache_db_query($query)
 * Executes a given query
 */
function quickcache_db_query($query) {
  // quickcache_debug("Executing SQL-query $query");
  $ret = @mysql_db_query($GLOBALS["QUICKCACHE_DB_DATABASE"],
                         $query,
                         $GLOBALS["sql_link"]);
  return $ret;
}

/* quickcache_restore()
 * Will try to restore the cachedata from the db.
 */
function quickcache_restore() {
  $res = quickcache_db_query("select GZDATA, DATASIZE, DATACRC from ".
                              $GLOBALS["QUICKCACHE_DB_TABLE"].
                          " where CACHEKEY='".
                              addslashes($GLOBALS["quickcache_key"]).
                          "' and (CACHEEXPIRATION>".
                              time().
                          " or CACHEEXPIRATION=0)"
                         );

  if ($res && mysql_num_rows($res))
  {
    if ($row = mysql_fetch_array($res))
    {
      // restore data into global scope from found row
      $GLOBALS["quickcachedata_gzdata"]   = $row["GZDATA"];
      $GLOBALS["quickcachedata_datasize"] = $row["DATASIZE"];
      $GLOBALS["quickcachedata_datacrc"]  = $row["DATACRC"];
      return true;
    }
  }
  return false;
}

/* quickcache_write()
 * Will (try to) write out the cachedata to the db
 */
function quickcache_write($gzdata, $datasize, $datacrc) {
  $dbtable = $GLOBALS["QUICKCACHE_DB_TABLE"];

  // XXX: Later on, maybe implement locking mechanism inhere.

  // Check if it already exists
  $res = quickcache_db_query("select CACHEEXPIRATION from $dbtable".
                          " where CACHEKEY='".
                              addslashes($GLOBALS["quickcache_key"]).
                          "'"
                         );


  if (!$res || mysql_num_rows($res) < 1) {
    // Key not found, so insert
    $res = quickcache_db_query("insert into $dbtable".
                            " (CACHEKEY, CACHEEXPIRATION, GZDATA,".
                            " DATASIZE, DATACRC) values ('".
                                addslashes($GLOBALS["quickcache_key"]).
                            "',".
                                (($GLOBALS["QUICKCACHE_TIME"] != 0) ?
                                (time()+$GLOBALS["QUICKCACHE_TIME"]) : 0).
                            ",'".
                                addslashes($gzdata).
                            "', $datasize, $datacrc)"
                           );
    // This fails with unique-key violation when another thread has just
    // inserted the same key. Just continue, as the result is (almost)
    // the same.
  } else {
    // Key found, so update
    $res = quickcache_db_query("update $dbtable set CACHEEXPIRATION=".
                                (($GLOBALS["QUICKCACHE_TIME"] != 0) ?
                                (time()+$GLOBALS["QUICKCACHE_TIME"]) : 0).
                            ", GZDATA='".
                                addslashes($gzdata).
                            "', DATASIZE=$datasize, DATACRC=$datacrc where".
                            " CACHEKEY='".
                                addslashes($GLOBALS["quickcache_key"]).
                            "'"
                           );
    // This might be an update too much, but it shouldn't matter
  }
}

/* quickcache_do_gc()
 * Performs the actual garbagecollection
 */
function quickcache_do_gc() {
  quickcache_db_query("delete from ".
                      $GLOBALS["QUICKCACHE_DB_TABLE"].
                   " where CACHEEXPIRATION<=".
                      time().
                   " and CACHEEXPIRATION!=0"
                  );

  // Are we allowed to do an optimize table-call?
  // As noted, first check if this works on your mysql-installation!
  if ($GLOBALS["QUICKCACHE_OPTIMIZE"]) {
      quickcache_db_query("OPTIMIZE TABLE ".$GLOBALS["QUICKCACHE_DB_TABLE"]);
  }
}

/* quickcache_do_start()
 * Additional code that is executed before main quickcache-code kicks in.
 */
function quickcache_do_start() {
  // Connect to db
  quickcache_db_connect();
}

/* quickcache_do_end()
 * Additional code that is executed after caching has been performed,
 * but just before output is returned. No new output can be added!
 */
function quickcache_do_end() {
  // Disconnect from db
  quickcache_db_disconnect();
}

?>
