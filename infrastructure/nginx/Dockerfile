FROM nginx:1.21.6-alpine

ARG DEV_DOMAIN
ARG NGINX_SSL
ENV DEV_DOMAIN $DEV_DOMAIN
ENV NGINX_SSL $NGINX_SSL

RUN apk update && apk add \
    #openssl \
    bash


#COPY ssl/generate-ssl.sh /etc/nginx/generate-ssl.sh
#RUN chmod +x /etc/nginx/generate-ssl.sh
#RUN cd /etc/nginx && ./generate-ssl.sh

# COPY vhosts/vhost.sh /etc/nginx/vhost.sh
# RUN chmod +x /etc/nginx/vhost.sh
# RUN cd /etc/nginx && ./vhost.sh

EXPOSE 80
EXPOSE 443


