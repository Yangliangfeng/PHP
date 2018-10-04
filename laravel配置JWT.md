* 通过composer安装php的JWT库
```
composer require tymon/jwt-auth 0.5.*
```
* 在config/app.php下添加以下配置信息:
```
'providers' => [

        /*
         * Laravel Framework Service Providers...
         */              
        ...
        Tymon\JWTAuth\Providers\JWTAuthServiceProvider::class
    ],
'aliases' => [
        ...
        'JWTAuth' => Tymon\JWTAuth\Facades\JWTAuth::class,
        'JWTFactory' => Tymon\JWTAuth\Facades\JWTFactory::class,
    ],

```
* 发布 JWT 的配置文件到 config/jwt.php
```
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\JWTAuthServiceProvider"
```
* 生成 JWT_SECRET
```
php artisan jwt:generate
```
```
/***
** 如果生成密钥时报的一个错误Method Tymon\JWTAuth\Commands\JWTGenerateCommand::handle() does not exist
**导致这个的原因是因为jwt版本与laravel版本冲突 
**解决方法：
** 1.找到config/app.php下的这一段代码删除掉
**    Tymon\JWTAuth\Providers\JWTAuthServiceProvider::class 
** 2.composer require tymon/jwt-auth:dev-develop --prefer-source
** 3.在config/app.php中
**    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */              
        ...
        Tymon\JWTAuth\Providers\LaravelServiceProvider::class
    ],
    4.php artisan jwt:secret
***/
```
* 使用的注意事项:
1. 在生成token的控制器中引入:
```
use Tymon\JWTAuth\Facades\JWTAuth;

然后生成token时调用:
$token =  JWTAuth::fromUser($user);
```
2. 在数据模型model中
```
use Tymon\JWTAuth\Contracts\JWTSubject;

//必须要实现JWTSubject接口
class User extends Authenticatable implements JWTSubject{
      /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
```

