<?php
namespace app\admin\controller;

use think\Console;
use think\Controller;
use think\Image;
use think\Request;
use think\response\Json;

class Img extends Controller
{
    public function index()
    {
        $search = input('get.search');
        $page = input('get.page','1');
        $config = [];
        $img = db('img');
//        array_push($config,['page'=>$page]);
        $config['page'] = $page;
        if ($search != ''){
//            $config = ['query'=>['search'=>$search]];
//            array_push($config,['query'=>['search'=>$search]]);
            $config['query'] = ['search'=>$search];
            $img -> whereLike('url',"%{$search}%");
        }

        $ad_list = $img->paginate(3,false,$config);


        $data = $ad_list->toArray();
//        echo "<pre>";
//        print_r($data);die;
        if (empty($data['data'])) {
            $config['page'] = $config['page'] - 1 > 1 ? $config['page'] - 1 : 1;
            $ad_list =$img->paginate(3 , false , $config);
        }


        $page = $ad_list->render();
        $search = ['pagination' , 'disabled' , 'active'];
        $replace = ['am-pagination tpl-pagination' , 'am-disabled' , 'am-active'];
        $page = str_replace($search,$replace,$page);


        $request = Request::instance();
        if ($request->isAjax()){
            return Json(['ad_list'=>$ad_list,'page'=>$page]);
        }

        $this->assign('ad_list',$ad_list);
        $this->assign('page',$page);
        return view();
    }

    public function insert()
    {
        return view();
    }

    public function store(){

        // $post = input('post.');
        $post = input('post.','','trim,htmlspecialchars');

//        print_r($post);die;
        $file = request()->file('src');
        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){

                $post['src'] = $info->getSaveName();

                echo $info->getSaveName();

                $image = Image::open(ROOT_PATH . 'public' . DS . 'uploads'.'/'.$info->getSaveName());
                $dir = dirname(ROOT_PATH . 'public' . DS . 'thumbs/'.$info->getSaveName());  // 获取目录名称
                !file_exists($dir) && mkdir($dir,0777,true);  //二元运算符  不存在就创建
                $image->thumb(150, 150)->save(ROOT_PATH . 'public' . DS . 'thumbs/'.$info->getSaveName());
//               dump($res);die;

//                $image = \think\Image::open('./image.png');
//                // 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.png
//                $image->thumb(150, 150)->save('./thumb.png');

            }
            else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }

//        dump($post);die;

        //插入数据库
        $insertId = db('img')->insertGetId($post);
        if($insertId){
            $this->success('插入成功',url('index'));
        }
        else{
            $this->error('插入失败');
        }

    }

    public function delete(){

        $id = input('get.id');

        $affectedRow = db('img')->where('id',$id)->delete();
        if($affectedRow){
            $this->success('删除成功');
        }
        else{
            $this->error('删除失败');
        }
    }


    public function edit(){
        $id = input('get.id');
        $info = db('img')->find($id);
//        print_r($info);die;
        $this->assign('info',$info);

        return view();
    }

    public function update(){

        $post = input('post.','','trim,htmlspecialchars');
//        dump($post);die;
        $file = request()->file('src');
        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){

            $info = db('img')->find($post['id']);
            if (file_exists(ROOT_PATH . 'public' . DS . 'uploads' . '/' .$info['src'])) {
                unlink(ROOT_PATH . 'public' . DS . 'uploads' . '/' .$info['src']);
            }
            if (file_exists(ROOT_PATH . 'public' . DS . 'thumbs' . '/' .$info['src'])) {
                unlink(ROOT_PATH . 'public' . DS . 'thumbs' . '/' .$info['src']);
            }


            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                $post['src'] = $info->getSaveName();


                echo $info->getSaveName();

                //制作缩略图
                $image = Image::open(ROOT_PATH . 'public' . DS . 'uploads'.'/'.$info->getSaveName());
                $dir = dirname(ROOT_PATH . 'public' . DS . 'thumbs/'.$info->getSaveName());  // 获取目录名称
                !file_exists($dir) && mkdir($dir,0777,true);  //二元运算符  不存在就创建
                $image->thumb(150, 150)->save(ROOT_PATH . 'public' . DS . 'thumbs/'.$info->getSaveName());
//               dump($res);die;
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
        db('img')->where('id', $post['id'])->update($post);
//
        $this->success('更新成功',url('index'));

    }

}
