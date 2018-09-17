## 安装PHP PDO Mysql扩展
```
1. phpize
2. sudo ./configure --with-php-config=/usr/local/php7/bin/php-config  --with-pdo-mysql=mysqlnd
3. make 
4. make install
5. 修改 php.ini文件: extension=pdo_mysql.so
```
