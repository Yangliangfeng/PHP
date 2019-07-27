# Swoft框架的使用

* swoftcli自动检测代码更新工具（开发中使用）
```
1. 启动swoft服务

swoftcli run -c http:start -b bin/swoft

```

### 实例之JSON参数自动转实体对象

* ProductEntity实体类
```
 private $prod_id;
    private $prod_name;
    private $prod_price;

    public function getProdId() {
        return $this->prod_id;
    }

    public function setProdId($prod_id) {
        $this->prod_id = $prod_id;
    }

    public function getProdName() {
        return $this->prod_name;
    }

    public function setProdName($prod_name) {
        $this->prod_name = $prod_name;
    }

    public function getProdPrice() {
        return $this->prod_price;
    }

    public function setProdPrice($prod_price) {
        $this->prod_price = $prod_price;
    }
    
```
* JSON转对象的方法-----JsonToObject
```
/**
** @param $class 类名
*/
function JsonToObject($class = '') {
    
    try{
      
        $resquest = \Swoft\Context\Context::mustGet()->getRequest();
        $contentType = $request->getHeader('content-type');
        //判断传输过来的参数是否是json
        if(!$contentType || false === strpos($contentType['0'],\Swoft\Http\Message\ContentType::JSON)) {
            return false;
        }
        $raw = $request->getBody()->getContents();
        $data = json_decode($raw, true);//json转为数组形式
        
        if(empty($class)) return $data;//单纯的json转数组
        
        $obj_class = new ReflectionClass($class);//得到$class 类的反射对象
        
        $obj_instance = $obj_class->newInstance(); //根据 反射对象创建实例
        
        $methods = $obj_class->getMethods(ReflectionMethod::IS_PUBLIC) //出发射对象的所有公有方法
        
        foreach($methods as $method) {
                  //$mathcs 形式为
                //(
                //    [0] => setProdPrice
                //    [1] => ProdPrice
                //)
                
            if(preg_match("/^set(.*)/", $method, $matchs)) {
                invokeSetterMethod($matchs['1'], $obj_class, $data, $obj_instance);
            }
        }
      
    }catch(Execption $e) {
        var_dump($e);
    }
}

function invokeSetterMethod($name, ReflectionClass $obj_class, $data, &$instance) {
    /**
    **(?<=) “肯定逆序环视”正则
    **把ProdId变成Prod_Id
    **/
    $filter_name = strtolower(preg_replace("/(?<=[a-z])([A-Z])/","_$1",$name));
    
    $props = $obj_class->getProperties(ReflectionProperty::IS_PRIVATE);//获取类的私有属性
    
    foreach($pros as $prop) {
        if($prop->getName() == $filer_name) {//存在对应有的私有属性
            $method = $obj_class->getMethod("set".$name);//取出对应的setProdId方法名
            $args = $method->getParameters();//获取参数
            if(count($args) == 1 && $data[$filter_name]) {
                $method->invoke($instance, $data[$filter_name]);
            }
        }
    }
    
}
```
