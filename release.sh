cd /var/www/html/;
git pull;
php composer.phar install;
php composer.phar dump-autoload;
cd $OLDPWD;
