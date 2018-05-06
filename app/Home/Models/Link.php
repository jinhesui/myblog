<?php 
//声明命名空间
namespace App\Home\Models;
use Illuminate\Eloquent\BaseModel;

//定义最终的友情链接模型类，并继承基础模型类
final class Link extends BaseModel
{
    //受保护的数据表名称
    protected $table = 'links';
}