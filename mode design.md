# 1. 单例模式
- 概念

`
单例模式指的是在整个应用中只有一个对象实例的设计模式
`
- 应用场景

`
php常常和数据库打交道，如果在应用中如果频繁建立连接对象，进行new操作的话，会消耗大料的系统内存资源
`
- 代码

```
class DB {
    protected static $ins = null;
    public $hash;
    final protected function __construct() { //构造函数被申明为private或者protected这注定无法通过new的方法创建实例对象了
        $this->hash = rand(1,9999);
    }
    public static function getInstance(){
        if(self::$ins instanceof self) {
            return self::$ins;
        }
        self::$ins = new self();
        return self::$ins;
    }
}
```
# 2. 工厂模式
- 概念

`
工厂设计模式提供获取某个对象的新实例的一个接口
`
- 场景

```
1.系统对接多个不同类型的数据库，mysql，oracle，sqlserver
2.做支付接口的时候，未来可能对应不同的支付网关：支付宝、财付通、网银在线
```
- 代码

```
class  ProductFactory {
  static function getProduct($type) {
  $obj = false;
  if(!class_exists($type)) {
    require($type.'.php');
  }
  switch ($type) {
    case 'Dogs':
      $obj = new Dogs();
      break;
    case 'Wines':
      $obj = new Wines();
      break;
    case 'Books':
      $obj = new Books();
      break;
  }

  if(is_subclass_of($type,'IProduct')){
      return $obj;
  }else return null;
}
}
```
# 3. 注册树模式
- 概念

`
存放在应用中使用频率高的对象
`
- 代码

```
class ProductDataCenter {

public static $objectList = [];

public static function set($k,$v) {
  self::$objectList[$k] = $v;
}

public static function get($k) {
  return self::$objectList[$k];
}

public static function del($k) {
  unset(self::$objectList[$k]);
}
public static function __callStatic($name,$argument){

  $return = [];//默认返回值
  foreach (self::$objectList as $k => $v) {
    if(method_exists($v, $name)) {

      $ret = $v->$name($argument);

      if($ret) {
        $return[] = $ret;
      }
    }
  }

  return $return;
}
}
```

