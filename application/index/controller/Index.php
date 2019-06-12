<?php
namespace app\index\controller;

class Index extends Cart
{
    //商品首页
    public function index()
    {
        $ware_list = '';
        $ware = db('ware_spec')->field('ware_id')->group('ware_id')->select();
        $minprice = db('ware_spec')->field("ware_id,min(price) as price")->group('ware_id')->select();
        $maxprice = db('ware_spec')->field("ware_id,max(price) as price")->group('ware_id')->select();
        foreach($minprice as $value)
        {
            $min[$value['ware_id']] = $value['price'];
        }
        foreach($maxprice as $value)
        {
            $max[$value['ware_id']] = $value['price'];
        }
        for($i=0;$i<count($ware);$i++)
        {
            $ware_list .= $ware[$i]['ware_id'].',';
        }
        $ware_list = substr($ware_list,0,-1);
        $data = db('commodity')->whereIn('id',$ware_list)->select();
        $this->assign('data',$data);
        $this->assign('minprice',$min);
        $this->assign('maxprice',$max);
        return view();
    }
}
