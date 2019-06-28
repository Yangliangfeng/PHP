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
  cat /opt/gitlab/embedded/service/gitlab-rails/VERSION
