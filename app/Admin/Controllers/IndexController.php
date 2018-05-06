<?php 
namespace App\Admin\Controllers;
use Illuminate\Eloquent\BaseController;

final class IndexController extends BaseController
{
    public function index()
    {
        //判断用户是否存在
        $this->denyAccess();
        $this->smarty->display("Index/index.html");
    }

    public function top()
    {
        //判断用户是否存在
        $this->denyAccess();
        $this->smarty->display("Index/top.html");
    }

    public function left()
    {
        //判断用户是否存在
        $this->denyAccess();
        $this->smarty->display("Index/left.html");
    }

    public function center()
    {
        //判断用户是否存在
        $this->denyAccess();
        $this->smarty->display("Index/center.html");
    }

    public function main()
    {
        //判断用户是否存在
        $this->denyAccess();
        $this->smarty->display("Index/main.html");
    }
}