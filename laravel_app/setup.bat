@echo off
echo ======================================
echo Instalador automatico Mi Mercado Global
echo ======================================

echo.
echo 1. Creando carpeta tools...
if not exist tools mkdir tools
cd tools

echo.
echo 2. Descargando PHP portable...
powershell -Command "Invoke-WebRequest https://windows.php.net/downloads/releases/php-8.2.12-Win32-vs16-x64.zip -OutFile php.zip"

powershell -Command "Expand-Archive php.zip -DestinationPath php"

set PATH=%CD%\php;%PATH%

cd ..

echo.
echo 3. Instalando Composer...
powershell -Command "Invoke-WebRequest https://getcomposer.org/composer-stable.phar -OutFile composer.phar"

echo @echo off> composer.bat
echo php composer.phar %%*>> composer.bat

echo.
echo 4. Instalando dependencias Laravel...
call composer install

echo.
echo 5. Instalando dependencias Node
npm install

echo.
echo 6. Configurando entorno...
if not exist .env copy .env.example .env

php artisan key:generate

echo.
echo 7. Creando contenedor DynamoDB Local...

docker --version >nul 2>&1
if errorlevel 1 (
    echo Docker no esta instalado. Instala Docker Desktop.
    pause
    exit
)

docker run -d ^
-p 8000:8000 ^
--name dynamodb-local ^
amazon/dynamodb-local

echo.
echo 8. Esperando DynamoDB...
timeout /t 5 /nobreak > nul

echo.
echo 9. Migraciones...
php artisan migrate

echo.
echo 10. Seed demo...
php artisan mercado:seed-demo

echo.
echo ======================================
echo Instalacion completa
echo DynamoDB corriendo en puerto 8000
echo Ejecuta: php artisan serve
echo ======================================

pause