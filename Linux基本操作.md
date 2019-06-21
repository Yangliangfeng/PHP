#### Linux基本操作

* 获得CPU详细信息
`
cat /proc/cpuinfo
`

* 查看物理CPU的个数
`
cat /proc/cpuinfo |grep "physical id"|sort |uniq|wc -l
`

* 查看逻辑CPU的个数
`
cat /proc/cpuinfo |grep "processor"|wc -l 
`

* 显示Linux所有的系统参数
`
sysctl -a
`

* 从指定的配置文件中加载系统参数，如不指定即从/etc/sysctl.conf中加载
`
sysctl -p
`
* 查看操作系统可使用的最大句柄数
`
sysctl -a | grep fs.file-max
`
* 查看当前的使用情况
`
sysctl -a | grep fs.file-nr    返回当前已分配的，正在使用的，上限
`
* 限制用户的句柄数
`
vim /etc/security/limits.conf
root soft nofiles 65535
root hard nofiles 65535   
notice: hard 必须大于等于soft的值
`
