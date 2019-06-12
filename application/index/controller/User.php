<?php
namespace app\index\controller;

use think\Request;

header("Content-Type:text/html;charset=utf8");
class User extends Common
{
    //my bag(我的书包?不要啥意思)
    public function compare()
    {
        return view();
    }
    //结账
    public function checkout()
    {

//        print_r($_POST);die;
        $care = '';
        $price = 0;
        if(!empty($_POST['cart_id']))
        {
            //购物车页订单
            foreach($_POST['cart_id'] as $value)
            {
                $care .= $value.',';
            }
            //同购物车操作一样,where变成whereIn购物车商品的id
            $cartlist = db('shopcart')->field('a.*,b.tradename,b.img,c.spec,c.price,d.sub')->alias('a')
            ->join('commodity b','a.ware_id = b.id')
            ->join('ware_spec c','a.spec_id = c.id')
            ->join('spec_list d','c.mould_name = d.mould')
            ->whereIn('a.id',$care)->select();
        }else
        {
            $cartlist = db('ware_spec')->field('a.*,b.tradename,b.img,c.sub')->alias('a')
            ->join('commodity b','a.ware_id = b.id')
            ->join('spec_list c','a.mould_name = c.mould')
            ->where('a.id',$_POST['spec_id'])->select();
            //适配spec_reform方法
            foreach($cartlist as $key=>$value)
            {
                $cartlist[$key]['spec_id'] = $value['id'];
            }
            //适配前台代码
            $care = $_POST['quantity'];
            $_POST['quantity'] = '';
            $_POST['quantity'][$cartlist[0]['id']] = $care; 
        }
        $cartlist = spec_reform($cartlist);
        $cartlist = array_values($cartlist);
        if(!empty($_POST['cart_id']))
        {
            //购物车的总价钱
            for($i=0;$i<count($cartlist);$i++)
            {
                $price += $cartlist[$i]['price']*$_POST['quantity'][$cartlist[$i]['id']];
            }
        }else
        {
            //单间商品的价钱
            $price = $cartlist[0]['price'];
        }
        //商品数量
        $this->assign('ware_num',$_POST['quantity']);
        //商品信息
        $this->assign('warelist',$cartlist);
        //总价钱
        $this->assign('price',$price);
        $addressList = db('addresslist')->where('user_id',session('user')[0]['id'])->select();
        $this->assign('address',$addressList);
        return view();
    }
    //愿望清单
    public function wishlist()
    {
        return view();
    }
    //我的账号
     public function my_account()
     {
        
         // 我推荐的人
         $member = model('member');

         $partners = $member->getAllPartners(session('id'));

//        dump($partners);die; //　所有会员
        $this->assign('partners',$partners);

         $id = session('id');
         $key = 'cascfawfecaesfvesf';
         $key = md5($id.$key);
//        print_r($id);die;
         $text = url('index/log/login','',true,true).'?id='.$id.'&key='.$key;
         $this->assign('text',$text);

        $addressList = db('addresslist')->where('user_id',session('user')[0]['id'])->select();
        $this->assign('address',$addressList);
        $request = Request::instance();
        if($request->isAjax())
        {
            return json(['address'=>$addressList]);
        }
        return view();
     }

     //  二维码  分享链接

    public function qrcode(){
//        header("Content-Type: text/html;charset=utf-8");

        vendor('phpqrcode.phpqrcode');
        $qrcode = new \QRcode();

        $id = session('id');
        $key = 'cascfawfecaesfvesf';
        $key = md5($id.$key);
//        print_r($id);die;
        $text = url('index/log/login','',true,true).'?id='.$id.'&key='.$key;

        $qrcode->png($text,false,'L',4,2);

        die;
    }
    
    //购物车
    public function cart()
    {
        //页面的购物车商品在Cart的类
        //删除购物车商品
        // echo "<pre>";
        // print_r($cartlist);die;
        $req = Request::instance();
        if($req->isAjax())
        {
            $id = input('post.id','');
            $del = db('shopcart')->delete($id);
            print_r($del);die;
        }
        return view();
    }


}
