<?php
namespace app\index\controller;

use think\Request;

class Ware extends Cart
{
    //显示商品规格
    public function product_details()
    {
        $sub = '';
        //获取模板的名称
        $mould_name =  db('ware_spec')->where('ware_id',$_GET['ware_id'])->select();
        //获得模板
        $mould_list = db('spec_list')->where('mould',$mould_name[0]['mould_name'])->select();
        foreach($mould_list as $value)
        {
            $sub .= $value['sub'].'/';
        }
        $sub = substr($sub,0,-1);
        //获得商品信息
        $ware = db('commodity')->where('id',$_GET['ware_id'])->find();
        //把上面的模板数组里面的spec中的数据变成数组
        foreach($mould_list as $key=>$value)
        {
            $mould_list[$key]['spec'] = explode('/',$value['spec']);
        }
        $this->assign('mould',$mould_list); 
        $this->assign('ware',$ware);
        $this->assign('sub',$sub);
        return view();
    }

    //价钱
    public function price()
    {
        $checkspec = '';
        foreach($_POST['up'] as $value)
        {
            $checkspec .= $value['i'].'/';
        }
        $checkspec = substr($checkspec,0,-1);
        $price = db('ware_spec')->where(['ware_id'=>$_GET['ware_id'],'spec'=>$checkspec])->find();

        $req = Request::instance();
        if($req->isAjax())
        {
            return json($price);
        }
    }

    //加入购物车 1成功 2相同不加 3没有选择完整规格 
    public function infoShopCart()
    {
        if(!empty(session('user')))
        {
            $post = input('post.','');

            $check = db('shopcart')->where(['user_id'=>session('user')[0]['id'],'spec_id'=>$post['spec_id']])->count();
            if($check)
            {
                return '2';
            }else
            {
                $post['user_id'] = session('user')[0]['id'];
                $updata = db('shopcart')->insert($post);
                return '1';
            }
        }else
        {
            return '3';
        }
    }
}
?>