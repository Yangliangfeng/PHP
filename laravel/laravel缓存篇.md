# 写入缓存
```
1.put
Cache::put('key', 'val', 10)//写入键为key,值为val的，有效时间为10分钟

2.add() 
$bool = Cache::add('key', 'val', 10)
//跟put的区别是：如果key存在,则返回false

3.forever()
Cache::forever('key','val')
//永久写入缓存

4.判断key是否存在
Cache::has('key')
```
* 读取缓存
```
1.get
Cache::get('key')

2.pull()
Cache::pull('key')
//取出来之后，删除缓存

3.forget()
Cache::forget()
//删除缓存
```
