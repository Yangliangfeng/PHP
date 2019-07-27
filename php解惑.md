## 1. 闭包若只如初见
* 定义
`
在函数内部使用外部函数定义的变量（特殊的匿名函数）
`
* 实例代码
```
//没有使用use
$message = "Hello World";
$func = function()
{
    return $message;
}
echo $func();
//Notice: Undefined variable: message

//使用use
$func = function () use($message)
{
    return $message;
}
echo $func();
// "Hello World"
```
## 2. global与$GLOBALS的藕断丝连
* 需要明白的概念
1. global是在函数内部定义global变量
2. GLOBALS是定义全局变量，只能用在当前脚本以及在当前脚本include和require所包含的文件

* 验证下面概念的code
1. global $a 是外部$a的`同名引用`
2. $GLOBALS['a']是外部变量$a本身
```
$a = 1;
function test(){
    unset($GLOBALS['a']);
}
test();
echo $a;
//什么都没输出，因为此时$a已经被unset了 
```
```
$a = 1;
function test(){
    global $a;
    unset($a);
}
test();
echo $a; //输出1
```
证明删除的只是别名，$GLOBALS['a']的引用，其本身的值没有受到任何改变,也就是说global $a其实就相当于
`$a = &$GLOBALS['a']`。调用外部变量的一个别名。
* 神奇的两段等同的代码
```
$a = 0;
function test(){
    global $a;
    $a = 1;
}
test();
echo $a;    //输出1
```
```
$a = 0;
function test(){
    $a = &$GLOBALS['a'];
    $a = 1;
}
test();
echo $a   //输出1
```
* “&”兴风作浪
```
$a = 1;
$b = 2;
function test(){
    global $a, $b;
    $a = $b;
}
test();
echo $a.PHP_EOL; //输出2
echo $b.PHP_EOL; //输出2
```
由于同名引用的关系，此时函数体内$b的值为2，故而函数体内的$a的值为2，由于函数体内$a是对外部$a的同名引用，故而外部$a也就是2 
```
$a = 1;
$b = 2;
function test(){
    global $a, $b;
    $a = &$b;
}
test();
echo $a.PHP_EOL; //输出1
echo $b.PHP_EOL; //输出2
```
由于此时函数体内$a=&$b;由于&的关系，故而此时函数体内的$a已不再是对外部$a的同名引用，所以当函数体内的$a=2时，外部的$a并没有改变
* 最后一个例子了
```
$a = 1;
$b = 2;
function test(){
    global $a, $b;
    $a = &$b;
    $a = 8;
}
test();
echo $a.PHP_EOL; //输出1
echo $b.PHP_EOL; //输出8
```
此时函数体内的$a=8;由于引用传值的关系，函数体内的$b=8,再由于函数体内的$b对于外部的$b的同名引用，故而外部的$b=8 
而由于&的关系，函数体内的$a与外部$a已不再是同名引用，故而外部的$a的值没有改变

## unset面具后面的真相
unset只是用来删除指向内存的变量，如果要释放删除变量的内存时，需要$test = null;不过，需要强调下面两点:
1. 该函数只有在变量值所占空间超过256字节长的时候才会释放内存
2. 只有当指向该值的所有变量（比如有引用变量指向该值）都被销毁后，地址才会被释放（也要执行1的判断）

怎么删不掉呢~~~~
```
$test = str_repeat("1",256);  
$p = &$test;  
unset($test);  
echo $p; //怎么还会出现256个1呢？？？？
```
这下终于删除干净了~~~~
```
$test = str_repeat("1",256);  
$p = &$test;  
$s = memory_get_usage();   
$test = null;  
unset($test);  
$e = memory_get_usage();  
var_dump($p)
```
我要删除指向这个内存地址的所有变量~~~
```
$test = str_repeat("1",256);  
$p = &$test;  
$s = memory_get_usage();   
unset($p);  
unset($test);   
$e = memory_get_usage();

```
## PHP的几个概念
```
1. PECL
    pecl的英文全称是The PHP Extension Community Library,是php的社区贡献扩展库，像memcache、rar等扩展都是
    
通过这种方式来贡献的
2. PEAR
    PEAR的英文全称是：The Php Extension and Application Repository ，意思是php的应用扩展仓库，目前有很多
    
扩展，你可以在http://pear.php.net/里找到。=pear和pecl都是php的扩展，但他们是有区别的:

    1) pecl是php的底层扩展，是通过c语言来写的。
    
    2) pear是php的上层扩展，是通过php语言来写的，在项目中直接include就行了。像smarty与PHPUnit就是pear扩展
    
3. PCRE
    PCRE的全程是 Perl Compatible Regular Expressions，意思是兼容perl的正则表达式，此包由牛津大学的一名学生
    
编写，凭借着其效率和易用性，为众多程序所青睐，php中有两种正则 （posix正则会被放弃），其中一种就是引用的pcre包。

目前PCRE的最新版本是8.02。

4. preg

    preg是php中PCRE正则的函数名前缀 
    
5. PSR
   1). PSR-0自动加载（该标准已经被废弃，使用PSR-4替代）
   2). PSR-1基本代码规范 
   3). PSR-2代码样式
   4). PSR-3日志接口
   5). PSR-4自动加载
   具体见https://www.jianshu.com/p/b33155c15343博文
```
## 关于no-cache、max-age=0、must-revalidate区别
* no-cache
    
    no-cache的响应实际是可以存储在本地缓存中的，只是在与原始服务器进行新鲜度再验证之前，缓存不能将其提供给客户端使用

