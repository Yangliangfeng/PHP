### PHP扩展

* pdo的安装
```
1. phpize
2. sudo ./configure --with-php-config=/usr/local/php7/bin/php-config  --with-pdo-mysql=mysqlnd
3. make 
4. make install
5. 修改 php.ini文件: extension=pdo_mysql.so
```

* php第三方路由
```
1. 安装
  composer require nikic/fast-route
  
2. 文档地址
   https://github.com/nikic/FastRoute
```
* php第三方php-di（依赖注入）
```
1. 文档地址
  http://php-di.org/doc/getting-started.html

2. 安装
  composer require php-di/php-di
```
* php注解第三方库
```
1. 文档地址
  https://github.com/doctrine/annotations

2. 安装
  composer require doctrine/annotations
```
