<?php
    namespace app\index\validate;

    use think\Validate;

    class Regcheck extends Validate
    {
        protected $rule = [
            'user'  =>  'require|alphaDash|min:5|max:15',
            'pwd' =>  'require|min:6|max:12|alphaDash',
            'pho' => 'number|length:11'
        ];

        protected $message = [
            'user.require' => '请输入用户名',
            'pwd.require' => '密码不能为空',
            'pwd.min' => '密码不能少于6位',
            'user.alphaDash' => '用户名不符合规定',
            'pwd.alphaDash' => '用户名不符合规定',
            'user.min' => '用户名最少5位',
            'user.max' => '用户名最多15位',
            'pwd.min' => '密码最少6位',
            'pwd.max' => '密码最多12位',
            'pho.number' => '这不是手机号码',
            'pho.length' => '手机号码长度不对'
        ];
    }   
?>