### Swoole从入门到精通

* 关于SWOOLE_BASE和SWOOLE_PROCESS模式
```
1. SWOOLE_BASE 
   如果设置worker_num = 2；ps -ef 查看进程，会发现有3个进程，两个worker进程和一个manager进程，manager进程是对子进程的
   
   管理和回收；
    
```
