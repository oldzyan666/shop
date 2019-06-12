<?php
namespace app\admin\controller;
use think\Controller;
use think\captcha\Captcha;
class Login extends Controller
{

    protected $weibo = [
        'client_id'=>'1871493020',
        'client_secret'=>'04a6339cc61b2c3c030228c5dba242d8',
        'redirect_uri'=>'http://zyzyan.phpok.org/',
    ];

    protected $weixin = [
        'appid'=>'wx9ef9146312d72c8e',
        'redirect_uri'=>'http://zyzyan.phpok.org/',
        'secret'=>'bafb0a6d5f11b442711c55734d948f6d',
    ];

    //登录视图与微博授权与微信授权
    public function login(){
        $weibo ='https://api.weibo.com/oauth2/authorize?client_id='.$this->weibo['client_id'].'&response_type=code&redirect_uri='.$this->weibo['redirect_uri'].'?backurl=http://www.qq.com/index.php/admin/index/weibo';
        $weibo ='https://api.weibo.com/oauth2/authorize?client_id='.$this->weibo['client_id'].'&response_type=code&redirect_uri='.$this->weibo['redirect_uri'].'?backurl=http://www.qq.com/index.php/admin/index/weibo';
        $this->assign('weibo',$weibo);
        $weixin = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->weixin['appid'].'&redirect_uri='.urlencode($this->weixin['redirect_uri'].'/?backurl=http://192.168.1.124/index.php/admin/index/weixin').'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
        $this->assign('weixin',$weixin);
        return view();
    }
    //微博登录
    public function weibo(){
        $code = input('get.code');
        $url = 'https://api.weibo.com/oauth2/access_token?client_id='.$this->weibo['client_id'].'&client_secret='.$this->weibo['client_secret'].'&grant_type=authorization_code&redirect_uri='.$this->weibo['redirect_uri'].'&code='.$code.'';
        $curl = curl($url,[]);
        $curl = json_decode($curl,true);
//        dump($curl);die;
        $url = 'https://api.weibo.com/2/users/show.json?access_token='.$curl['access_token'].'&uid='.$curl['uid'].'';
        $curl = curl($url);
        $curl = json_decode($curl,true);
//        dump($curl);die;
        $select = db('admin')->where('idstr',$curl['idstr'])->find();
        if($select){
            session('profile_image_url',$curl['profile_image_url']);
            session('name',$curl['name']);
            $this->success('登录成功',url('index'));
        }else{
            $idstr = $curl['idstr'];
            $name = $curl['name'];
            session('name',$name);
            session('idstr',$idstr);
            session('profile_image_url',$curl['profile_image_url']);
            return view();
        }


    }
    //微博绑定账号过程
    public function binding(){
        $post = input('post.');
        $sele = db('admin')->where(['user'=>$post['user'],'pwd'=>md5($post['pwd'])])->select();
        if(!empty($sele[0]['idstr'])){
            $this->success('此账号已被绑定了',url('login'));
        }else{
            $select = db('admin')->where(['user'=>$post['user'],'pwd'=>md5($post['pwd'])])->find();
//        $user = $post['user'];
//        session('user',$user);
            if($select){
                $selec = db('admin')->where('idstr',session('idstr'))->find();
//          dump($selec);die;
                if($selec){
//               $sele = db('admin')->where('idstr',session('idstr'))->select();
                    $this->success('登录成功',url('index'));
                }else{
                    $data = [
                        'idstr' => session('idstr'),
                        'nickname'=>session('name'),
                        'img'=>session('profile_image_url'),
                    ];
                    $upda = db('admin')->where('user',$post['user'])->update($data);
                    $this->success('绑定成功',url('index'));
                }

            }else{
                $this->error('用户密码错误或此用户不存在',url('login'));
            }
        }


    }
    //微博不绑定
    public function notbin(){
        $data = [
            'nickname'=>session('name'),
            'idstr'=>session('idstr'),
            'img'=>session('profile_image_url'),
        ];
        $insert = db('admin')->insert($data);
        $this->error('登录成功',url('index'));
    }


