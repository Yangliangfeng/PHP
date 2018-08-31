## 1.闭包若只如初见
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
