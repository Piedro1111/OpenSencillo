SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE IF NOT EXISTS `console` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `time` datetime NOT NULL,
  `title` varchar(25) NOT NULL,
  `data` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8 MAX_ROWS=10000;

CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item` varchar(255) CHARACTER SET utf8 COLLATE utf8_slovak_ci DEFAULT NULL,
  `icon` varchar(255) NOT NULL,
  `module` varchar(255) DEFAULT NULL,
  `template_file` varchar(255) DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `url` text,
  `perm` smallint(6) NOT NULL DEFAULT '0',
  `view_parameter` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `sencillo_cache` (
  `CACHEKEY` varchar(255) COLLATE utf8_slovak_ci NOT NULL,
  `CACHEEXPIRATION` int(11) NOT NULL,
  `GZDATA` blob,
  `DATASIZE` int(11) DEFAULT NULL,
  `DATACRC` int(11) DEFAULT NULL,
  PRIMARY KEY (`CACHEKEY`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sign` text COLLATE utf8_slovak_ci,
  `active` int(1) DEFAULT NULL,
  `login` varchar(255) COLLATE utf8_slovak_ci DEFAULT NULL,
  `pass` varchar(255) COLLATE utf8_slovak_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_slovak_ci DEFAULT NULL,
  `fname` varchar(255) COLLATE utf8_slovak_ci DEFAULT NULL,
  `lname` varchar(255) COLLATE utf8_slovak_ci DEFAULT NULL,
  `perm` int(4) DEFAULT NULL,
  `ip` varchar(20) COLLATE utf8_slovak_ci DEFAULT NULL,
  `agent` text COLLATE utf8_slovak_ci,
  `date` varchar(20) COLLATE utf8_slovak_ci DEFAULT NULL,
  `time` varchar(20) COLLATE utf8_slovak_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

CREATE TABLE IF NOT EXISTS `usersPasswordCodes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(1) DEFAULT NULL,
  `code` varchar(5) COLLATE utf8_slovak_ci DEFAULT NULL,
  `param` int(1) DEFAULT NULL,
  `expire` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

CREATE TABLE IF NOT EXISTS `virtual_system_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(255) NOT NULL,
  `perm` int(4) NOT NULL,
  `switch` tinyint(1) NOT NULL DEFAULT '1',
  `function` varchar(25) NOT NULL,
  `command` varchar(255) NOT NULL,
  `commander` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
