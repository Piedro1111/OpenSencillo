#OpenSencillo
============

##OpenSencillo PHP framework

> Help with programming OpenSencillo PHP Framework. _We are looking for you!_

###About Stable version

> * Name: OpenSencillo;
> * Licence: [GNU/GPL](http://www.gnu.org/licenses/gpl-3.0.html);
> * Type: Framework;
> * Category: OpenSource;
> * Language: PHP 5.3+, JQUERY, HTML5
> * Year: 2015;
> * Build: 004;
> * Rev: 2015.004;
> * By: [Bc. Peter Horváth](http://phorvath.com);
> * Homepage: [Open Sencillo](http://opensencillo.com);
> * Features: File management, File Convertors, Database management, SEO, Session & Cookies management, Hash subsystem, Translates JSON file, Unit Testing, Htaccess generator, Simple image tool ...

##How to Install
============
Check: http://www.opensencillo.com/installation/

##Examples
============
Check: http://www.opensencillo.com/sencillo-php-examples/

##Module types
###Name structure
In version >= 2015.003
> [type]_[module-name].php

####Types:
* module
* info
* install
* update

##Library files
It is special modules contains system´s classes.

##Changes log
============

###ON BUILD 2015.005
1. New welcome page

###2015.004
1. Fix #9 add io_validator in to Sencillo CORE
2. Add Mastery product key validator
3. Add Mastery system key lock
4. Add float parser
5. Add object interface for core
6. Add welcome page (in yourcode.php)
7. Fix installation bug

###2015.003

1. New structure for module: [type]_[module-name].php
2. Fix #5 lib_identificator is used for read modules
3. Add bootstrap for CSS3
4. Add bootstrap pretty installer
5. Add relocation installer if sencillo not installed
6. Add new MySQL Interface
7. Cache defaults OFF
8. Gzip cache if allow
9. Add mysql full outer join
10. Add mysql left join
11. Add mysql right join
12. Add mysql inner join
13. Add action aliases
14. Add mysqli update
15. Add mysqli insert
16. Convert to GPL3
17. Add security hash
18. Add INSERT to SQL installer
19. Add SimpleImage library
20. Close issue #2 - add new htaccess generator (library only; test need)

###2015.002

1. Add mysql core function: uniqueKey
2. Add mysql core function: prepareTable
3. Add testing tool in to test.tool.framework.php
4. Add testing tool function output to browser console: print_ut,print_test
5. Removed nonobject files
6. Comment old cookie system and old session system (as depecrated)
7. Full comment main config (as depecrated)
8. Fix #1 Installer problem: Problem 1,3 solved

###2015.001

1. Add default login template
2. Add default registration template
3. Add default account template
4. Add installer main screen template
5. Add logman default login function

###2014.012

1. Add library support system lib_identificator
2. Add exception for load lib_identification
3. Add information subsystem
4. Repair mod_indentificator path
5. Add back support for old Sencillos
6. Add lib_identificator to bootstrap
7. Add library for delete files named file.delete.fdel.php
8. Fix core bugs in openTable
9. Fix logman support version 2014.012
10. Root folder "framework" set as variable subfolder
11. Add check session to logman
12. Add login by logman via hash sha512
13. Fix translate tool (translate file not exist)
14. Add create translate 
15. Add cache allow / disallow

###2014.011

1. Repair install path
2. Add PDF2JPG support
3. Add prebuild path to jquery
4. Add translate library

###2014.010
###2014.009
###2014.008

1. Add back compatible mode
2. Upgrade installer from version 2014.005 to 2014.008
3. Upgrade broken path in installer
4. Upgrade broken path in core
5. Add new path in core chmod
6. Delete boxid from default install mode
7. Upgrade to nodatabase module installer mod_identificator.php
8. Add database select
9. Add GNU GPL terms

###2014.007

1. Rename folders cms_* to fw_*
2. Merged developer version to version 2014.007
3. Upgrade mod_identificator.php from version 2014.002 to 2014.007
4. Add default libraries
5. Rename root folder "cms" to root folder "framework"

##Older
============

###OBSOLETE 2014.006 AND OLDER

1. Minimal support for modules 2014.007 and up
2. Not support library
3. Old layout subsystem
4. Instalation problems in 2014.005 and older
5. Security subsystem problem