* must-revalidate
    
    含有must-revalidate的响应会被存储在本地缓存中，在后续请求时，该指令告知缓存：在事先没有与原始服务器进行再验证的情况下，不能提供这个对象的陈旧副本，但缓存仍然可以随意提供新鲜的副本
    
* max-age

    max-age=xxx标识了该响应从服务器那边获取过来时，文档的处于新鲜状态的秒数，若max-age=0，则表示是一个立即过期的响应（直接标记为陈旧状态）
    
* no-cache和must-revalidate的区别
    
    假设一个文档的缓存时间设置为10s，若指定no-cache，则它会强制浏览器(User Agent)必须先进行新鲜度再验证（注：不管该缓存是否新鲜），待服务器那边确认新鲜（304）后，方可使用缓存。

    若指定must-revalidate，则浏览器会首先等待文档过期（超过10s），然后才去验证新鲜度（10s之前，都会直接使用缓存，不与服务器交互）

* no-cache 与 must-revalidate, max-age=0区别

    在执行must-revalidate时，若浏览器第二次去请求服务器来做新鲜度验证，结果服务器挂了，无法访问，那么缓存需要返回一个504 Gateway Timeout的错误（这里应该是像nginx这样的代理来返回，若是浏览器如chrome，将直接是ERR_CONNECTION_REFUSED，即无法访问，连接被拒绝）。

    而如果是no-cache，当验证新鲜度时，服务器扑街，则会照样使用本地缓存显示给用户（有的总比没的好，当然有可能显示的就是旧的文档了）。

    所以must-revalidate用在对事务要求比较严苛的情况下使用（比如支付）。
```
    header("Cache-Control: no-cache, must-revalidate"); //强制不缓存
    header("Pragma: no-cache");  //禁止本页被缓存
```
## 关于PHP-FPM
```
1. 作用
    PHP-FPM(PHP FastCGI Process Manager)意：PHP FastCGI 进程管理器，用于管理PHP 进程池的软件，用于接受web服务器
    
的请求。PHP-FPM提供了更好的PHP进程管理方式，可以有效控制内存和进程、可以平滑重载PHP配置。

(1). 为什么会出现php-fpm

    fpm的出现全部因为php-fastcgi出现。为了很好的管理php-fastcgi而实现的一个程序
    
(2). 什么是php-fastcgi

    php-fastcgi 只是一个cgi程序,只会解析php请求，并且返回结果，不会管理(因此才出现的php-fpm)。
    
(3)为什么不叫php-cgi

    其实在php-fastcgi出现之前是有一个php-cgi存在的,只是它的执行效率低下，因此被php-fastcgi取代。
    
(4)那fastcgi和cgi有什么区别呢？

    区别很大，当一个服务web-server(nginx)分发过来请求的时候，通过匹配后缀知道该请求是个动态的php请求，会把这个请求转给php。
    
    在cgi的年代，思想比较保守，总是一个请求过来后,去读取php.ini里的基础配置信息，初始化执行环境，每次都要不停的去创建一个进程,
    
读取配置，初始化环境，返回数据，退出进程。

    当php来到了5的时代，大家对这种工作方式特别反感，想偷懒的人就拼命的想，我可不可以让cgi一次启动一个主进程(master),让他只读取
    
一次配置，然后在启动多个工作进程(worker),当一个请求来的时候，通过master传递给worker这样就可以避免重复劳动了。于是就产生了fastcgi。

(5)fastcgi这么好，启动的worker用完怎么办？

    当worker不够的时候，master会通过配置里的信息，动态启动worker，等空闲的时候可以收回worker。
    
(6)到现在还是没明白php-fpm 是个什么东西?
    
    就是来管理启动一个master进程和多个worker进程的程序。
    
    PHP-FPM 会创建一个主进程，控制何时以及如何将HTTP请求转发给一个或多个子进程处理。PHP-FPM主进程还控制着什
    
么时候创建(处理Web应用更多的流量)和销毁(子进程运行时间太久或不再需要了)PHP子进程。PHP-FPM进程池中的每个进程

存在的时间都比单个HTTP请求长,可以处理10、50、100、500或更多的HTTP请求。

2. 安装

    PHP在 5.3.3 之后已经把php-fpm并入到php的核心代码中了。 所以php-fpm不需要单独的下载安装。要想php支持php-fpm，
    
只需要在编译php源码的时候带上 --enable-fpm 就可以了。
```
## PHPStorm支持Swoole提示
```
1. 安装包
https://github.com/wudi/swoole-ide-helper

2. phpstorm配置
选中“External Libraries” ------> 点击右键---------->Configure PHP Include Paths
```
## 为什么Composer在生产环境要使用dumpautoload
```
composer dump-autoload (-o)

composer dumpautoload (-o)
```
## array_merge 与 array+array 的区别
```
1. 当下标为数值时：array_merge()不会覆盖掉原来的值；array＋array合并数组则会把最先出现的值作为最终结果返回，

而把后面的数组拥有相同键名的那些值“抛弃”掉（不是覆盖）。

2. 当下标为字符时：array＋array仍然把最先出现的值作为最终结果返回；而把后面的数组拥有相同键名的那些值“抛弃”掉，

但array_merge()此时会覆盖掉前面相同键名的值. 
```
    
