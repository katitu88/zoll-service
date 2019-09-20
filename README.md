# MVC CMS
Status: Refactoring and recoding to symfony framework, stay tune

## Contents:
<p align="center">
  <img src="https://github.com/saintfr3ak/KubikCMS/raw/master/style/icons/kubik-logo.png?raw=true" alt="Kubik-Studio logo"/>
</p>  

1. [Dependencies/libs](#dependencies)  
	a. [Frontend](#frontend)  
	b. [Backend](#backend)  
2. [Installation (web-server configs samples)](#installation)
3. [Todo](#todo)

## Dependencies
### Frontend
* [jQuery](https://jquery.com/download/) - JS framework
* [Materialize](https://materializecss.com/) - Material Design css/js framework
* [List.js](https://github.com/javve/list.js) - Convert ajax to list div elements
* [LazyLoad](https://github.com/verlok/lazyload) - Load images on viewport
* [Viewport-checker](https://github.com/dirkgroenen/jQuery-viewport-checker) - Change/Add class to div on viewport
* [JS Cookie](https://github.com/js-cookie/js-cookie) - Control user's cookie from JS
* [Outdated Browser](https://github.com/burocratik/outdated-browser) - Detect old useragent and make notification
* [Rellax](https://github.com/dixonandmoe/rellax) - Parallax library
* [cyrillic-to-translit-js](https://github.com/greybax/cyrillic-to-translit-js) (admin-panel)
* [jQuery Form Plugin](https://github.com/jquery-form/form) - Convert form to ajax request (admin-panel)
* [SweetAlert2](https://sweetalert2.github.io/) - Awesome modals (admin-panel)
* [Ace](https://ace.c9.io/) - Syntax highlighted editor (admin-panel)

### Backend
* [Composer](https://github.com/composer/composer/) - PHP extension manager
* [Twig](https://twig.symfony.com/) - Template engine
* [Minify](https://github.com/matthiasmullie/minify/) - CSS & JavaScript minifier
* [GMagick](https://www.php.net/manual/ru/book.gmagick.php) - Best for image processing (Optimal)

## Installation
<details>
  <summary>Nginx</summary>
  
  /etc/nginx/sites-available/Site.conf sample
  
```
server {
  set $docroot "/var/www/host";
  root $docroot;
  index index.php;
  server_name host.localnet host.de;
  access_log on;

  location ~ \.(js|ico|gif|jpg|jpeg|png|css|woff|woff2|svg|mp4|zip|rar|doc|docx|xls|xlsx|pdf) {
    expires 365d;
    add_header Pragma public;
    add_header Cache-Control "public";
    try_files $uri =404;
    fastcgi_hide_header Set-Cookie;
  }

  location ~ /\. { deny all; }

  location /admin {
    auth_basic "Admin Login";
    auth_basic_user_file /etc/nginx/pma_pass;
    include fastcgi_params;
    fastcgi_pass unix:/var/run/php/php7.2-fpm.sock;
    fastcgi_param SCRIPT_FILENAME $docroot/index.php;
  }

  location / {
    include fastcgi_params;
    fastcgi_pass unix:/var/run/php/php7.2-fpm.sock;
    fastcgi_param SCRIPT_FILENAME $docroot/index.php;
  }

  listen 443 ssl; # managed by Certbot
  ssl_certificate /etc/letsencrypt/live/host.de/fullchain.pem; # managed by Certbot
  ssl_certificate_key /etc/letsencrypt/live/host.de/privkey.pem; # managed by Certbot
  include /etc/letsencrypt/options-ssl-nginx.conf; # managed by Certbot
  ssl_dhparam /etc/letsencrypt/ssl-dhparams.pem; # managed by Certbot
}

server {
  if ($host = host.de) {
    return 301 https://$host$request_uri;
  } # managed by Certbot

  listen 80;
  server_name host.localnet host.de;
  return 404; # managed by Certbot
}
```

</details>

<details>
  <summary>Apache</summary>

  ./.htaccess sample

```
RewriteEngine On
RewriteCond %{HTTP:X-Forwarded-Proto} !=https
RewriteRule .* https://%{HTTP_HOST}%{REQUEST_URI} [R=302,L]
RewriteRule !\.(pdf|mp4|woff|js|svg|gif|jpg|png|css|txt)$ index.php [L]
RewriteCond %{REQUEST_URI} !^/
RewriteRule ^(.*)$ /$1 [L]

SetEnvIf Request_URI ^/admin require_auth=true
AuthUserFile /home/m/kubik/.htpasswd
AuthName "Password Protected Area"
AuthType Basic

Order Deny,Allow
Deny from all
Satisfy any
Require valid-user
Allow from env=!require_auth

<IfModule mod_expires.c>
  ExpiresActive On
  ExpiresByType image/jpg                             "access 1 year"
  ExpiresByType image/jpeg                            "access 1 year"
  ExpiresByType image/gif                             "access 1 year"
  ExpiresByType image/png                             "access 1 year"
  ExpiresByType image/svg+xml                         "access 1 year"
  ExpiresByType image/x-icon                          "access 1 year"
  ExpiresByType text/css                              "access 1 month"
  ExpiresByType application/pdf                       "access 1 month"
  ExpiresByType application/javascript                "access 1 month"
  ExpiresByType application/x-javascript              "access 1 month"
  ExpiresByType text/x-javascript                     "access plus 1 month"
  ExpiresByType application/javascript                "access plus 1 month"
  ExpiresByType application/x-javascript              "access plus 1 month"
  ExpiresByType application/vnd.ms-fontobject         "access plus 1 month"
  ExpiresByType font/eot                              "access plus 1 month"
  ExpiresByType font/opentype                         "access plus 1 month"
  ExpiresByType application/x-font-ttf                "access plus 1 month"
  ExpiresByType application/font-woff                 "access plus 1 month"
  ExpiresByType application/x-font-woff               "access plus 1 month"
  ExpiresByType font/woff                             "access plus 1 month"
  ExpiresByType application/font-woff2                "access plus 1 month"
  ExpiresByType application/x-font-woff2              "access plus 1 month"
  ExpiresDefault                                      "access 2 days"
</IfModule>

<ifModule mod_gzip.c>
  mod_gzip_on Yes
  mod_gzip_dechunk Yes
  mod_gzip_item_include file .(html?|txt|css|js|php|jpg|gif|png|svg)$
  mod_gzip_item_include handler ^cgi-script$
  mod_gzip_item_include mime ^text/.*
  mod_gzip_item_include mime ^application/x-javascript.*
  mod_gzip_item_exclude mime ^image/.*
  mod_gzip_item_exclude rspheader ^Content-Encoding:.*gzip.*
</ifModule>
```

</details>

<details>
  <summary>Composer</summary>

  ../composer.json sample

```
{
    "autoload":{
        "psr-4":{
            "Model\\":"test/php/app/"
        }
    },
    "require": {
        "twig/twig": "^2.0",
        "twig/extensions": "^1.5",
        "matthiasmullie/minify": "^1.3"
    },
    "require-dev": {
        "symfony/web-server-bundle": "^4.2"
    }
}
```

</details>

## TODO
- [x] Code refactoring
- [ ] Make clone pages/templates function
- [ ] Check and verify templates on files/db (what is edit last)
- [ ] Settings page
- [ ] SEO page with Google/Yandex api
- [ ] Make site multi language
- [ ] Make dummy page, if DB is empty
- [ ] Make smooth animation on admin panel
