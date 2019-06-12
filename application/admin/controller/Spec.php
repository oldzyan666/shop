<?php
    namespace app\admin\controller;
    
    use think\Request;
    use think\Image;
    use think\Controller;

    class Spec extends controller
    {
        //添加大类页面 比如手机,电脑
        public function info_type()
        {
            return view();
        }

        //上传大类操作
        public function up_type()
        {
            $data = input('post.','','trim,htmlspecialchars');
            $query = db('wareType')->insert($data); 
            if($query)
            {
                $this->redirect(url('spec/typeList'));
            }else
            {
                $this->error('添加失败');
            }
        }

        //删除大类型操作
        public function del_type()
        {
            $data = input('get.id','');
            db('ware_type')->delete($data);
        }

        //显示大类列表页面
        public function typelist()
        {
            $getText = input('get.seach','','htmlspecialchars');
            $db = db('ware_type');
            $config['page'] = input('get.page','');
            if($getText != '')
            {   
                $config['query'] = ['seach'=>$getText];
                $db->whereLike('user',"%{$getText}%");
            }

            $list = $db->order(['id'=>'ord'])->paginate(4,false,$config);
            $data = $list->toArray();
            if(empty($data['data'])&&$config['page']>1)
            {
                $config['page'] -= 1;
                $list = $db->order(['id'=>'ord'])->paginate(4,false,$config);
            }
            $page = $list->render();
            //选择属于用户的mould(还没加where指定哪个用户,后期加)
            // $mould = db('spec_list')->field('mould,type_id')->group('mould')->select();
            // foreach($mould as $key=>$value)
            // {
            //     $mould_list[$value['type_id']][] = $value['mould'];
            // }
            $now = ['class="am-pagination tpl-pagination"' , 'class="am-disabled"' , 'class="am-active"'];
            $old = ['class="pagination"' , 'class="disabled"' , 'class="active"'];
            $seach = str_replace($old,$now,$page);

            $request = Request::instance();
            if($request->isAjax())
            {
                return json(['list'=>$list, 'page'=>$seach]);
            }
            return view();
        }

        //添加子类模板页面 比如颜色,大小等等和属性
        public function info_spec()
        {
            $type = db('ware_type')->select();
            $this->assign('type',$type);
            return view();
        }

        //上传子类模板操作
        public function up_spec()
        {
            $post = input('post.','','htmlspecialchars');
            $spec_list = spec($post);
            //去除sub中的空元素
            $post['sub'] = array_filter($post['sub']);
            $check = db('spec_list')->insertAll($spec_list);
            //判断是否都上传成功,如果失败删除部分上传成功的
            if($check==count($post['sub'])){
                $this->success('子类添加成功',url('spec/spec_list'));
            }else
            {
                db('spec_list')->where('mould',$post['mould'])->delete();
                $this->error('子类添加失败');
            }
        }

        //上传修改模板
        public function up_revspec()
        {
            $post = input('post.','','htmlspecialchars');
            $spec_list = spec($post);
            db('spec_list')->where('mould',$post['old_mould'])->delete();
            $check = db('spec_list')->insertAll($spec_list);
            //判断是否都上传成功,如果失败删除部分上传成功的
            if($check==count($post['sub'])){
                $this->success('子类修改成功',url('spec/spec_list'));
            }else
            {
                $this->error('子类添加失败');
            }
        }

        //子类模板列表
        public function spec_list()
        {
            $getText = input('get.seach','','htmlspecialchars');
            $db = db('spec_list');
            $config['page'] = input('get.page','');
            if($getText != '')
            {   
                $config['query'] = ['seach'=>$getText]; 
                $db->whereLike('user',"%{$getText}%");
            }

            $list = $db->field('a.*,b.type')->alias('a')->join('ware_type b','a.type_id = b.id')->order(['id'=>'ord'])->group('mould')->paginate(4,false,$config);
            $data = $list->toArray();
            if(empty($data['data'])&&$config['page']>1)
            {
                $config['page'] -= 1;
                $list = $db->field('a.*,b.type')->alias('a')->join('ware_type b','a.type_id = b.id')->order(['id'=>'ord'])->group('mould')->paginate(4,false,$config);
            }
            $page = $list->render();
            //选择属于用户的mould(还没加where指定哪个用户,后期)
            $spec = $db->select();
            foreach($spec as $key=>$value)
            {
                $spec_list[$value['mould']][] = $value['sub'];
            }
            $now = ['class="am-pagination tpl-pagination"' , 'class="am-disabled"' , 'class="am-active"'];
            $old = ['class="pagination"' , 'class="disabled"' , 'class="active"'];
            $seach = str_replace($old,$now,$page);

            $request = Request::instance();
            if($request->isAjax())
            {
                return json(['list'=>$list, 'page'=>$seach, 'spec'=>$spec_list]);
            }
            return view();
        }

        //修改子类模板
        public function rev_spec()
        {
            $type = db('ware_type')->select();
            $this->assign('type',$type);
            $data =db('spec_list')->where('mould',$_GET['mould'])->select();
            foreach($data as $key=>$value)
            {
                $data[$key]['spec'] = explode('/',$value['spec']);
            }
            // echo '<pre>';
            // print_r($data);die;
            $this->assign('data',$data);
            return view();
        }

        //删除子类模板
        public function del_spec()
        {
            $mould = input('get.id','');
            db('spec_list')->where('mould',$mould)->delete();
            return '删除成功';
        }

        //添加商品属性价格列表页面
        public function ware_spec()
        {
            $mould_list = db('spec_list')->group('mould')->select();
            //用户选择的模板
            $mould = input('post.mould','');
            //后期加where userid
            $spec_list = db('spec_list')->where('mould',$mould)->select();
            //把spec里面的字段变为数组
            foreach($spec_list as $key=>$value)
            {
                $spec_list[$key]['spec'] = '';
                $spec_list[$key]['spec'] = explode('/',$value['spec']);
                $spec[$spec_list[$key]['sub']] = $spec_list[$key]['spec'];
            }
        
            $this->assign('mould',$mould_list);
            $request = Request::instance();
            if($request->isAjax())
            {
                return json(['spec_list'=>$spec_list,'sub'=>$spec]);
            }
            return view();
        }

        //上传商品属性价格操作
        public function up_price()
        {
            $a = 0;
            $post = input('post.','','trim,htmlspecialchars');
            $ware_id = $post['id'];
            $mould = $post['mould'];
            unset($post['id'],$post['title'],$post['mould']);
            foreach($post as $key=>$value)
            {
                $spec_list[$a]['spec'] = '';
                for($i = 0;$i<count($value)-2;$i++)
                {
                    $spec_list[$a]['spec'] .= $value[$i].'/';
                }
                $spec_list[$a]['spec'] = substr($spec_list[$a]['spec'],0,-1);
                //现在暂无登录所以没有用户ID
                // $spec_list[$a]['user_id'] = $user_id;
                $spec_list[$a]['mould_name'] = $mould;
                $spec_list[$a]['ware_id'] = $ware_id;
                $spec_list[$a]['price'] = $value['price'];
                $spec_list[$a]['stock'] = $value['stock'];
                $a++;
            }
            $check = db('ware_spec')->insertAll($spec_list);
            if($check==count($post))
            {
                $this->success('上传成功',url('tablefont/tablefont'));
            }else
            {
                $this->error('上传失败');
            }
        }

    }