#!/bin/bash
#
echo "$(tput setab 7)----------------------$(tput sgr 0)"
echo "$(tput setab 7)-$(tput setaf 4)OpenSencillo$(tput sgr 0)$(tput setab 7) $(tput setaf 0)upgrade$(tput sgr 0)$(tput setab 7)-$(tput sgr 0)"
echo "$(tput setab 7)----------------------$(tput sgr 0)"
read -p "Press $(tput setaf 3)enter$(tput sgr 0) to continue";
echo "$(tput setaf 3)Download OpenSencillo$(tput sgr 0)"
wget -O ./tmp.zip "https://github.com/Piedro1111/OpenSencillo/archive/master.zip";
unzip "tmp.zip" -d "./tmp/"
cp -R ./tmp/OpenSencillo-master/fw_core/ ./
cp -R ./tmp/OpenSencillo-master/fw_libraries/ ./
cp -R ./tmp/OpenSencillo-master/fw_modules/ ./
cp -R ./tmp/OpenSencillo-master/fw_cache/ ./
cp -R ./tmp/OpenSencillo-master/fw_templates/system/ ./fw_templates/
cp ./tmp/OpenSencillo-master/fw_headers/install.ini ./fw_headers/install.ini
cp ./tmp/OpenSencillo-master/fw_headers/main_config.php ./fw_headers/main_config.php
cp ./tmp/OpenSencillo-master/index.php ./index.php
cp ./tmp/OpenSencillo-master/cache.php ./cache.php
cp ./tmp/OpenSencillo-master/basicstrap.php ./basicstrap.php
rm -rf ./tmp/
rm tmp.zip
echo "$(tput setaf 3)Done :)$(tput sgr 0)"
