### PHP面试算法题
```
1. 考察点：位运算
题目： $a= 10, $b =20 在不增加中间变量的情况下，交换a和b的值
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

4. 100以内的质数

   素数：只能被1和它本身整除的自然数
   质数：只能被2和它本身-1整除的自然数
   
   $count = 0;
   for($i = 2; $i <= 100000; $i++) {
      $isFlag = true;
      for ($j = 2; $j < $i; $j++) {
         if(($i % $j) == '0') {
            $isFlag = false;
            break;
         }
      }
      if($isFlag) {
         $count++;
      }

   }
```
