## 1.闭包
* 定义
`
在函数内部使用外部函数定义的变量
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
