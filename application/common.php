<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function curl($curl,$data=null){
    $url =curl_init();
    curl_setopt($url,CURLOPT_URL,$curl);
    // curl_setopt($url,CURLOPT_SSL_VERIFYPEER,false);
    // curl_setopt($url,CURLOPT_SSL_VERIFYHOST,false);

    if(!is_null($data)){
        curl_setopt($url,CURLOPT_POST,true);
        curl_setopt($url,CURLOPT_POSTFIELDS,$data);
    }

    curl_setopt($url,CURLOPT_RETURNTRANSFER,1);

    $content=curl_exec($url);

    curl_close($url);

    return $content;
}

function spec($post)
{
    foreach($post['spec'] as $key=>$value)
    {
        //把子类属性中的空白清楚,并重新排序
        $post['spec'][$key] = array_filter($post['spec'][$key]);
        $post['spec'][$key] = array_values($post['spec'][$key]);
        //储存spec里面的元素
        $spec = $post['spec'][$key];
        //清空spec里面的元素
        $post['spec'][$key] = '';
        //把spec里面的元素进行拼接再存入数组里
        for($i=0;$i<count($spec);$i++)
        {
            $post['spec'][$key] .= $spec[$i].'/';
        }
        //把最后一个 / 去除
        $post['spec'][$key] = substr($post['spec'][$key],0,-1);
    }
    //使用insertAll上传重新储存进一个数组,如果sub为空,该sub中的属性不会储存
    for($i=0;$i<count($post['sub']);$i++){
        if(!empty($post['sub'][$i])){
        $spec_list[] = ['mould'=>$post['mould'],'type_id'=>$post['type_id'],"sub"=>$post['sub'][$i],'spec'=>$post['spec'][$i]];
        }
    }
    return $spec_list;
}

//商品的属性和小类进行重组
function spec_reform($cartlist)
{
    foreach($cartlist as $key=>$value)
    {
        $cartlist[$key]['spec'] = explode('/',$value['spec']);
        //把数组里面的sub存在另一个数组里面去,用他们的spec_id(属性id)区分开
        foreach($value as $k=>$val)
        {   
            if($k == 'sub')
            {
                $sub[$value['spec_id']][] = $value['sub'];
                $cartlist[$key]['sub'] = '';
            }
        }
        //删除相同的数组
        foreach($cartlist as $i=>$v)
        {
            if($i != $key)
            {
                if($v['id']==$value['id'])
                {
                    unset($cartlist[$key]);
                }
            }
        }
    }
    //匹配谁跟谁
    foreach($sub as $key=>$value)
    {
        foreach($cartlist as $k=>$val)
        {
            if($val['spec_id']==$key)
            {
                $cartlist[$k]['sub'] = $sub[$key];
            }
        }
    }
    return $cartlist;
}

//生成随机的一个订单号
function form_id()
{
    $error = 1;
    $check_id = date("Y",time()).rand(10000000000000,100000000000000);
    $data = db('priceform')->field('priceform.id,priceform.form_id')->select();

    for($i=0;$i<count($data);$i++)
    {
        if($data[$i]['form_id'] == $check_id)
        {
            $error = 2;
        }
    }

    if($error == 2)
    {
        return form_id();
    }else
    {
        return $check_id;
    }
}

//更新订单信息
function updateForm($state,$back_id)
{
    $up = db('orderform')->where('priceid',session('price_id'))->update(['state'=>$state]);
    $upBackId = db('priceform')->where('id',session('price_id'))->update(['back_id'=>$back_id,'state'=>$state]);
    if(!empty($up) && !empty($upBackId))
    {
        return 1;
    }
}