###  Gitlab的搭建以及使用

* gitlab的彻底卸载
```
1. 停止gitlab
   gitlab-ctl stop
   
2. 卸载gitlab
   rpm -e gitlab-ce

3. 查看gitlab进程
  */service log 主进程
  
4. 杀死第一个守护进程
   kill -9 PID
  
5. 删除所有包含gitlab的文件及目录
   find / -name gitlab | xargs rm -rf 
```
* 查看gitlab的版本号
```
  cat /opt/gitlab/embedded/service/gitlab-rails/VERSION
```
* kill -9 与kill 区别
```
1. kill -9 强制杀死该进程；kill有局限性，例如后台进程，守护进程

2. 执行kill命令，系统会发送一个SIGTERM信号对应的程序。SIGTERM多半会被阻塞。

3. kill -9命令，系统发送的信号对应的是SIGKILL，即exit。exit信号不会被系统阻塞，所以，kill -9能顺利杀掉进程。
```
