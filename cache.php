<?php
/*~ cache.php
.---------------------------------------------------------------------------.
|  Software: SencilloCache                                                  |
|   Version: 2015.003                                                       |
|   Contact: ph@mastery.sk                                                  |
| ------------------------------------------------------------------------- |
|    Author: Bc. Peter Horváth (original founder)                           |
| Copyright (c) 2015, Bc. Peter Horváth. All Rights Reserved.               |
| ------------------------------------------------------------------------- |
|   License: Distributed under the General Public License (GPL)             |
|            http://www.gnu.org/licenses/gpl-3.0.html                       |
| This program is distributed in the hope that it will be useful - WITHOUT  |
| ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or     |
| FITNESS FOR A PARTICULAR PURPOSE.                                         |
'---------------------------------------------------------------------------'
~*/

/* Credits:
 *
 * Based upon and inspired by:
 *     QuickCache        <andy.prevost@worxteam.com> (http://sourceforge.net/projects/quickcache)
 */

$QUICKCACHE_VERSION="v2015.003";

// Set the includedir to the quickcache-directory
$includedir     = "./fw_cache";

/* File based caching setting. */
$QUICKCACHE_DIR = $includedir . '/tmp';
                  // Directory where quickcache will store generated files.
                  // Please use a dedicated directory, and make it writable

/* IF USING DATABASE TYPE CACHE STORAGE, FILL INFO BELOW */

/* Some settings are specific for the type of cache you are running, like
 * file- or database-based.
 * Define which QuickCache  type you want to use (db storage and/or system).
 * Note: with 2.1rc1, file type is assumed unless another type is selected
 * This allows for system-specific patches & handling, as sometimes
 * 'platform independent' is behaving quite differently.
 */

/* DB based caching settings
 * Fill in your username and password
 * ONLY if you intend to use the MySQL database to store
 * cache settings, otherwise, leave blank
 */
$QUICKCACHE_DB_HOST     = $DBHost;  // Database Server
$QUICKCACHE_DB_DATABASE = $DBName; // Database-name to use
$QUICKCACHE_DB_USERNAME = $DBUser;           // Username
$QUICKCACHE_DB_PASSWORD = $DBPass;           // Password
$QUICKCACHE_DB_TABLE    = 'sencillo_cache';  // Table that holds the data
$QUICKCACHE_OPTIMIZE    = 1;            // If 'OPTIMIZE TABLE' after garbage
                                        // collection is executed. Please check
                                        // first if this works on your mySQL!

IF ($QUICKCACHE_DB_USERNAME != '') {
  $QUICKCACHE_TYPE = 'mysql'; /* means this is a 'MySQL' type cache */
} else {
  $QUICKCACHE_TYPE = 'file';  /* means this is a 'file' type cache */
}

/* IF YOU HAVE IMPLEMENTED YOUR OWN TYPE OF STORAGE OR FILE SYSTEM
 * FILL IN BELOW AND EMAIL TO 'andy.prevost@worxteam.com' TO INCLUDE IN
 * THE NEXT RELEASE OF QUICKCACHE
 */
// $QCACHE_TYPE = 'template';

/* General configuration options */
$QUICKCACHE_TIME         =   900; // Default number of seconds to cache a page
$QUICKCACHE_DEBUG        =   0;   // Turn debugging on/off
$QUICKCACHE_IGNORE_DOMAIN=   1;   // Ignore domain name in request(single site)
//$QUICKCACHE_ON           =   1;   // Turn caching on/off
$QUICKCACHE_USE_GZIP     =   1;   // Whether or not to use GZIP
$QUICKCACHE_POST         =   0;   // Should POST's be cached (default OFF)
$QUICKCACHE_GC           =   1;   // Probability % of garbage collection
$QUICKCACHE_GZIP_LEVEL   =   9;   // GZIPcompressionlevel to use (1=low,9=high)
$QUICKCACHE_CLEANKEYS    =   0;   // Set to 1 to avoid hashing storage-key:
                               // you can easily see cachefile-origin.

$QUICKCACHE_FILEPREFIX = 'qcc-';
                         // Prefix used in the filename. This enables
                         // QuickCache to (more accurately) recognize
                         // quickcache files.

if ( isCGI() ) {
  $QUICKCACHE_ISCGI = 1;    // CGI-PHP is running
} else {
  $QUICKCACHE_ISCGI = 0;    // PHP is running as module - definitely not CGI
}

// Standard functions
require $includedir . "/cache_main.php";

// Type specific implementations
require $includedir . "/type/" . $QUICKCACHE_TYPE . ".php";

// Start caching
if($QUICKCACHE_ON===1)
{
	quickcache_start();
}

/* function to determine if PHP is loaded as a CGI-PHP or as an Apache module */
function isCGI() {
  if (substr(php_sapi_name(), 0, 3) == 'cgi') {
    return true;
  } else {
    return false;
  }
}

?>
