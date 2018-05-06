<?php 
//声明命名空间
namespace App\Admin\Controllers;
use Illuminate\Eloquent\BaseController;
use App\Admin\Models\User;

//定义最终的用户控制器类，并继承基础控制器
final class UsersController extends BaseController
{
    //用户管理首页
    public function index()
    {
        //判断用户是否存在
        $this->denyAccess();
        //创建模型类对象
        $modelObj = User::getInstance();
        //获取多行数据
        $users = $modelObj->fetchAll();
        //向视图赋值，并显示视图
        $this->smarty->assign('users', $users);
        $this->smarty->display('users/index.html');
    }

    //显示添加用户的表单
    public function create()
    {
        //判断用户是否存在
        $this->denyAccess();
        $this->smarty->display('users/add.html');
    }

    //插入用户数据
    public function store()
    {
        //判断用户是否存在
        $this->denyAccess();
        //获取表单提交值
        $data['name'] = $_POST['username'];
        $data['password'] = md5($_POST['password']);
        $data['email'] = $_POST['email'];
        $data['phone'] = $_POST['phone'];
        $data['created_at'] = date('Y-m-d H:i:s',time());
        //判断两次输入的密码是否一致
        if ($data['password'] != md5($_POST['confirm_password'])) {
            $this->jump("两次输入的密码不一致！","?c=Users");
        }
        //创建模型类对象
        $modelObj = User::getInstance();
        //判断用户是否已经注册
        if ($modelObj->rowCount("name='{$data['name']}'")) 
        {
            $this->jump("用户名 {$data['name']} 已经被注册了！","?c=Users");
        }
        //判断用户数据是否插入成功
        if ($modelObj->store($data)) 
        {
            $this->jump("用户信息添加成功！","?c=Users");
        } else {
            $this->jump("用户信息添加失败！","?c=Users");
        }
    }

    //显示编辑的表单
    public function edit()
    {
        //判断用户是否存在
        $this->denyAccess();
        //获取地址栏传递的id
        $id = $_GET['id'];
        //创建模型类对象
        $modelObj = User::getInstance();
        //获取指定 id 的数据
        $user = $modelObj->fetchOne("id = {$id}");
        //向视图赋值，并显示视图
        $this->smarty->assign('user', $user);
        $this->smarty->display('users/edit.html');
    }

    //更新用户数据
    public function update()
    {
        //判断用户是否存在
        $this->denyAccess();
        //获取表单提交值
        $id = $_POST['id'];
        $data['name'] = $_POST['username'];
        $data['email'] = $_POST['email'];
        $data['phone'] = $_POST['phone'];
        $data['activated'] = $_POST['activated'];
        $data['is_admin'] = $_POST['role'];
        $data['updated_at'] = date('Y-m-d H:i:s',time());
        //判断密码是否为空
        if (!empty($_POST['password']) && !empty($_POST['confirm_password'])) {
            //判断两次输入的密码是否一致
            if ($_POST['password'] == $_POST['confirm_password']) {
                $data['password'] = md5($_POST['password']);
            }
        }
        //创建模型类对象
        $modelObj = User::getInstance();
        //判断数据是否更新成功
        if ($modelObj->update($data,$id)) {
            $this->jump("id={$id}用户更新成功！","?c=Users");
        } else {
            $this->jump("id={$id}用户更新失败！","?c=Users");
        }
    }

    //删除用户
    public function destory()
    {
        //判断用户是否存在
        $this->denyAccess();
        //获取地址栏传递的id
        $id = $_GET['id'];
        //创建模型类对象
        $modelObj = User::getInstance();
        //判断是否删除成功
        if ($modelObj->destroy($id)) {
            $this->jump("id={$id}的用户删除成功！","?c=Users");
        } else {
            $this->jump("id={$id}的用户删除失败！","?c=Users");
        }
    }

    //用户登陆表单
    public function login()
    {
        $this->smarty->display('users/login.html');
    }

    //用户登陆验证方法
    public function loginCheck()
    {
        //(1)获取表单提交值
        $name = $_POST['username'];
        $password = md5($_POST['password']);
        //(2)判断用户的账号是否正确
        $user = User::getInstance()->fetchOne("name = '$name' and password = '$password'");
        if (empty($user)) {
            $this->jump("用户名或密码不正确！","?c=Users&a=login");
        }
        //(3)更新用户资料：最后登录的IP、最后登录的时间、登录总次数
        $data['last_login_ip'] = $_SERVER['REMOTE_ADDR'];
        $data['last_actived_at'] = date('Y-m-d H:i:s',time());
        $data['login_times'] = $user['login_times'] + 1;
        if (!User::getInstance()->update($data,$user['id'])) {
            $this->jump("用户信息更新失败！","?c=Users&a=login");
        }
        //(4)将用户状态信息写入SESSION
        $_SESSION['uid'] = $user['id'];
        $_SESSION['username'] = $name;
        //(5)跳转到网站后台首页
        $this->jump("{$name}用户登陆成功，正在跳转…","?c=Index&a=index");
    }

    //用户退出方法
    public function logout()
    {
        //删除SESSION数据
        unset($_SESSION['username']);
        unset($_SESSION['uid']);
        //删除SESSION文件
        session_destroy();
        //删除SESSION对应的COOKIE数据
        setcookie(session_name(),false);
        //跳转到后台登录页面
        $this->jump("用户退出成功！","?c=Users&a=login");
    }

}