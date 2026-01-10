FROM prestashop/prestashop:1.7.8

RUN rm -rf /var/www/html/*
COPY --chown=33:33 --chmod=755 ./prestashop /var/www/html
COPY config/php/ssl.conf /etc/apache2/sites-available/ssl.conf
COPY ssl /etc/apache2/ssl

EXPOSE 443