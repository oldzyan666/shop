<?php

//　支付宝　支付　查询

namespace app\index\controller;

use think\Controller;
use think\Session;

class Alipay extends Controller{

    //　　支付方法　　order 页面　代码相当于　extend 下的　alipay  里面的　index.php 页面
    public function order(){

        return view();
    }

    //　　支付方法　　alipay  里面的　index.php 页面　跳转到　pagepay 里面的 pagepay.php
    public function pay(){
    //  模仿extend 里面的　pagepay/pagepay.php　文件
        $post = input('post','','trim');

        include EXTEND_PATH.'alipay/config.php';
        include EXTEND_PATH.'alipay/pagepay/service/AlipayTradeService.php';
        include EXTEND_PATH.'alipay/pagepay/buildermodel/AlipayTradePagePayContentBuilder.php';

        //　　接收四个参数
        
        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = session('form_id');

        //订单名称，必填
        $subject = $_GET['WIDsubject'].'等等商品';

        //付款金额，必填
        $total_amount = session('price');

        //商品描述，可空
        $body = '';

        // 实例化　类

        //构造参数  类是全局的　　所以要加上　\  否则会在当前目录下去找这个类
        $payRequestBuilder = new \AlipayTradePagePayContentBuilder();
        $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setOutTradeNo($out_trade_no);

        $aop = new \AlipayTradeService($config);

        /**
         * pagePay 电脑网站支付请求
         * @param $builder 业务参数，使用buildmodel中的对象生成。
         * @param $return_url 同步跳转地址，公网可以访问
         * @param $notify_url 异步通知地址，公网可以访问
         * @return $response 支付宝返回的信息
         */
        $response = $aop->pagePay($payRequestBuilder,$config['return_url'],$config['notify_url']);
        // http://www.tp.com/index.php/index/text/sure?charset=UTF-8
        // &out_trade_no=12
        // &method=alipay.trade.page.pay.return
        // &total_amount=0.01
        // &sign=bJ4m8fNAmLEGeXQuPOVvzQrK5otI9cC6JKxSovazZtxc236JkAnMiGhHUsgCNuq6QGxfeokhpebemLFNeu0rdGGtV6jf2WPCzkzJlHmT2wmThbL56Op8K0ytY%2FuqzMhDKvm5iY7kt20i8ly%2FFYUl5jeAnRaNkR%2BgGi2lTwa7fFmlyKwOiYxKpAxfWwckhvdsvA36s03GLe%2FNDU64dNsJnMUAdom0g2RUitWEqOks7nDfdWExA7SmIZFx0d%2Bz6dTvZWQEBQqeV4nAHbXWU%2BltIT8POBG2sbrSCFpEuG6mFO17Bu%2BEcAna5hDnz%2FPKLNFW0%2F19vxiZ5N7qh4zh7k%2BapA%3D%3D
        // &trade_no=2018121722001416190500233460
        // &auth_app_id=2016091900550001
        // &version=1.0
        // &app_id=2016091900550001
        // &sign_type=RSA2
        // &seller_id=2088102176377828
        // &timestamp=2018-12-17+16%3A07%3A08
    }

    //　获取　用户信息
    public function sure()
    {
        $get = input('get.');
        dump($get);
    }

    //    查询订单　 剩下的　退款　退款查询　　同样操作
    public function query(){

        include EXTEND_PATH. 'alipay/config.php';
        include EXTEND_PATH. 'alipay/pagepay/service/AlipayTradeService.php';
        include EXTEND_PATH. 'alipay/pagepay/buildermodel/AlipayTradeQueryContentBuilder.php';

        //商户订单号，商户网站订单系统中唯一订单号
        $out_trade_no = session('form_id');

        //支付宝交易号
        $trade_no = trim($_GET['trade_no']);
        //请二选一设置
        //构造参数
        $RequestBuilder = new \AlipayTradeQueryContentBuilder();
        $RequestBuilder->setOutTradeNo($out_trade_no);
        $RequestBuilder->setTradeNo($trade_no);

        $aop = new \AlipayTradeService($config);

        /**
         * alipay.trade.query (统一收单线下交易查询)
         * @param $builder 业务参数，使用buildmodel中的对象生成。
         * @return $response 支付宝返回的信息
         */
        $response = $aop->Query($RequestBuilder);

        //判断是否付款成功及价钱匹配
        if($response->trade_status == 'TRADE_SUCCESS'&&$response->total_amount == session('price'))
        {
            $check = updateForm(2,$response->trade_no);
            //判断是否把信息更新到服务器
            if($check == 1)
            {
                Session::delete('price_id','form_id','price');
                $this->success('购买成功',url('user/my_account'));
            }else
            {
                updateForm(1,0);
                curl('www.project.org'.url('alipay/pagepay'),['WIDout_trade_no'=>$response->out_trade_no,'WIDTRrefund_amount'=>session('price'),'WIDTRtrade_no'=>$response->trade_no]);
                Session::delete('price_id','form_id','price');
                $this->error('服务器繁忙,请重新在试',url('user/my_account'));
            }
        }else
        {
            updateForm(1,0);
            //写死域名
            curl('www.project.org'.url('alipay/pagepay'),['WIDout_trade_no'=>$response->out_trade_no,'WIDTRrefund_amount'=>$response->total_amount,'WIDTRtrade_no'=>$response->trade_no]);
            Session::delete('price_id','form_id','price');
            $this->error('服务器繁忙,请重新在试',url('user/my_account'));
        }
    }

    public function pagepay()
    {
        include EXTEND_PATH.'alipay/config.php';
        include EXTEND_PATH.'alipay/pagepay/service/AlipayTradeService.php';
        include EXTEND_PATH.'alipay/pagepay/buildermodel/AlipayTradeRefundContentBuilder.php';

        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = trim($_POST['WIDout_trade_no']);

        //支付宝交易号
        $trade_no = trim($_POST['WIDTRtrade_no']);

        //需要退款的金额，该金额不能大于订单金额，必填
        $refund_amount = trim($_POST['WIDTRrefund_amount']);

        //退款的原因说明
        $refund_reason = '服务器繁忙';

        // //标识一次退款请求，同一笔交易多次退款需要保证唯一，如需部分退款，则此参数必传
        // $out_request_no = trim($_GET['WIDTRout_request_no']);

        //构造参数
        $RequestBuilder=new \AlipayTradeRefundContentBuilder();
        $RequestBuilder->setOutTradeNo($out_trade_no);
        $RequestBuilder->setTradeNo($trade_no);
        $RequestBuilder->setRefundAmount($refund_amount);
        // $RequestBuilder->setOutRequestNo($out_request_no);
        $RequestBuilder->setRefundReason($refund_reason);

        $aop = new \AlipayTradeService($config);

        /**
         * alipay.trade.refund (统一收单交易退款接口)
         * @param $builder 业务参数，使用buildmodel中的对象生成。
         * @return $response 支付宝返回的信息
        */
        $response = $aop->Refund($RequestBuilder);
    }
}



?>