# Plateforme d'intégration continue

## Détails

- Plateforme Docker Jenkins

### Jenkins Docker

```bash
# PHP 7.0 & dependencies
cd /root
apt-get install  wget apt-transport-https lsb-release ca-certificates && wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg && sh -c 'echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list' && apt update

apt install php7.2 php7.2-common php7.2-cli php7.2-fpm php7.2-mbstring php7.2-dom php-xdebug


# PEAR
cd /root
wget https://pear.php.net/go-pear.phar && php go-pear.phar

# PEAR Setup
pear config-set auto_discover 1 && pear channel-discover pear.phing.info && pear channel-discover pear.phpmd.org && pear channel-discover pear.pdepend.org
pear upgrade-all

# QA Dependencies
 pear install --alldeps PHP_CodeSniffer php_CompatInfo Log Text_Diff HTML_QuickForm2 Image_GraphViz MDB2 Mail_Mime PHP_Beautifier SOAP XML_Beautifier  Structures_Graph components.ez.no/Graph VersionControl_SVN-alpha Horde_Text_Diff XML_RPC2 VersionControl_Git-alpha phing/phing pdepend/PHP_Depend phpmd/PHP_PMD


wget http://www.phpdoc.org/phpDocumentor.phar && chmod a+x phpDocumentor.phar && mv phpDocumentor.phar /usr/local/bin/phpdoc

# PHPUnit
 wget -O phpunit https://phar.phpunit.de/phpunit-7.phar &&  chmod +x phpunit && cp phpunit /usr/bin/phpunit

# PHP COMPOSER
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === '93b54496392c062774670ac18b134c3b3a95e5a5e5c8f1a9f115f203b75bf9a129d5daa8ba6a13e2cc8a1da0806388a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php && chmod a+x composer.phar && mv composer.phar /usr/bin/composer
php -r "unlink('composer-setup.php');"

# PHP cpd
wget -O phpcpd.phar https://phar.phpunit.de/phpcpd.phar && chmod a+x phpcpd.phar && mv phpcpd.phar /usr/bin/phpcpd

# PHP Codebrowser
wget https://github.com/mayflower/PHP_CodeBrowser/releases/download/2.0/phpcb-2.0.phar && chmod +x phpcb-2.0.phar && mv phpcb-2.0.phar /usr/bin/phpcb

```
