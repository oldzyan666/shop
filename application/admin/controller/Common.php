<?php


namespace app\admin\controller;

use think\Controller;
use think\Image;
use think\Request;

class Common extends Controller{

    public function __construct(Request $request = null){
        parent::__construct($request);
        if(is_null(session('user'))){
            $this->error('请先登录','login/login');
        }

        if(!$this->checkAuth()){
            $this->error('没有权限');
        }

    }

    public function checkAuth(){

        $hasPermission = 0; //　0　代表没有权限
        $request = Request::instance();

        $module = $request->module();
        $controller = $request->controller();
        $action = $request->action();

//　　根据用户名查询用户信息
        $user_info = db('admin')->where('user',session('user'))->find();
//      dump($user_info);die;

//      根据用户信息查询用户角色

        $role_ids = db('admin_role')->where('admin_id', $user_info['id'])->select();
//      dump($role_ids);die;

        $roleList = '';
        foreach ($role_ids as $k=>$v){
            if ($v['role_id'] == 1){
                $hasPermission = 1;
            }
            $roleList .= $v['role_id'].',';
        }



        if ( $hasPermission == 1){

            $menus = db('permission')->where('type',0)->order('ords')->select();
//            dump($menus);die;
        }
        elseif ($hasPermission == 0){

        $roleList = rtrim($roleList,',');
//        dump($roleList);die;

//        根据角色id查询　角色对应的权限id
         $permission_id = db('role_permission')->whereIn('role_id',$roleList)->select();
//        dump($permission_id);die; buildsql

        $permissionList = [];
        foreach ($permission_id as $k=>$v){
            $permissionList[] = $v['permission_id'];
        }
       array_unique($permissionList);  //　　去重
        $permissionList = implode(',',$permissionList); //  分割成字符串
//        dump($permissionList);die;

        //　查询用户的所有权限
        $permissions  = db('permission')->whereIn('id',$permissionList)->select();
//        dump($permissions);
            $menus = db('permission')->where('type',0)->whereIn('id',$permissionList)->order('ords')->select();


//     获取地址栏的模块　控制器　方法


//        echo strtolower($module.'/'.$controller.'/'.$action);die;


        foreach ($permissions as $k=>$v){
            if(strtolower($v['name']==strtolower($module.'/'.$controller.'/'.$action))){
                $hasPermission = 1;
                break;
            }
        }

        }

        $this->assign('menus',$menus);
        $this->assign('current_url',strtolower($module.'/'.$controller.'/'.$action));
//        dump($menus);die;
    return $hasPermission;


    }


    }