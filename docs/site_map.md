## 网站目录
```
├── app
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
```
