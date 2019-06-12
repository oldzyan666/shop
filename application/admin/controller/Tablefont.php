<?php
namespace app\admin\controller;


use think\Controller;
use think\Request;

class Tablefont extends Controller
{
//商品列表
    public function tablefont(){
        $select = db('commodity')->paginate(3);
        $page = $select->render();
        $search = ['pagination','disabled','active'];
        $replace = ['am-pagination tpl-pagination','am-disabled','am-active'];
        $page = str_replace($search,$replace,$page);
        $request = Request::instance();
        if($request->isAjax()){
            return json(['select'=>$select,'page'=>$page]);
        }else{
            $this->assign('page',$page);
            $this->assign('select',$select);
            
        }
        return view();
    }
    //商品添加视图
    public function tableadd(){

        return view();
 }
    //商品添加
    public function fontadd(){
          $post = input('post.');
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('img');
        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->move(ROOT_PATH . 'public/static/admin/images/' . DS . 'uploads');
            if($info){
                // 成功上传后 获取上传信息
                // 输出 jpg
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                $post['img'] = $info->getSaveName();

            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
          $data = [
               'tradename'=>$post['tradename'],
               'time'=>$post['time'],
                'img'=>$post['img'],
               'notes'=>$post['notes'],
          ];
//          $sele = db('commodity')->select();
//          print_r($sele);die;
          $inser = db('commodity')->insert($data);
          if($inser){
              $this->success('添加商品成功');
          }else{
              $this->error('添加商品失败');
          }
    }

    public function tabledelete(){
        $id = input('get.id');
//        dump($id);die;
        $delete = db('commodity')->where('id',$id)->delete();
        if($delete){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
    public function tabledate(){
       $id = input('get.id');
       $select = db('commodity')->where('id',$id)->find();
        $this->assign('select',$select);
//       dump($id);die;
       return view();
    }
    public function fontdate(){
        $post = input('post.');
        if(isset($post['img'])){
            unlink(ROOT_PATH.'public/static/admin/images/uploads'.$post['imgs']);
//            $update = db('commodity')->where('id',$post)
        }

        $file = request()->file('img');
        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->move(ROOT_PATH . 'public/static/admin/images/' . DS . 'uploads');
            if($info){
                // 成功上传后 获取上传信息
                // 输出 jpg
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                $post['imgs'] = $info->getSaveName();

            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
        $data = [
            'tradename'=>$post['tradename'],
            'time'=>$post['time'],
            'img'=>$post['imgs'],
            'notes'=>$post['notes'],
        ];
        $update = db('commodity')->where('id',$post['id'])->update($data);
        if($update){
            $this->success('修改成功');
        }else{
            $this->error('修改失败');
        }
    }
}