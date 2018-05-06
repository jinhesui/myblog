<?php 
//声明命名空间
namespace App\Admin\Models;
use Illuminate\Eloquent\BaseModel;

final class Post extends BaseModel
{
    //受保护的数据表名称
    protected $table = 'posts';

    //获取连表查询的分页数据
    public function fetchAllWithJoin($where = 1, $startrow = 0,$pagesize = 10)
    {
        //构建连表查询的SQL语句
        $sql = "select posts.*,categories.classname,users.name from {$this->table} ";
        $sql .= "left join categories on posts.category_id = categories.id ";
        $sql .= "left join users on posts.user_id = users.id ";
        $sql .= "where {$where} ";
        $sql .= "order by  posts.id desc ";
        $sql .="limit {$startrow},{$pagesize}";
        //执行SQL语句，并返回结果(二维数组)
        return $this->pdo->fetchAll($sql);
    }
}