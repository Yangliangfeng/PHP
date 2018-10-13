### Laravel文件上传
* Laravel配置文件的修改
```
 'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],
        
        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),//这个存储位置代表的是storage/app/public目录下
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
        //可以任意增加上传文件的位置
        'uploads' => [
            'driver' => 'local',
            'root' => public_path('uploads'),//这个存储位置代表的是public/uploads目录下
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
        //s3对应的是亚马逊的云盘
        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
        ],

    ],
```
* 控制器的处理
```
$fileCharater = $request->file('source');
if ($fileCharater->isValid()) {
    
    //获取文件的扩展名 
    $ext = $fileCharater->getClientOriginalExtension();
    //获取文件的绝对路径(上传文件时的临时文件)
    $path = $fileCharater->getRealPath();
    //获取文件的类型
    $type = $fileCharater->getClientMimeType();
    //获取文件的原名
    $orginalName = $fileCharater->getClientOriginaName();
    //生成新的文件名
    $fileName = date('YmdHis').'_'.unique().$ext;
    //存储文件。disk里面的public。总的来说，就是调用disk模块里的public配置
    $bool = Storage::disk('public')->put($filename, file_get_contents($path));//返回布尔值
}
```
