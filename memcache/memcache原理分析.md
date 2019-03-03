#     Memcache原理分析篇

###   Memcache工作原理

    工作原理包括存和读两部分：
    
    存：Client端通过指定的server端的ip地址进行访问，需要缓存的对象或者数据以key-value的形式保存在server端。key通过hash，
    
    根据hash值吧value放到对应的server上。
    
    读：当需要获取对象数据时，先对key进行hash，通过获得的值可以确定他被保存在哪台server上，然后再向该server发出请求。
    
    也就是说Client端，只需要知道保存hash（key）的值在哪台服务器上就可以了
    
    Memcache通过在内存里维护一个统一的巨大的hash表
    
###   Memcache特性
    
    * 1.最大30天的数据过期时间,设置为永久的也会在这个时间过期
    * 2.最大键长为250字节，大于该长度无法存储，常量KEY_MAX_LENGTH 250控制
    * 3.单个item最大数据是1MB，超过1MB数据不予存储，常量POWER_BLOCK 1048576进行控制
    * 4.最大同时连接数是200(与tomcat一致)，通过conn_init()的freetotal进行控制，最大软连接数是1024，即settings.maxconns=1024
###   删除过期item

    Memcache为每个item设置过期时间，但不是到期就把item从内存删除，而是访问item时，如果到了有效期，才把item内存中删除。

    惰性删除：
        
        延迟删除到期的item到查找进行，可以提高memcache的效率。这样不必每时每刻检查到期的item，从而提高CPU的工作效率。
  
 ### LRU算法淘汰数据
 
    当Memcached使用内存大于设置的最大内存使用时，为了腾出内存空间来存放新的数据项，Memcached会启动LRU算法淘汰旧的数据项。
    
    淘汰规则是，从数据项列表尾部开始遍历，在列表中查找一个引用计数器为0的item，把此item释放掉。
  
    为什么要从item列表尾部开始遍历呢？ 因为memcached会把刚刚访问过的item放到item列表头部，所以尾部的item都是没有或很少访

    问的，这就是LRU算法的精髓。
    
    果在item列表找不到计数器为0的item，就查找一个3小时没有访问过的item。把他释放，如果还是找不到，就返回NULL（申请内存失败）。

    老数据被踢现象：

    某个key设置的是永久有效，也一样会被删除
 
 
    
    
    
   
