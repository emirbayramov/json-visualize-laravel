## Job application test "JSON Visualizer"
###To install:


```git clone https://github.com/emirbayramov/json-visualize-laravel```

```cd json-visualize-laravel```

```composer update```


###Setup nginx with auth:
```sudo chmod -R 755 [path to folder]/json-visualize-laravel```

```sudo chown -R www-data:www-data [path to folder]/json-visualize-laravel```


Setup auth:
1. Verify that apache2-utils (Debian, Ubuntu) or httpd-tools (RHEL/CentOS/Oracle Linux) is installed. 
2. Create a password file and a first user `
 ```sudo htpasswd -c /etc/apache2/.htpasswd user1```

3. ```sudo nano /etc/nginx/sites-available/json-visualize```
Paste the following:
```
server {
    listen 80;
    server_name [server ip];
    root [path-to-folder]/json-visualizer-laravel/public;

    auth_basic           “Administrator’s Area”;
    auth_basic_user_file /etc/apache2/.htpasswd; 
    
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_param index.php $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

