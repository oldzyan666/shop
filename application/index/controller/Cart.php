<?php
namespace app\index\controller;

use think\Controller;
use think\Request;

class Cart extends Controller
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $cartlist = db('shopcart')->field('a.*,b.tradename,b.img,c.spec,c.price,d.sub')->alias('a')
        ->join('commodity b','a.ware_id = b.id')
        ->join('ware_spec c','a.spec_id = c.id')
        ->join('spec_list d','c.mould_name = d.mould')
        ->where('a.user_id',session('user')[0]['id'])->select();
        if(!empty($cartlist))
        {
            $cartlist = spec_reform($cartlist);
            $this->assign('care',$cartlist);
        }
        $req = Request::instance();
        if($req->isAjax())
        {
            return json(['cartlist'=>$cartlist]);
        }
    }

    public function aa()
    {
        
    }
}   
?>