官方网址:https://www.consul.io/docs/agent/basics.html
## 运行Consul
```
consul agent -data-dir=/home/yang/consul -bind=192.168.1.200 -server -bootstrap -client 0.0.0.0 
-ui -client=0.0.0.0
* -server 代表以服务器的方式启动
* -boostrap 指定自己为leader，而不需要选举
* -ui 启动一个内置管理web界面
* -client 指定客户端可以访问的IP。设置为0.0.0.0 则任意访问，否则默认本机可以访问。 
```
## 注册
* 新建json文件,文件内容如下：
```
{
  "ID": "userservice",
  "Name": "userservice",
  "Tags": [
    "primary"
  ],
  "Address": "192.168.222.119",
  "Port": 9503,
   "Check": {
    "HTTP": "http://192.168.222.119:9503/health",
    "Interval": "5s"
   }
}

```
* 通过curl方式注册
```
curl \
    --request PUT \
    --data @p.json \
   localhost:8500/v1/agent/service/register
```
## Linux 信号表
```
1. kill -9    //9(SIGKILL);立即结束程序的运行. 本信号不能被阻塞、处理和忽略
2. kill -2    //2(SIGINT);程序终止(interrupt)信号, 譬如Ctrl+C时发出 
3. kill -15   //15(SIGTERM);可以被阻塞和处理。通常用来要求程序自己正常退出
4. kill(默认是kill -15)
```
