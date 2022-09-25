#!/usr/bin/env bash

if [ "$NGINX_SSL" = true ]; then
cat > /etc/nginx/conf.d/default.conf <<- EOF

server {
    listen 80;
    server_name $DEV_DOMAIN;
    return 301 https://$DEV_DOMAIN:4433\$request_uri;
}

server {
    listen 443 ssl;
    server_name $DEV_DOMAIN;
    index index.html index.htm index.php;
    error_log  /var/log/nginx/error.log error;
    access_log /var/log/nginx/access.log;
    root /var/www/public;
    charset utf-8;
    sendfile off;

    ssl_certificate     /etc/nginx/ssl-cert.crt;
    ssl_certificate_key /etc/nginx/ssl-cert.key;
    ssl_protocols       TLSv1 TLSv1.1 TLSv1.2;
    ssl_ciphers         HIGH:!aNULL:!MD5;

    location ~ \.php$ {
        try_files \$uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass php:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        fastcgi_param PATH_INFO \$fastcgi_path_info;
    }
    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
        gzip_static on;
    }

    location ~ /\.ht {
        deny all;
    }
}


EOF

else

cat > /etc/nginx/conf.d/default.conf <<- EOF

server {
    listen 80;
    server_name "~^snapp\.[^\.]+\.local$"; 
    index index.html index.htm index.php;
    error_log  /var/log/nginx/snapp-error.log error;
    access_log /var/log/nginx/snapp-access.log;
    root /var/www/public;
    charset utf-8;
    sendfile off;

    location ~ \.php$ {
        try_files \$uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass php:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        fastcgi_param PATH_INFO \$fastcgi_path_info;
    }
    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
        gzip_static on;
    }

    location ~ /\.ht {
        deny all;
    }
}

server {
    listen 80;
    server_name "~(?<tenant>.+)\-snapp2\.local$";
    #server_name "thisrd-snapp2.local";
    
    #listen 443 ssl http2;
    index index.html index.htm index.php;
    error_log  /var/log/nginx/\$tenant-error.log error;
    access_log /var/log/nginx/\$tenant-access.log;
    root /var/www/public/\$tenant/\$tenant;
    charset utf-8;
    sendfile off;

    location ~ \.php$ {
        try_files \$uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass php:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        fastcgi_param PATH_INFO \$fastcgi_path_info;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
        gzip_static on;
    }

    location ~ /\.ht {
        deny all;
    }
    #ssl_certificate     /etc/nginx/ssl/snapp.itrm-snapp2.local.crt;
    #ssl_certificate_key /etc/nginx/ssl/snapp.itrm-snapp2.local.key;
}


EOF

fi
