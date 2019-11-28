### PHP面试题收集
```
1. 题目： $a= 10, $b =20 在不增加中间变量的情况下，交换a和b的值
   考察知识点：位运算
   $a = $a ^ $b ^ $a
   $b = $a ^ $b ^ $b
   
2. 考察点：php引用
$arr = ["a", "b", "c"];

foreach($arr as $key => &$value) {

}

var_dump($arr);

foreach($arr as $key => $value) {

}
var_dump($arr);

3. 考察点：后期静态绑定

class A {
    public static function foo() {
        static::who();
    }

    public static function who() {
        echo __CLASS__."\n";
    }
}

class B extends A {
    public static function test() {
        A::foo();
        parent::foo();
        self::foo();
    }

    public static function who() {
        echo __CLASS__."\n";
    }
}
class C extends B {
    public static function who() {
        echo __CLASS__."\n";
    }
}

C::test();
```
