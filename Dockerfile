FROM ubuntu:trusty
# FROM debian:wheezy

RUN DEBIAN_FRONTEND=noninteractive apt-get update && apt-get install -y apache2 php5 nagios3
RUN htpasswd -b -c /etc/nagios3/htpasswd.users nagiosadmin nagiosadmin

RUN echo 'exit 0' > /usr/sbin/policy-rc.d
RUN invoke-rc.d apache2 start
RUN invoke-rc.d nagios3 start
RUN echo 'exit 1' > /usr/sbin/policy-rc.d

ADD . /vshell
WORKDIR /vshell

RUN ./install.php

EXPOSE 80

CMD ["/usr/sbin/apache2ctl -D FOREGROUND"]
