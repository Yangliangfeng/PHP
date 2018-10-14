* 配置
 ```
 选择database方式:
 .env文件：
 QUEUE_DRIVER = database;
 ```
 * 基本步骤
 ```
 1. 生成所需要的迁移文件
 php artisan queue:table
 
 2.执行迁移
 php artisan migrate
 
 3.创建任务类
 php artisan make:job sendEmail
 
 4.0 修改SendEmail任务类
 
 4.控制器中的修改
 use DispatchesJobs  //引入这个类
 dispatch('具体的任务')
 
 5.运行队列监听器
 php artisan queue:listen
 
 6.队列执行失败的表
 php artisan queue:failed-table
 
 7.执行迁移
 php artisan migrate
 
 8.常用的查看的命令:
  * php artisan queue:failed    //查看执行失败的队列
  * php artisan queue:retry 1   //重新执行ID为1的失败的队列
  * php artisan queue:retry all  //重新执行所有失败的队列
  * php artisan queue:forget 1 //删除ID为1的失败的队列
  * php artisan queue:flush    //删除所有失败的队列
 ```
