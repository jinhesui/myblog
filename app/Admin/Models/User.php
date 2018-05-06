<?php 
//声明命名空间
namespace App\Admin\Models;
use Illuminate\Eloquent\BaseModel;

//定义最终的用户模型类，并继承基础模型类
final class User extends BaseModel
{
    //受保护的数据表名称
    protected $table = 'users';
}