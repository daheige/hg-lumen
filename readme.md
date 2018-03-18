# Lumen PHP Framework demo
## 网站说明
    1.该框架基于lumen5.5.* 定制而成，调整了lumen官网提供的目录结构
    2.本项目改造采用MIT授权协议
## 网站目录
```
.
|   ├── app
│   ├── AppBoot.php         app启动文件
│   ├── bootstrap           lumen app配置文件目录
│   ├── Console
│   ├── Events
│   ├── Exceptions
│   ├── Functions           公共函数列表
│   ├── Http
│   ├── Jobs
│   ├── Libs                公共类库或第三方类库
│   ├── Listeners
│   ├── Logics              公共逻辑层
│   ├── Models              模型层
│   ├── Providers
│   ├── routes              路由配置目录
│   ├── Services            服务层目录
│   └── User.php
├── artisan
├── bin
│   └── app-init.sh         初始化脚本
├── composer.json
├── config                  配置目录（根据不同环境读取配置）
│   ├── production
│   └── testing
├── public
│   └── index.php           入口文件采用composer加载
├── readme.md
├── resources
│   └── views
├── storage                 缓存目录
│   ├── app
│   ├── framework
│   └── logs
└── vendor
    ├── autoload.php

## nginx配置
server {
        listen 80;
        index index.php index.html index.htm;
        root /web/hg-lumen/public;

        # Add index.php to the list if you are using PHP
        index index.html index.htm default.html;
        server_name hglumen.com *.hglumen.com;

        location / {
                try_files $uri $uri/ /index.php?$query_string;
        }

        # pass  FastCGI server listening on 127.0.0.1:9000
        location ~ \.php$ {
                fastcgi_pass 127.0.0.1:9000;
                fastcgi_index index.php;
                include fastcgi_params;

                fastcgi_param SCRIPT_FILENAME    $document_root$fastcgi_script_name;
                fastcgi_param APP_ENV "TESTING";#TESTING;PRODUCTION;STAGING
        }

        location ~ /\.ht {
                deny all;
        }

        location ~ .*\.(xml|gif|jpg|jpeg|png|bmp|swf|woff|woff2|ttf|js|css)$ {
                expires 30d;
        }

        error_log /web/wwwlogs/hglumen-error.log;
        access_log /web/wwwlogs/hglumen-access.log;
}
```
