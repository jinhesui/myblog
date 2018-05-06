<?php 
//声明命名空间
namespace App\Home\Models;
use Illuminate\Eloquent\BaseModel;

//定义最终的文章模型类，并继承基础模型类
final class Post extends BaseModel
{
    //受保护的数据表名称
    protected $table = 'posts';

    //获取文章按月份归档的数据
    public function fetchAllWithMonth()
    {
        //构建查询的SQL语句
        $sql = "select date_format(created_at,'%Y年%m月') as months,count(id) as records ";
        $sql .= " from {$this->table} ";
        $sql .= "group by months ";
        $sql .= "order by months desc";
        //执行SQL语句，并返回结果（二维数组）
        return $this->pdo->fetchAll($sql);
    }

    //获取文章连表查询的数据
    public function fetchAllWithJoin($where = 1,$startrow = 0, $pagesize = 10)
    {
        //构建查询的SQL语句
        $sql = "select  posts.*,users.name,categories.classname from {$this->table} ";
        $sql .= "left join users on posts.user_id = users.id ";
        $sql .= "left join categories on posts.category_id = categories.id ";
        $sql .= "where {$where} ";
        $sql .= "order by posts.id desc ";
        $sql .= "limit {$startrow},{$pagesize}";
        //执行SQL语句，并返回结果（二维数组）
        return $this->pdo->fetchAll($sql);
    }

    //获取指定id的连表查询的文章数据
    public function fetchOneWithJoin($where = 1,$orderby = "posts.id desc")
    {
        //构建查询的SQL语句
        $sql = "select posts.*,users.name,categories.classname from {$this->table} "; 
        $sql .= "left join users on posts.user_id = users.id " ;
        $sql .= "left join categories on posts.category_id = categories.id ";
        $sql .= "where {$where} ";
        $sql .= "order by {$orderby}";
        //执行SQL语句，并返回结果（一维数组）
        return $this->pdo->fetchOne($sql);
    }

    //获取当前自段最大id
    public function fetchOneWithMax($where = 1)
    {
        //构建查询的SQL语句
        $sql = "select max(id) from {$this->table} "; 
        $sql .= "where {$where} ";
        //执行SQL语句，并返回结果（一维数组）
        return $this->pdo->fetchOne($sql);
    }

    //更新阅读数
    public function updateRead($id)
    {
        //构建更新的SQL语句
        $sql = "update {$this->table} set read_total = read_total + 1 where id = {$id}";
        //执行SQL语句，并返回结果
        return $this->pdo->exec($sql);
    }

    //更新点赞数
    public function updatePraise($id)
    {
        //构建更新的SQL语句
        $sql = "update {$this->table} set like_total = like_total + 1 where id = {$id}";
        //执行SQL语句，并返回结果
        return $this->pdo->exec($sql);
    }
}