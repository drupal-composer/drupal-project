FROM centos:latest

MAINTAINER "Maximo Mena" <mmenavas@asu.edu>

ENV container docker

# Add EPEL and Webtatic repos
RUN rpm -Uvh https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm \
 && rpm -Uvh https://mirror.webtatic.com/yum/el7/webtatic-release.rpm

# Normal updates
RUN yum -y update

# PHP, HTTPD, and other tools
RUN yum -y install \
    httpd \
    mod_ssl \
    which \
    php70w \
    php70w-cli \
    php70w-common \
    php70w-gd \
    php70w-intl \
    php70w-mbstring \
    php70w-mcrypt \
    php70w-mssql \
    php70w-mysql \
    php70w-odbc \
    php70w-opcache \
    php70w-pdo \
    php70w-pear \
    php70w-pecl-xdebug \
    php70w-soap \
    php70w-xml \
    php70w-xmlrpc \
    mysql \
    curl \
    git \
 && yum clean all

# Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
 && php composer-setup.php --install-dir=bin --filename=composer \
 && php -r "unlink('composer-setup.php');"

# Transfer files from host
COPY php.d/ /etc/php.d/
COPY v-host.conf /etc/httpd/conf.d/
COPY odbcinst.ini /tmp/odbcinst.ini
COPY ssl.conf /tmp/ssl.conf
COPY scripts /scripts

# Configuration changes
RUN rm -rf /etc/localtime \
 && ln -s /usr/share/zoneinfo/America/Phoenix /etc/localtime \
 && cat /tmp/odbcinst.ini >> /etc/odbcinst.ini \
 && sed -i -e 's/#DocumentRoot "\/var\/www\/html"/DocumentRoot "\/var\/www\/app\/web"/' /etc/httpd/conf.d/ssl.conf \
 && sed -i -e '/ServerName/r /tmp/ssl.conf' /etc/httpd/conf.d/ssl.conf

# Create DocumentRoot directory
RUN mkdir -p /var/www/app/web 

WORKDIR /var/www/app

EXPOSE 80 443

CMD ["/bin/bash", "/scripts/start.sh"]
