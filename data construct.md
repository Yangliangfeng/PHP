# 链表
- 概念 

`
链表是一种物理存储（内存）单元上非连续，非顺序的存储结构，数据元素的逻辑顺序是通过对象引用来实现的。
`
- 组成

`
链表由一系列结点组成，结点可以在运行时动态生成。每个结点包括两个部分：一个是存储数据元素的的数据域，另一个是存储下一个结点的引用。
`
1. 单链表

`
单链表有一个引用指向后续结点。
`

![](https://github.com/Yangliangfeng/PHP/raw/master/Images/singleLink.jpg)

- php DEMO

```
class Node{
    public $data = null;
    public $next = null;
    public function __construct($data,$next = null){
      $this->data = $data;
      $this->next = $next;
    }
}

class singleLinkList{
    private $header = null;
    private $last = null;
    public $size = 0;

    public function add($data) {
        $node = new Node($data);
        if($this->header == null && $this->last == null) {
            $this->header = $node;
            $this->last = $node;
        }else{
            $this->last->next = $node;
            $this->last = $node;
        }
	}

    public function del($data) {
        $node = $this->header;
        if($node->data = $data) {
            $this->header = $this->header->next;
            return TRUE;
        }else{
            while($node->next->data == $data) {
                $node->next = $node->next->next;
                return TRUE;
            }
        }
        return FALSE;
    }

    public function update($old,$new){
        $node = $this->header;
        while($node->next != null) {
            if($node->data == $old) {
                $node->data = $new;
                return TRUE;
            }
            $node = $node->$next;
        }
        echo "not found!";
        return false;
    }

    public function find($data) {
        $node = $this->header;
        while($node->next != null) {
            if($node->data == $data) {
                echo "found!";
                return;
            }
            $node = $node->next;
        }
        echo 'not found!';
    }

    public function findAll() {
        $node = $this->header;
        while($node->next != null) {
            echo $node->data;
            $node = $node->next;
        }
        echo $node->data;
      }
}
```




