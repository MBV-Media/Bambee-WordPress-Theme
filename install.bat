@echo off

echo.

echo running bundle install...
echo.
call bundle install
echo.
echo bundle install finished!

echo.

echo running npm install...
echo.
call npm install
echo.
echo npm install finished!

echo.

echo running bower install...
echo.
call bower install
echo.
echo bower install finished!

echo.

echo running composer install...
echo.
cd src
call composer install
cd..
echo.
echo composer install finished!

echo.

pause