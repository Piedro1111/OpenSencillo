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
> * Year: 2018;
> * Build: 106;
> * Rev: 2018.106;
> * By: [Bc. Peter Horváth](http://phorvath.com);
> * Homepage: [Open Sencillo](http://opensencillo.com);
> * Features: File management, File Convertors, Database management, SEO, Session & Cookies management, Hash subsystem, Translates JSON file, Unit Testing, Htaccess generator, Simple image tool ...


##How to Install
============
1. [Download](https://github.com/Piedro1111/OpenSencillo/archive/master.zip) OpenSencillo.
2. Upload sencillo to the webroot directory on your server.
3. Go to [www.example.com/fw_core/core_installer.php](www.example.com/fw_core/core_installer.php) and show you install guide as in the picture:
![Installation screen](http://www.opensencillo.com/wp-content/uploads/2015/02/install-e1424023344141.png)
4. After installation you can see default welcome screen with information about OpenSencillo.
5. Now you can write code in yourcode.php and create profesional website.

Check: http://www.opensencillo.com/installation-2015-003/

##Examples
============
Check: http://www.opensencillo.com/category/examples/


##Module types:
* module
* info
* install
* update

##Library files
It is special modules contains system´s classes.

##Changes log
============
###2017.104
1. Update welcome screen
2. Update install.ini
3. Readme update
4. Installer bug fix
5. Change to PHP7.1 support
6. Version info update

###2017.104
1. Update welcome screen
2. Update install.ini
3. Readme update
4. Installer bug fix
5. Change to PHP7 support
6. Fix UTF-8 DB problems

###2016.106
1. Update welcome screen
2. Update install.ini
3. Readme update
4. Installer bug fix
5. Prepare alternate template - not ready for use at this time

###2015.109
1. Created SAMS - Sencillo As Module Subsystem
2. Add __DIR__ for SAMS
3. Add Gentenela free theme
4. Modify new theme

###2015.108 (only developers)
1. Created class startInfo
2. Add mail.generator.mailgen.php
3. Add minify css library named minify.css.mincss.php
4. Fix MySQL connection driver problems 
5. Fix config generator problem

###2015.107
###2015.005 - 106 (only developers)

1. New welcome page
2. Fix #17 database foreign key problem
3. New structgen class for generating menu or galleries
4. Update manager tool #3 - no gui
5. Paylock subsystem - update
6. Add OG tags
7. Add Snippet meta tags
8. Fix Snippet bug
9. Fix first start database login problems
10. Add menu generator
11. Fix foreigns keys in core_sql
12. Create config class for database layout
13. Add support config class in core_sql
14. Add structure.generator.structgen.php for generate universal structures
15. Add google.analytics.goas.php library for support Google Universal Analytics
16. Add bootUp class for in class loading sencillo

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

###2014.008 - 010

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

1. Rename folders cms_* (content management system prefix) to fw_* (framework prefix)
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
