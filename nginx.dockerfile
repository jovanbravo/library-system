FROM nginx:1.26.3-alpine3.20

ARG NGINX_USER=nginx
ARG NGINX_GROUP=nginx

RUN mkdir -p /var/www/html/public

ADD nginx/app.conf /etc/nginx/conf.d/default.conf

RUN sed -i "s/user www-data/user ${NGINX_USER}/g" /etc/nginx/nginx.conf

RUN adduser -g ${NGINX_GROUP} -s /bin/sh -D ${NGINX_USER}