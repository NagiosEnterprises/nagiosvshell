FROM ubuntu:trusty

RUN DEBIAN_FRONTEND=noninteractive apt-get update && apt-get install -y apache2 php5 nagios3
RUN htpasswd -b -c /etc/nagios3/htpasswd.users nagiosadmin nagiosadmin

ADD . /vshell
WORKDIR /vshell

RUN ./install.php

EXPOSE 80

CMD ["/usr/sbin/apache2ctl -D FOREGROUND"]
