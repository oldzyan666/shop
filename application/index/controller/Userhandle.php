<?php

    namespace app\index\controller;

    use think\Controller;
 
    class Userhandle extends Controller
    {
        //添加地址
        public function upaddress()
        {
            
            $address = input('post.','','trim,htmlspecialchars');
            $address['user_id'] = session('user')[0]['id'];
            $updata = db('addresslist')->insert($address);
            if($updata)
            {
                if(!empty(session('id')))
                {
                    db('addresslist')->delete(session('id'));
                }
                session('id','');
                return 'ok';
            }
        }

        //删除地址
        public function delAddress()
        {
            $id = input('post.id','');
            db('addresslist')->delete($id);
        }

        //拿地址
        public function getdata()
        {
            $id = input('post.id','');
            session('id',$id);
            $data = db('addresslist')->where('id',$id)->select();
            return json($data);
        }
    }


?>