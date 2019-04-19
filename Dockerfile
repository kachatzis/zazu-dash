FROM ubuntu:18.04

# Install Packages
RUN  apt-get update
RUN  echo 'debconf debconf/frontend select Noninteractive' | debconf-set-selections
RUN  apt-get -qq -y install curl
RUN  DEBIAN_FRONTEND=noninteractive apt-get -qq -y install apache2 php7.2 libapache2-mod-php7.2 php7.2-curl

# Configure Server
RUN  rm -rf /etc/apache2/sites-enabled/*
COPY ./src/build/etc/apache2/apache2.conf /etc/apache2/apache2.conf
COPY ./src/build/etc/apache2/ports.conf /etc/apache2/ports.conf
COPY ./src/build/etc/apache2/mods-enabled/dir.conf /etc/apache2/mods-enabled/dir.conf
COPY ./src/build/etc/apache2/sites-enabled/site.conf /etc/apache2/sites-enabled/site.conf
COPY ./src/build/etc/php/7.2/apache2/php.ini /etc/php/7.2/apache2/php.ini
COPY ./src/public /var/www/html
RUN  a2enmod rewrite
RUN  a2enmod headers

# Restart
RUN  /etc/init.d/apache2 stop
RUN  /etc/init.d/apache2 start

# CMD
COPY ./src/build/run-apache2 /usr/local/bin/run-apache2
CMD ["apachectl","-DFOREGROUND"]