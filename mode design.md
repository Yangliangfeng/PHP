# 1. 单例模式
- 概念

`
单例模式指的是在整个应用中只有一个对象实例的设计模式
`
- 应用场景

`
php常常和数据库打交道，如果在应用中如果频繁建立连接对象，进行new操作的话，会消耗大料的系统内存资源
`
- 代码示例

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
- 代码示例

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
- 代码示例

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
# 4.委托模式
* 概念

`
通过分配或委托其他对象，委托设计模式能够去除核心对象中的判决和复杂的功能性
`
* 场景
* 代码示例

```
class Mp3{
    function Mp3Play($list,$song) {
        return $list[$song];
    }
}
class Mp4{
	function Mp4Play($list,$song) {
		return $list[$song];
	}
}
class CdDelegator{

	public $delegator = null;
	public $list = [];

	function addSong($song) {
		$this->list[$song] = $song;
	}
	function __call($name,$argument) {
		if($this->delegator != null) {

			return call_user_func_array([$this->delegator,$name], $argument);
		}

		return false;
	}
}
$cddelegator = new CdDelegator();
$cddelegator->addSong('1');
$cddelegator->addSong('2');
$cddelegator->delegator = new Mp3();
echo $cddelegator->Mp3Play($cddelegator->list,'1');
```
# 5.责任链模式
* 概念

```
在责任链模式里，很多对象由每一个对象对其下家的引用而连接起来形成一条链。请求在这个链上传递，直到链上的某一个对象决定处理此请求。发出这个请求的客户端并不知道链上的哪一个对象最终处理这个请求
```
* 场景
```
商务部譬如要做个专题推广活动，活动中需要调一些商品做优惠
1、商务部编辑需要先写文案 
2、则商务部领导需要审批文案内容
3、商品部领导需要审批商品的价格或库存（万一没货呢？） 
4、后面可能还有好多领导要审批（待扩展）
流程：
编辑MM写好文案  ---->  商务部领导审批  ---->  商品部领导再批
```
* 代码示例
```
 //审批专题类
 class subject{
     public $content;
     public $state = 0;
     public function save(){
     	  file_put_contents(__DIR__.'/subject.json',json_encode($this);
     }
 }
 
 //核心类
 class Manager
 {
      public $subject;
      public $mystate = 0; //当前状态，这个很重要
      public $leader=false;//很重要，设置自己的领导是谁
      public $myname="";//当前审批者的名字
      
      public function __construct()
      {
      	  $this->subject = json_decode(file_get_contents(__DIR__.'/subject.php'));
      }
      //设置领导
      public function setLeader($leader)
      {
      	  $this->leader = $leader;
      }
      
      public function step($msg)
      {
      	  //只有当subject的state和自己的mystate 相等时才会处理
      	  if($this->subject->state == $this->mystate)//代表当前状态是自己要处理的状态
	  {
	  	if($this->leader)//判断自己的领导是否有，如果有,那么,我们把控制权交给领导
		{
		    $this->subject->state = $this->leader->mystate;//交控制权
		    file_put_contents(__DIR__.'/subject.php',json_encode($this->subject));
		}
		else
		{
		    echo '审批结束';
		}
		
		//在这执行审批通过的具体一些操作
		echo $msg.'审批者是：'.$this->myname;
	  }
	  else
	  {
	      if($this->leader)/让自己的领导去审批 .leader就是维护整个类的链
	      {
	          $this->leader->step($msg);
	      }
	  }
      }
      
 }
 class BusinessLeader extends Manager
 {
     public $myname = '商务部领导';
     public $mystate = 0;
 }
 class ProdLeader extends Manager
 {
     public $myname = '商品部领导';
     public $mystate = 837;
 }
 class LastLeader extends Manager
 {
     public $myname = '大Boss';
     public $mystate = 937;
 }
 //假设有编辑好的专题文案提交过来需要审批
 $subject = new subject();
 $subject->content = '文案内容';
 $subject->save();
 
 //领导的维护
 $busleader = new BusinessLeader();
 
 $prodleader = new ProdLeader();
 
 $lastleader = new LastLeader();
 
 $busleader->setLeader($prodleader);
 $prodleader->setLeader($lastleader);
 
 $busleader->step('审批通过');
```
# 6.命令模式
* 概念
```
将一个请求封装成一个对象，从而让你使用不同的请求把客户端参数化，对请求排队或记录请求日志，可以提供命令的撤销和恢复功能
```
* 场景
```
数据插入数据库：
1.保存数据库
2.插入缓存
3.生成静态页面
```
* 示例代码
```
class ICommand 
{
    public $isRemove = false;
    abstract function exec($object);
}
class SaveToDB extends ICommand
{
    public function exec($object)
    {
        echo '插入数据库';
    }
}
class SaveToMemcached extends ICommand
{
    public function exec($object)
    {
        echo '插入缓存';
    }
}
class GenFile extends ICommand
{
    public function exec($object)
    {
        echo '生成文件';
    }
class Model
{
    public function commint(...$commands)
    {
        foreach($commands as $command)
	{
	    if(is_subclass_of($command,'ICommand')
	    {
	         $command->exec(null);
	    }
	}
    }
}
$model = new Model();
$model->commit(new SaveToDB(),new SaveToMemcached(),new GenFile());
```
# 7.行为收集
* 概念
`
行为收集、在需要的时候才进行触发执行。各个行为之间还能互相传递数据
`
* 思路
```
创建一个类叫做Action:
1.有一个专门的方法来收集行为，仅仅是收集，但不执行
2.设置一个commit方法来统一执行
3.利用yeild生成器来获取每一步返回的值
```
* 示例代码
```
class Action 
{
    public $actions = [];
    
    public function then(callable $callable)//收集行为
    {
        $this->actions[] = $callable;
    }
    
    public function commit()
    {
        foreach($this->actions as $action)
	{
	    $get_return = $action();
	    yield $get_return;
	}
    }
}
class BaseController
{
    public $action;
    public function __construct()
    {
        $this->action = new Action();
    }
}
class NewController extends BaseController
{   public $test="测试内容";
    public function review()//演示
    
    {	//提交1、新闻评论数据 2、提交缓存数据 3、生成一些静态文件
    	$self = $this;
        $this->action->then(function() use($self){
	     echo $self->test;
	     //假设我在这完成数据库操作
	     return "数据库操作完成";
	})->then(function(){
	     //假设我们完成了缓存操作 ，譬如插入memcached
	     return "memcached ok";
	})->then(function(){
	    //静态文件的生成
	    return "静态文件的生成";
	});
    }
    
    foreach($this->action->commit() as $item)
    {
        print_r($item);
    }
}
```






