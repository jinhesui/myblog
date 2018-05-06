<?php 
//声明命名空间
namespace App\Admin\Controllers;
use Illuminate\Eloquent\BaseController;
use App\Admin\Models\Post;
use App\Admin\Models\Category;

//定义最终的文章控制器类，并继承基础控制器类
final class PostsController extends BaseController
{
    //显示文章列表数据
    public function index()
    {
        //(1)获取无限级分类数据
        $categories = Category::getInstance()->categoryList(Category::getInstance()->fetchAll());
        //(2)构建查询的条件
        $where = 1;
        if (!empty($REQUEST['category_id'])) $where .= " and category_id = " . $REQUEST['category_id'];
        if (!empty($REQUEST['keyword'])) $where .= " and title like '% " . $REQUEST['keyword'] . "%'";
        //(3)构建分页的参数
        $pagesize = 5;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $startrow = ($page-1)*$pagesize;
        $records = Post::getInstance()->rowCount($where);
        $params = array(
            'c'=>CONTROLLER,
            'a' =>ACTION,
        );
        if (!empty($_REQUEST['category_id'])) $params['category_id'] = $_REQUEST['category_id'];
        if (!empty($_REQUEST['keyword'])) $params['keyword'] = $_REQUEST['keyword'];
        //(4)获取连表查询的文章分页数据
        $posts = Post::getInstance()->fetchAllWithJoin($where,$startrow,$pagesize);
        //创建分页对象
        $pageObj = new \Illuminate\Vendor\Pager($records,$pagesize,$page,$params);
        $pageStr = $pageObj->showPage();
        //向视图赋值，并显示视图
        $this->smarty->assign(array(
            'categories' => $categories,
            'posts' => $posts,
            'pageStr'=>$pageStr,
        ));
        $this->smarty->display('posts/index.html');
    }

    //显示添加的表单
    public function create()
    {
        //获取无限级分类数据
        $categories = Category::getInstance()->categoryList(Category::getInstance()->fetchAll());
        //向视图赋值，并显示视图
        $this->smarty->assign('categories', $categories);
        $this->smarty->display('posts/add.html');
    }

    //插入数据
    public function store()
    {
        //获取表单提交数据
        $data['category_id'] = $_POST['category_id'];
        $data['user_id'] = $_SESSION['uid'];
        $data['title'] = $_POST['title'];
        $data['content'] = $_POST['content'];
        $data['orderby'] = $_POST['orderby'];
        $data['top'] = isset($_POST['top']) ? 1 : 0;
        $data['created_at'] = date('Y-m-d H:i:s',time());
        if (Post::getInstance()->store($data))
        {
            $this->jump("文章添加成功！","?c=Posts");
        }
    }

    //显示修改的表单
    public function edit()
    {
        $id = $_GET['id'];
        //获取无限级分类数据
        $categories = Category::getInstance()->categoryList(Category::getInstance()->fetchAll());
        //获取指定id的单行数据
        $post = Post::getInstance()->fetchOne("id = {$id}");
        //向视图赋值，并显示视图
        $this->smarty->assign(array(
            'categories' => $categories,
            'post' => $post,
        ));
        $this->smarty->display('posts/edit.html');
    }

    //更新表单数据
    public function update()
    {
        //获取表单数据
        $id = $_POST['id'];
        $data['category_id'] = $_POST['category_id'];
        $data['title'] = addslashes($_POST['title']);
        $data['orderby'] = $_POST['orderby'];
        if (isset($_POST['top']))
         {
            $data['top'] = 1;
        }
        //过滤掉特殊符号：单引号、双引号、反斜杠
        $data['content'] = addslashes($_POST['content']);
        //更新数据
        if (Post::getInstance()->update($data,$id)) 
        {
            $this->jump("id={$id}的文章更新成功！","?c=Posts");
        }
    }

    //删除文章
    public function destory()
    {
        //获取地址栏传递的id
        $id = $_GET['id'];
        if (Post::getInstance()->destory($id)) {
            $this->jump("id={$id}的文章删除成功！","?c=Posts");
        }
    }
}