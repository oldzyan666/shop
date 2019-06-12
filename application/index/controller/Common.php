<?php
    namespace app\index\controller;   

    class Common extends Cart
    {
        public function __construct()
        {
            parent::__construct();
            if(is_null(session('user')))
            {
                $this->error('请先登录',url('log/login'));
            }
            if(!empty(cache('user')))
            {
                $this->redirect(url('log/reg'));
            }
        }
    }
?>