    //微信登录
    public function weixin(){
        $code = input('get.code');
//        dump($code);die;
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->weixin['appid'].'&secret='.$this->weixin['secret'].'&code='.$code.'&grant_type=authorization_code';
        $curl = curl($url);
        $curl = json_decode($curl,true);
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$curl['access_token'].'&openid='.$curl['openid'].'&lang=zh_CN';
        $curl = curl($url);
        $curl = json_decode($curl,true);
        $select = db('admin')->where('idstr',$curl['openid'])->find();
        if($select){
            session('nickname',$curl['nickname']);
            session('headimgurl',$curl['headimgurl']);
            $this->success('登录成功',url('index'));
        }else{
            session('openid',$curl['openid']);
            session('nickname',$curl['nickname']);
            session('headimgurl',$curl['headimgurl']);
            return view();
        }

    }
    //微信绑定账号过程
    public function xinbin(){
        $post = input('post.');
        $sele = db('admin')->where(['user'=>$post['user'],'pwd'=>md5($post['pwd'])])->select();
//        print_r($sele);die;
        if(!empty($sele[0]['idstr'])){
            $this->success('此账号已绑定过',url('index'));
        }else{
            $select = db('admin')->where(['user'=>$post['user'],'pwd'=>md5($post['pwd'])])->find();
            if($select){
                $data = [
                    'idstr'=>session('openid'),
                    'nickname'=>session('nickname'),
                    'img'=>session('headimgurl'),
                ];
                $upda = db('admin')->where('user',$post['user'])->update($data);
                $this->success('绑定成功',url('index'));
            }else{
                $this->error('密码用户错误或此账号未注册',url('login'));
            }
        }

    }
//    微信不绑定
    public function xinnot(){
        $data = [
            'idstr'=>session('openid'),
            'nickname'=>session('nickname'),
            'img'=>session('headimgurl'),
        ];
        $inser = db('admin')->insert($data);
        $this->error('登录成功',url('index'));
    }

    // 登录功能
    public function loginis(){
        $post = input('post.','','trim,htmlspecialchars');
//        print_r($post);die;

        $code = $post['code'];
        if(!captcha_check($code)){
            $this->error('验证失败');
        }
        $select = db('admin')->where(['user'=>$post['user'],'pwd'=>$post['pwd']])->count();
        if($select){
//            session('id',$post['id']);
            session('user',$post['user']);
            $this->success('登录成功',url('index/index'));
            die;
        }else{
            $this->error('登录失败');
        }

    }
    //登录验证码
    public function code(){
        $config =    [
            // 验证码字体大小
            'fontSize'    =>     28,
            // 验证码位数
            'length'      =>    1,
            // 关闭验证码杂点
            'useNoise'    =>    false,
        ];
        $captcha = new Captcha($config);
//        $captcha->fontttf = '5.ttf';
        return $captcha->entry();
    }
    //登录后的退出
    public function sign(){
        session('user',null);
        session('nickname',null);
        session('headimgurl',null);
        session('profile_image_url',null);
        session('name',null);
        $this->error('退出成功',url('login'));
    }
    //注册
    public function reg(){
        $post = input('post.');
        $succes = [];
//        dump($post);
//        dump($post['code'],1,session('mt'));
        $admin = validate('admin');
        if(!$admin->check($post)){
            $this->error($admin->getError());
        }
        $ifconfig = [];
        $data = [
            'user'=>$post['user'],
            'pwd'=>md5($post['pwd']),
            'email'=>$post['email'],
            'phone'=>$post['phone'],
        ];
        $select = db('admin')->where('user',$post['user'])->count();
        if($select){
            $ifconfig['user'] = '用户已存在';
        }
        $select = db('admin')->where('email',$post['email'])->count();
        if($select){
            $ifconfig['email'] = '邮箱已被绑定';
        }
        $select = db('admin')->where('phone',$post['phone'])->count();
        if($select){
            $ifconfig['phone'] = '手机号已被绑定';
        }
        $code = $post['phonecode'];
        if($code!=session('mt')){
            $ifconfig['code'] = '验证码错误';
        }
        if(!empty($ifconfig)){
            return $ifconfig;
        }else{
            $inser = db('admin')->insert($data);
            $this->success('注册成功',url('login'));
        }

//       $insert = db('admin')->insert($data);
//       $this->success('注册成功');
//          $select = db('admin')->where('user',$post['user'])->count();
//        if($select){
//            $this->success('用户已存在');
//        }else{
//            $select = db('admin')->where('email',$post['email'])->count();
//            if($select){
//                $this->success('邮箱已被注册');
//            }else{
//                $select = db('admin')->where('phone',$post['phone'])->count();
//                if($select){
//                    $this->success('手机号已被绑定');
//                }else{
//                    $code = $post['phonecode'];
//                     if($code!=session('mt')){
//
//                         $this->success('验证码错误');
//                     }else{
//                         $insert = db('admin')->insert($data);
//                         $this->success('注册成功');
//
//                     }
//
//                }
//
//            }
//
//        }

    }
    //手机验证码
    public function phoneCode(){
        $post = input('post.');
//        dump($post['phone']);
        $mt = mt_rand(999,9999);
        session('mt',$mt);
//           $url = "http://v.juhe.cn/sms/send";
//           $data = [
//                  'mobile'=>$post['phone'],
//                 'tpl_id'=>'110957',
//                 'tpl_value'=>urlencode('#code#='.$mt),
//                  'key'=>'71cf36d9e8733621c79c9f9bd2ef9b6c'
//           ];
//       echo curl($url,$data);
        return json_encode($mt);
    }
}





