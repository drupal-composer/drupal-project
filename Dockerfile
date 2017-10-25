FROM centos:latest

MAINTAINER "KoKsPfLaNzE" <kokspflanze@protonmail.com>

ENV container docker

RUN rpm -Uvh https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm \
 && rpm -Uvh https://mirror.webtatic.com/yum/el7/webtatic-release.rpm

# normal updates
RUN yum -y update

# php && httpd
RUN yum -y install php70w php70w-opcache php70w-cli php70w-common php70w-gd php70w-intl php70w-mbstring php70w-mcrypt php70w-mysql php70w-mssql php70w-pdo php70w-odbc php70w-pear php70w-soap php70w-xml php70w-xmlrpc php70w-pecl-xdebug httpd

# Set timezone 
RUN  rm -rf /etc/localtime \
 && ln -s /usr/share/zoneinfo/America/Phoenix /etc/localtime

# Config changes
COPY .docker-build/php.d/ /etc/php.d/
COPY .docker-build/v-host.conf /etc/httpd/conf.d/
COPY .docker-build/odbcinst.ini /tmp/odbcinst.ini
RUN cat /tmp/odbcinst.ini >> /etc/odbcinst.ini

# Add scripts
COPY .docker-build/scripts /scripts

# Create DocumentRoot directory
RUN mkdir -p /app/web 

# Add sample php file to DocumentRoot
COPY .docker-build/index.html /app/web/

EXPOSE 80 443

CMD ["/bin/bash", "/scripts/start.sh"]
