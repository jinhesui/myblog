<?php 
//声明命名空间
namespace App\Admin\Controllers;
use Illuminate\Eloquent\BaseController;
use App\Admin\Models\Category;

//定义最终的分类控制器类，并继承基础控制器
final class CategoriesController extends BaseController
{
    //显示文章分类的列表
    public function index()
    {
        //获取分类的原始数据
        $categories = Category::getInstance()->fetchAll();
        //调用无限级分类的方法
        $categories = Category::getInstance()->categoryList($categories);
        //向视图赋值，并显示视图
        $this->smarty->assign('categories', $categories);
        $this->smarty->display('categories/index.html');
    }

    //显示添加的表单
    public function create()
    {
        //获取无限级分类数据
        $categories = Category::getInstance()->categoryList(Category::getInstance()->fetchAll());
        //向视图赋值，并显示视图
        $this->smarty->assign('categories', $categories);
        $this->smarty->display('categories/add.html');
    }

    //插入数据
    public function store()
    {
        //获取表单提交值
        $data['classname'] = $_POST['classname'];
        $data['orderby'] = $_POST['orderby'];
        $data['pid'] = $_POST['pid'];
        //插入数据
        if (Category::getInstance()->store($data))
        {
            $this->jump("分类数据插入成功！","?c=Categories");
        }
    }

    //显示修改的表单
    public function edit()
    {
        $id = $_GET['id'];
        $categories = Category::getInstance()->categoryList(Category::getInstance()->fetchAll());
        $category = Category::getInstance()->fetchOne("id = {$id}");
        //向视图赋值，并显示视图
        $this->smarty->assign('arrs', $categories);
        $this->smarty->assign('category', $category);
        $this->smarty->display('categories/edit.html');
    }

    public function update()
    {
        //获取表单提交数据
        $id = $_POST['id'];
        $data['classname'] = $_POST['classname'];
        $data['orderby'] = $_POST['orderby'];
        $data['pid'] = $_POST['pid'];
        if (Category::getInstance()->update($data,$id))
        {
            $this->jump("id={$id}的记录更新成功！","?c=Categories");
        }
    }

    //删除记录
    public function destory()
    {
        $id = $_GET['id'];
        if (Category::getInstance()->destory($id))
        {
            $this->jump("id={$id}的记录删除成功！","?c=Categories");
        }
    }

}