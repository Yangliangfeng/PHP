#### Linux基本操作

* 获得CPU详细信息
cat /proc/cpuinfo

* 查看物理CPU的个数
cat /proc/cpuinfo |grep "physical id"|sort |uniq|wc -l

* 查看逻辑CPU的个数
cat /proc/cpuinfo |grep "processor"|wc -l 

* 显示Linux所有的系统参数
sysctl -a

* 从指定的配置文件中加载系统参数，如不指定即从/etc/sysctl.conf中加载
sysctl -p
