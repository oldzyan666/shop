<?php
    namespace app\index\controller;

    use think\Controller;
    use think\Request;

    class Log extends Controller
    {
        public function __construct(Request $request = null)
        {
            parent::__construct($request);
            if(!empty(session('user')))
            {
                $this->redirect(url('index/index'));
            }
        }
        //登录
        public function login()
        {
            $weibo="https://api.weibo.com/oauth2/authorize?client_id=2891905678&response_type=code&redirect_uri=http://www.project.org/index.php/index/check/weibo";
            $this->assign('weibo',$weibo);

            $wechat = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx02c6c3c8c1a73dac&redirect_uri=".urlencode('http://zyzyan.phpok.org/?backurl=http://www.project.org/index.php/index/check/wechat')."&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
            $this->assign('wechat',$wechat);
            return view();
        }
        //注册
        public function reg()
        {
            return view();
        }
    }