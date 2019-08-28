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
