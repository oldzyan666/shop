<?php
    namespace app\index\controller;

    use think\Controller;
    use think\captcha\Captcha;
    use think\Cache;
    use think\Session;

    class Check extends Controller
    {
        //注册检查是否有误
        public function checkUser()
        {
            $post = input('post.','','htmlspecialchars'); 
            $error = [];
            foreach($post as $key=>$value)
            {
                if($value==''&&$key!='up')
                {
                    $error[] = $key;
                }
            }
            if(!empty($error))
            {
                return json(['errornub'=>1,'error'=>$error]);
                die;
            }
            $is_user = db('user')->where('user',$post['user'])->count();
            $is_email = db('user')->where('email',$post['email'])->count();
            $is_pho = db('user')->where('pho',$post['pho'])->count();
            if($is_user)
            {
                $error['user'] = '用户名已存在';
            }
            if($is_email)
            {
                $error['email'] = '邮箱已存在';
            }
            if($is_pho)
            {
                $error['pho'] = '号码已存在';
            }
            if(!empty($error))
            {
                return json(['errornub'=>2,'error'=>$error]);
                die;
            }
            $checkBox = validate('Regcheck');
            if(session('random')==$post['phoVali']&&session('pho')==$post['pho'])
            {
                if($post['pwd']==$post['agPwd'])
                {
                    if($checkBox->check($post))
                    {
                        session('pho','');
                        unset($post['up'],$post['agPwd'],$post['phoVali']);
                        $post['pwd'] = md5($post['pwd']);
                        switch(cache('user')['logintype'])
                        {
                            case "wechat":
                                $post['wechat_id'] = cache('user')['openid'];
                                break;
                            case "weibo":
                                $post['weibo_id'] = cache('user')['id'];
                                break;
                            default:
                                break;
                        }
                        $query = db('user')->insert($post);
                        if($query)
                        {
                            $user = db('user')->where('pho',$post['pho'])->select();
                            Cache::rm('user');
                            session('user',$user);
                            return '';
                        }else
                        {
                            $this->success('注册失败',url('user/login'));
                        }
                    }else
                    {
                        $error['getError'] = $checkBox->getError();
                    }
                }else
                {
                    $error['pwd'] = '密码不一致';
                }
            }else
            {
                $error['phoVali'] = '验证码错误';
            }
            
            if(!empty($error))
            {
                return json(['errornub'=>2,'error'=>$error]);
            }
        }

        //登录
        public function login()
        {

            $pid = input('get.id','','htmlspecialchars');

            $key = input('get.key','','htmlspecialchars');
//            print_r($pid);die;

            $this->assign('pid',$pid);
            $this->assign('key',$key);

            if ($pid!=''&&md5($pid.'cascfawfecaesfvesf')==$key){
                 session('pid',$pid);
                 session('key',$key);
            }
            else if($pid!=''&&md5($pid.'cascfawfecaesfvesf')!=$key){
                $this->error('非法url',url('index/index'));
                die;
            }


            $post = input('post.','','htmlspecialchars');

            $error = [];
            foreach($post as $key=>$value)
            {
                if($value==''&&$key!='up')
                {
                    $error[] = $key;
                }
            }
            if(!empty($error))
            {
                return json(['errornub'=>1,'error'=>$error]);
                die;
            }
            $captcha = $post['captcha'];
            if(!captcha_check($captcha)){
                $error['captcha']='验证码错误';
                return json(['errornub'=>2,'error' => $error]);
                die;
            };
            unset($post['captcha']);
            $post['pwd'] = md5($post['pwd']);
            $check = db('user')->where($post)->find();
            $GLOBALS['check'] = $check ;
//            print_r($check);die;


            if($check)
            {

                //　　二维码　　判断　ｐｉｄ　是否为０　
//                echo session('pid');die;
                $user = db('user')->where($post)->select();
//                print_r(session('pid'));die;
                if ($check['pid'] == 0 && session('pid')!=null){

                    db('user')->where('id',$check['id'])->update(['pid'=>session('pid')]);

                    Session::delete('pid');
                    Session::delete('key');
                }
//                print_r($user);die;
                session('user',$user);
                session('id',$check['id']);

                            //    Db::transaction(function(){     //事物
                // 积分
                $member = model('member');
//                $member->getReferee($info['id']);
                $check = $GLOBALS['check'];     // 超级变量

                // 一级推荐人
                $referee1 = $member->getReferee($check['id']);

//                dump($referee1);die;
                // 二级推荐人
                $referee2 = $member->getReferee($referee1['id']);
                // 三级推荐人
//                if (!empty($referee2)){
                $referee3 = $member->getReferee($referee2['id']);
//                }

                $point1 = db('config')->where('name','一级推荐奖励')->find();
                $point2 = db('config')->where('name','二级推荐奖励')->find();
                $point3 = db('config')->where('name','三级推荐奖励')->find();

                db('accessLog')->insert(['msg'=>'成功推荐'.$check['user'].';获得一级推荐奖励'.$point1['value'],'type'=>1,'addtime'=>time()]);
                db('user')->where('id',$referee1['id'])->setInc('point',$point1['value']);

                if (!empty($referee2)) {
                    db('accessLog')->insert(['msg' => '成功推荐' . $check['user'] . ';获得二级推荐奖励' . $point2['value'],'type' => 1, 'addtime' => time()]);
                db('user')->where('id', $referee2['id'])->setInc('point', $point2['value']);
//                    isset 避免报错
                    if (isset($referee3)&&!empty($referee3)) {
                        db('accessLog')->insert(['msg' => '成功推荐' . $check['user'] . ';获得三级推荐奖励' . $point3['value'],'type' => 1, 'addtime' => time()]);
                db('user')->where('id', $referee3['id'])->setInc('point', $point3['value']);
                    }
                }

//                });
                return '';

            }else
            {
                $error['pwd'] = '账号或密码错误';
            }
            return json(['errornub'=>2,'error' => $error]);
        }

        //绑定微博微信
        public function binding()
        {
            $post = input('post.','','htmlspecialchars');
            $error = [];
            foreach($post as $key=>$value)
            {
                if($value==''&&$key!='up')
                {
                    $error[] = $key;
                }
            }
            if(!empty($error))
            {
                return json(['errornub'=>1,'error'=>$error]);
                die;
            }
            if(!captcha_check($post['captcha']))
            {
                $error['captcha']='验证码错误';
                return json(['errornub'=>2,'error' => $error]);
                die;
            }
            $post['pwd'] = md5($post['pwd']);
            $check = db('user')->where('user',$post['user'])->where('pwd',$post['pwd'])->count();
            
            if($check)
            {
                if(cache('user')['logintype']=='wechat')
                {
                    $up = db('user')->where('user',$post['user'])->where('pwd',$post['pwd'])->setField('wechat_id',cache('user')['openid']);
                }elseif(cache('user')['logintype']=='weibo')
                {
                    $up = db('user')->where('user',$post['user'])->where('pwd',$post['pwd'])->setField('weibo_id',cache('user')['id']);
                }
                if($up)
                {
                    $find_user = db('user')->where('user',$post['user'])->where('pwd',$post['pwd'])->select();
                    Cache::rm('user'); 
                    session('user',$find_user);
                    return '';
                }
            }else
            {
                $error['pwd'] = '账号或密码错误';
            }
            return json(['errornub'=>2,'error' => $error]);
        }

        //发送手机验证码
        public function phoVali()
        {
            $pho = input('post.pho','');
            $is_pho = db('user')->where('pho',$pho)->count();
            if($is_pho)
            {
                return 1;
                die;
            }
            if(empty($pho))
            {
                return 2;
                die;
            }
            $random = mt_rand(1000,9999);
            session('random',$random);
            session('pho',$pho);
            $url = 'http://v.juhe.cn/sms/send?mobile='.$pho.'&tpl_id=110957&tpl_value='.urlencode('#code#='.$random).'&key=71cf36d9e8733621c79c9f9bd2ef9b6c';
            curl($url,[]);
            return 1;
        }

        //验证码
        public function captcha()
        {
            $config = [
                'fontSize' => 40,
                'length' => 4,
                'useCurve' => false
            ];
            $captcha = new Captcha($config);
            return $captcha->entry();
        }

        //微博登录
        public function weibo()
        {            
            $code = input('get.code');
            $data = [
                'id' => 2891905678,
                'secret' => '93a78135249f8f8010bbd203ec903722',
                'url' => 'http://www.project.org/index.php/index/check/weibo',
                'code' => $code
            ]; 
            $url = 'https://api.weibo.com/oauth2/access_token?client_id='.$data['id'].'&client_secret='.$data['secret'].'&grant_type=authorization_code&redirect_uri='.$data['url'].'&code='.$data['code'];            
            $getUid = curl($url,[]);
            $phpData = json_decode($getUid,true);
            $url = "https://api.weibo.com/2/users/show.json?access_token=".$phpData['access_token']."&uid=".$phpData['uid'];            
            $getUser = curl($url);   
            $phpData = json_decode($getUser,true);
            $find_user = db('user')->where('weibo_id',$phpData['id'])->select();
            if($find_user)
            {
                session('user',$find_user);
                $this->success('登录成功',url('index/index'));
            }else
            {
                $phpData['logintype'] = 'weibo';
                cache('user',$phpData,600);
                $this->redirect(url('log/reg'),302);
            }
        }

        //微信登录
        public function wechat()
        {
            $code = input('get.code');
            $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx02c6c3c8c1a73dac&secret=b7af660aa34a7d85e0dbfda8803d3314&code=".$code."&grant_type=authorization_code";
            $json = curl($url);
            $phpData = json_decode($json,true);
            $url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$phpData['access_token']."&openid=".$phpData['openid']."&lang=zh_CN";
            $userjson = curl($url);
            $user = json_decode($userjson,true);
            $find_user = db('user')->where('wechat_id',$user['openid'])->select();
            if($find_user)
            {
                session('user',$find_user);
                $this->success('登录成功',url('index/index'));
            }else
            {
                $user['logintype'] = 'wechat';
                cache('user',$user,600);
                $this->redirect(url('log/reg'),302);
            }
        }

        //取消绑定登录
        public function cancelbin()
        {
            Cache::rm('user');
            $this->redirect(url('log/login'),302);
        }

        //提交订单
        public function orderForm()
        {
            $updata = [];
            $id = '';
            $i = 0;
            $cart_id = '';
            $post = input('post.','','htmlspecialchars');
            //总价钱
            session('price',$post['price']);
            //生成随机订单号
            session('form_id',form_id());
            $price_id = db('priceform')->insertGetId(['user_id'=>session('user')[0]['id'],'form_id'=>session('form_id'),'price' => $post['price'],'time'=>time()]);
            //订单数据库主键id
            session('price_id',$price_id);

            //购物车id
            foreach($post['cart_id'] as $value)
            {
                $cart_id .= $value.',';
            }

            foreach($post['spec_id'] as $key => $value)
            {
                $updata[$i]['addressid'] = $post['addressid'];
                $updata[$i]['user_id'] = session('user')[0]['id'];
                $updata[$i]['ware_num'] = $post['ware_num'][$value];
                $updata[$i]['spec_id'] = $value;
                $updata[$i]['priceid'] = $price_id;
                $updata[$i]['state'] = 1;   
                $i++;  
            }
            $up = db('orderform')->insertAll($updata); 
            if($up == $i)
            {
                //当提交订单时就删除购物车所选的商品
                $delcart = db('shopcart')->whereIn('id',$cart_id)->delete();
                //当提交订单时减少相应库存
//                $cutstock = db('');
//                Db::startTrans();
//                try{
//                    Db::table('think_user')->find(1);
//                    Db::table('think_user')->delete(1);
//                    // 提交事务
//                    Db::commit();
//                } catch (\Exception $e) {
//                    // 回滚事务
//                    Db::rollback();
//                }
                $this->redirect(url('alipay/pay').'?WIDsubject='.$post['ware_name']);
            }
        }

        //退出
        public function userExit()
        {
            Session::delete('user');
            $this->success('退出成功',url('log/login')); 
        }

    }
?>