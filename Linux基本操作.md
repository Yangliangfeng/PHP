#### Linux基本操作

* 获得CPU详细信息
cat /proc/cpuinfo

* 查看物理CPU的个数
cat /proc/cpuinfo |grep "physical id"|sort |uniq|wc -l

* 查看逻辑CPU的个数
cat /proc/cpuinfo |grep "processor"|wc -l 
