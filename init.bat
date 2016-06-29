@echo off
echo Running "bundle install"...
call bundle install
echo Finished "bundle install"
echo.
echo ---------------------
echo.
echo Running "npm install"...
call npm install
echo Finished "npm install"
echo.
echo ---------------------
echo.
echo Running "bower install"...
call bower install
echo Finished "bower install"
echo.
echo ---------------------
echo.
cd src
echo Running "composer install"...
composer install
echo Finished "composer install"
cd ..
echo.
echo Bambee initialization successfully!