### PHP面试算法题
```
1. 考察点：位运算
题目： $a= 10, $b =20 在不增加中间变量的情况下，交换a和b的值
   考察知识点：位运算
   $a = $a ^ $b;
   $b = $a ^ $b;
   $a = $a ^ $b;
   
2. 考察点：php引用
$arr = ["a", "b", "c"];

foreach($arr as $key => &$value) {

}

var_dump($arr);

foreach($arr as $key => $value) {

}
var_dump($arr);

2-1 PHP引用
   
$data = ['a','b','c']; 

foreach($data as $k=>$v){

    $v = &$data[$k];

}

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

5. php实现快速排序
   
   function quick_sort($array) {

    if (count($array) <= 1) return $array;

    $key = $array[0];

    $left_arr = array();

    $right_arr = array();

    for ($i=1; $i<count($array); $i++){

        if ($array[$i] <= $key)

            $left_arr[] = $array[$i];

        else

            $right_arr[] = $array[$i];

    }

    $left_arr = quick_sort($left_arr);

    $right_arr = quick_sort($right_arr);

    return array_merge($left_arr, array($key), $right_arr);

}

6. 1） echo输出false和true的值； 2）浮点类型不能用于精确计算
   $a= 0.1; $b = 0.7;if($a+$b ==0.8){ echo true; }else{ echo false; } 
   
   解析：1，echo false和true的值；2、浮点类型不能用于精确计算；首先浮点类型的数据不能用于计算，
   他会将浮点类型转为二进制，所以有一定的损耗，故它无限接近于0.8，也就是0.79999999...，所以echo 
   应该是个false；echo false；结果是空；echo true；结果是1
```
