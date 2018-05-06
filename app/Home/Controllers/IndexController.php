<?php 
//声明命名空间
namespace App\Home\Controllers;
use Illuminate\Eloquent\BaseController;
use App\Home\Models\Link;
use App\Home\Models\Category;
use App\Home\Models\Post;

//定义最终的首页控制器类，并继承基础控制器类
final class IndexController extends BaseController
{
    //显示前端首页
    public function index()
    {
        //获取今天的待办事项数据
        $ids = Post::getInstance()->fetchOneWithMax("posts.category_id = 1");
        $ids = $ids['max(id)'];
        //(1)获取友情链接数据
        $links = Link::getInstance()->fetchAll();
        //(2)获取无限级分类数据
        $categories = Category::getInstance()->categoryList(Category::getInstance()->fetchAllWithCount());
        //(3)获取文章按月份归档数据
        $months = Post::getInstance()->fetchAllWithMonth();
        //(4)构建搜索的条件
        $where = 1;
        if (!empty($_REQUEST['title'])) $where .= " and title like '%" . $_REQUEST['title'] . "%'"; 
        if (!empty($_GET['category_id'])) $where .= " and category_id = " . $_GET['category_id'];
        //(5)构建分页的参数
        $pagesize = 5;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $startrow = ($page - 1) * $pagesize;
        $records = Post::getInstance()->rowCount($where);
        $params = array(
            'c'=>CONTROLLER,
            'a'=>ACTION,
        );
        if (!empty($_REQUEST['title'])) $params['title'] = $_REQUEST['title']; 
        if (!empty($_GET['category_id'])) $params['category_id'] = $_GET['category_id'];
        //(6)获取分页的字符串
        $pageObj = new \Illuminate\Vendor\Pager($records,$pagesize,$page,$params);
        $pageStr = $pageObj->showPage();
        //(7)获取文章连表查询的分页数据
        $posts = Post::getInstance()->fetchAllWithJoin($where,$startrow,$pagesize);
        //向视图赋值，并显示视图
        $this->smarty->assign(array(
            'links' => $links,
            'categories' => $categories,
            'months' => $months,
            'posts' => $posts,
            'pageStr' => $pageStr,
            'ids' => $ids,
        ));
        $this->smarty->display('Index/index.html');
    }

    //显示前端列表页
    public function showList()
    {
        //获取今天的待办事项数据
        $ids = Post::getInstance()->fetchOneWithMax("posts.category_id = 1");
        $ids = $ids['max(id)'];
        //(1)获取友情链接数据
        $links = Link::getInstance()->fetchAll();
        //(2)获取无限级分类数据
        $categories = Category::getInstance()->categoryList(Category::getInstance()->fetchAllWithCount());
        //(3)获取文章按月份归档数据
        $months = Post::getInstance()->fetchAllWithMonth();
        //(4)构建搜索的条件
        $where = 1;
        if (!empty($_REQUEST['title'])) $where .= " and title like '%" . $_REQUEST['title'] . "%'"; 
        if (!empty($_GET['category_id'])) $where .= " and category_id = " . $_GET['category_id'];
        //(5)构建分页的参数
        $pagesize = 30;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $startrow = ($page - 1) * $pagesize;
        $records = Post::getInstance()->rowCount($where);
        $params = array(
            'c'=>CONTROLLER,
            'a'=>ACTION,
        );
        if (!empty($_REQUEST['title'])) $params['title'] = $_REQUEST['title']; 
        if (!empty($_GET['category_id'])) $params['category_id'] = $_GET['category_id'];
        //(6)获取分页的字符串
        $pageObj = new \Illuminate\Vendor\Pager($records,$pagesize,$page,$params);
        $pageStr = $pageObj->showPage();
        //(7)获取文章连表查询的分页数据
        $posts = Post::getInstance()->fetchAllWithJoin($where,$startrow,$pagesize);
        //向视图赋值，并显示视图
        $this->smarty->assign(array(
            'links' => $links,
            'categories' => $categories,
            'months' => $months,
            'posts' => $posts,
            'pageStr' => $pageStr,
            'ids' => $ids,
        ));
        $this->smarty->display('Index/list.html');
    }

    //显示详细内容
    public function content()
    {
        $id = $_GET['id'] + 0;
        //获取今天的待办事项数据
        $ids = Post::getInstance()->fetchOneWithMax("posts.category_id = 1");
        $ids = $ids['max(id)'];
        //更新文章阅读数
        Post::getInstance()->updateRead($id);
        //获取指定id的连表查询的文章数据
        $post = Post::getInstance()->fetchOneWithJoin("posts.id = $id");
        //获取上一条和下一条文章
        $arr[] = Post::getInstance()->fetchOneWithJoin("posts.id < $id","posts.id desc");
        $arr[] = Post::getInstance()->fetchOneWithJoin("posts.id > $id","posts.id asc");
        //向视图赋值，并显示视图
        $this->smarty->assign(array(
            'post' => $post,
            'arr' => $arr,
            'ids' => $ids,
        ));
        $this->smarty->display('Index/art.html');
    }

    //点赞方法
    public function praise()
    {
        $id = $_GET['id'] + 0;
        //判断用户是否登录
        if (isset($_SESSION['username'])) 
        {
            //判断当前文章是否点赞过
            if (!isset($_SESSION['praise'][$id]))
            {
                //更改当前ID状态
                $_SESSION['praise'][$id] = 1;
                //更新点赞数
                Post::getInstance()->updatePraise($id);
                $this->jump("点赞成功！","?c=Index&a=content&id=$id");
            } else {
                $this->jump("同一篇文章只能点赞一次！","?c=Index&a=content&id=$id");
            } 
         } else {
                $this->jump("必须是登录用户才能点赞！","./admin.php?c=Users&a=login");
        }
    }
}