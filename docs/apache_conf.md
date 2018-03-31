# hg-lumen
# 需要开启重写模式 
  配置apache http.conf LoadModule rewrite_module modules/mod_rewrite.so
# htaccess配置
    在public下新建 .htaccess 加入如下内容：
    Options +FollowSymLinks
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]

# 虚拟主机配置
  <VirtualHost *:80>
    ServerName www.hglumen.com
    ServerAlias hglumen.com api.hglumen.com
    DocumentRoot "D:\web\hg-lumen\public\"
    <Directory "D:\web\hg-lumen\public">
      #Options +Indexes +Includes +FollowSymLinks +MultiViews
      AllowOverride All
      Require local
    </Directory>
  </VirtualHost>
