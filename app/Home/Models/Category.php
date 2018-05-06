<?php 
namespace App\Home\Models;
use Illuminate\Eloquent\BaseModel;

final class Category extends BaseModel
{
    //受保护的数据表名称
    protected $table = 'categories';

    //获取带文章数的文章分类数据
    public function fetchAllWithCount()
    {
        //构建查询的SQL语句
        $sql = "select categories.*,count(posts.id) as records from {$this->table} ";
        $sql .= "left join posts on categories.id = posts.category_id ";
        $sql .= "group by categories.id";
        //执行SQL语句，并返回结果（二维数组）
        return $this->pdo->fetchAll($sql);
    }

    //获取无限级分类数据
    public function categoryList($arrs, $level = 0, $pid = 0)
    {
        //静态变量，用来保存结果数组
        //静态变量：函数或方法执行完毕，该变量不销毁
        //静态变量：只在第1次调用函数或方法时，初始化一次，以后不再初始化
        // $arrs 代表原始分类数据； $level 代表菜单层级； $pid 代表上层的id
        static $categories = array();
        //循环原始分类数据
        foreach ($arrs as $arr)
         {
            //如果当前菜单的 pid 等于参数 $pid,给新数组添加新元素
            if ($arr['pid'] == $pid)
             {
                $arr['level'] = $level; //菜单层级
                $categories[] = $arr; //将添加了菜单层级的元素，追加到新数组中

                //方法的递归调用
                $this->categoryList($arrs, $level + 1, $arr['id']);
            }
        }
        return $categories; //返回无限级分类数组
    }
}