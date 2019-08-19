<?php

namespace App\Http\Controllers;

use App\Model\User;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class UserController extends Controller
{
    //登录接口
    public function login()
    {
//        return $_POST;
        $username = $_POST['username'];
        $pwd = $_POST['pwd'];
        $arr = User::where('username',$username)->first()->toArray();
        if(!$arr)
        {
            echo "查无此人!请输入正确的用户名或者密码！";
        }else{
//            echo password_hash($pwd,PASSWORD_DEFAULT);
            if(password_verify($pwd,$arr['pass']))
            {
                //生成用户的token
                $key = "login:user:".$arr['id'];
                $userToken = $this->getUSertoken();
                Redis::set($key,$userToken);
                Redis::expire($key,7*3600);
                echo "欢迎登陆！";
            }else{
                echo "查无此人!请输入正确的用户名或者密码！";
            }
        }
    }
    //获取用户的token
    public function getUSertoken()
    {
        return substr(Str::random(13).rand(100,999).time(),6,'15');
    }
}