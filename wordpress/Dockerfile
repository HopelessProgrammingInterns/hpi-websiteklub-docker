FROM wordpress:latest
COPY docker-entrypoint.sh /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]
CMD apache2ctl -D FOREGROUND
