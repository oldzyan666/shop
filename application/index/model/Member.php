<?php

namespace app\index\model;

class Member{

    /* public function __construct()
    {

    echo 123;
    }*/

    // 获取一级伙伴

    public function getPartners($id){

        return db('user')->where('pid',$id)->select();

    }

    // 获取一级,二级，三级伙伴
    // $id  当前会员的　id

    public function getAllPartners($id){

        $return = [];

        // 一级伙伴
        $level1 = $this->getPartners($id);
//        $return['level1'] = [];
        //dump($level1);die;

        if (!empty($level1)){
            $return['level1'] = $level1;
            // 二级伙伴
            foreach ($level1 as $k1 => $v1){

            $tem_level2[] = $this->getPartners($v1['id']);

            }
            //dump($level2);die; // 三维数组
            //三维数组　转　二维数组
            foreach ($tem_level2 as $k2=>$v2){

                foreach ($v2 as $v3){

                    $level2[] = $v3;

                }
            }

            //dump($level2);die;

            if (!empty($level2)) {
                $return['level2'] = $level2;
                // 三级伙伴
                foreach ($level2 as $k1 => $v1) {

                    $tem_level3[] = $this->getPartners($v1['id']);

                }

                //      dump($tem_level3);die; // 三维数组

                //　三维数组　转　二维数组
                foreach ($tem_level3 as $k2 => $v2) {

                    foreach ($v2 as $v3) {

                        $level3[] = $v3;

                    }
                }
                if (!empty($level3)) {
                    $return['level3'] = $level3;
                }
            }
        }
        //dump($level3);die;
        return $return;
    }


    //  获取上级
    public function getReferee($id){
        // 获取会员数据　　 　find 查询一条数据
        $info = db('user')->find($id);

        // 只有一个推荐人
        $referee = db('user')->where('id',$info['pid'])->find();

        return $referee;
    }



}