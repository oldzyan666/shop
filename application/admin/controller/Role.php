<?php
namespace app\admin\controller;
use think\Request;

class Role extends Common
{
    public function index()
    {
        $search = input('get.search', '' , 'htmlspecialchars');
        $config = [];
        $page = input('get.page','1');
        $roles = db('role');
        if ($search!=''){
            $config = ['query'=>['search'=>$search]];
            $roles->whereLike('role_name',"%{$search}%");
        }

        $role = $roles->paginate(3,false,$config);
        $data = $role->toArray();
//        dump($data);die;
        if (empty($data['data'])) {
            $config['page'] = $page - 1 > 1 ? $page - 1 : 1;
            $role =$roles->paginate(3 , false , $config);
        }
        $page = $role->render();
        $search = ['pagination' , 'disabled' , 'active'];
        $replace = ['am-pagination tpl-pagination' , 'am-disabled' , 'am-active'];
        $page = str_replace($search , $replace , $page);


        $request = Request::instance();
        if ($request->isAjax()){

            return json(['role'=>$role,'page'=>$page]);

        }else{

            $this->assign('role',$role);
            $this->assign('page', $page);
        }

        return view();
    }


    public function insert(){
        return view();
    }

    public function store(){

        $post = input('post.','','htmlspecialchars');
        $res = db('role')->insert($post);

        if ($res){
            $this->success('添加成功',url('index'));
        }
        else{
            $this->error('添加失败');
        }
    }

    public function delete () {
        $id = input('get.id');
        $affectedRow = db('role')->where('id' , $id)->delete();
        if ($affectedRow) {
            $this->success('删除成功');
        }else {
            $this->error('删除失败');
        }
    }

    public function edit(){
        $id = input('get.id','','intval');
        $res = db('role')->find($id);
        $this->assign('res',$res);
        return view();
    }

    public function save () {

        $post = input('post.');
//        print_r($post);die;

//        db('role')->update(['role_name'=>$post]);
        db('role')->update($post);

        $this->success('更新成功' , url('index'));

    }

    public function editPermission(){

        //　　查询所有权限
        $permission = db('permission')->order('ords')->select();
//        print_r($permission);die;
        $tmp_permission = $permission;
        foreach ($tmp_permission as $key=>$value){
            foreach ($tmp_permission as $key2=>$value2){

                if ($value2['pid'] == $value['id']){

                    $permission[$key]['son'][] = $value2;
                    unset($permission[$key2]);
                }

            }
        }
//        dump($permission);die;

//        查询该角色有那些权限
        $id = input('get.id');
        $hasPermission = db('rolePermission')->where('role_id',$id)->select();
//        dump($hasPermission);die;
        $hasPermission2 = [];
        foreach ($hasPermission as $k=>$v){
            $hasPermission2[] = $v['permission_id'];
        }
//        dump($hasPermission2);die;

        $this->assign('permission',$permission);
        $this->assign('hasPermission2',$hasPermission2);
        return view();

    }

    public function savePermission(){

        $post = input('post.');

        db('role_permission')->where('role_id',$post['role_id'])->delete();

//        dump($post['permission_id']);die;
        $insertData = [];
        foreach ($post['permission_id'] as $k=>$v){

            $insertData[$k]['role_id'] = $post['role_id'];
            $insertData[$k]['permission_id'] = $v;

        }

//        dump($insertData);die;
        db('role_permission')->insertAll($insertData);
        $this->success('更新成功',url('index'));
    }



}