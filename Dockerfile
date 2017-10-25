FROM centos:latest

MAINTAINER "KoKsPfLaNzE" <kokspflanze@protonmail.com>

ENV container docker

RUN rpm -Uvh https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm \
 && rpm -Uvh https://mirror.webtatic.com/yum/el7/webtatic-release.rpm

# normal updates
RUN yum -y update

# php && httpd
RUN yum -y install php70w php70w-opcache php70w-cli php70w-common php70w-gd php70w-intl php70w-mbstring php70w-mcrypt php70w-mysql php70w-mssql php70w-pdo php70w-odbc php70w-pear php70w-soap php70w-xml php70w-xmlrpc php70w-pecl-xdebug httpd

# tools
RUN yum -y install epel-release iproute at curl crontabs git

# pagespeed
RUN curl -O https://dl-ssl.google.com/dl/linux/direct/mod-pagespeed-stable_current_x86_64.rpm \
 && rpm -U mod-pagespeed-*.rpm \
 && yum clean all \
 && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
 && php composer-setup.php --install-dir=bin --filename=composer \
 && php -r "unlink('composer-setup.php');" \
 && rm -rf /etc/localtime \
 && ln -s /usr/share/zoneinfo/America/Phoenix /etc/localtime

# Config changes
COPY .docker-build/php.d/ /etc/php.d/
COPY .docker-build/v-host.conf /etc/httpd/conf.d/
COPY .docker-build/odbcinst.ini /tmp/odbcinst.ini
RUN cat /tmp/odbcinst.ini >> /etc/odbcinst.ini

# Add scripts
ADD .docker-build/scripts /scripts

# create webserver-default directory and clone website's repo
RUN mkdir -p /var/www/docroot

EXPOSE 80 443

CMD ["/bin/bash", "/scripts/start.sh"]
