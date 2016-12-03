FROM debian:jessie

ENV DEBIAN_FRONTEND noninteractive
RUN apt-get update && apt-get install -y apache2 libapache2-mod-auth-kerb

RUN a2enmod proxy proxy_http headers

COPY ./portal.conf /etc/apache2/sites-enabled/

CMD /usr/sbin/apache2ctl -e info -D FOREGROUND
