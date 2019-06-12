<?php
namespace app\admin\validate;
use think\Validate;

class Admin extends Validate{

    protected $rule = [
          'user'=>'require',
          'pwd'=>'require|min:6',
          'email'=>'email',
           'phone'=>'require|length:11|number'
    ];
    protected $message = [
          'user.require'=>'用户名不能为空',
          'pwd.require'=>'密码不能为空',
           'pwd.min'=>'密码不能少于6位',
           'email'=>'邮箱格式有误',
           'phone.require'=>'手机号不能为空',
           'phone.min'=>'手机号长度必须是11位',
           'phone.number'=>'手机号必须为数字'
    ];
}