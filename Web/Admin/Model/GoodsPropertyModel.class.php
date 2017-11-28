<?php
namespace Admin\Model;
use Think\Model;

class GoodsPropertyModel extends Model
{   
    //处理数组成为需要的二维数组
    public function manageProperty() {
        $data = I('post.');
        foreach ($data as $key => $value) {
            if ($key == 'taste') {
                foreach ($data[$key] as $k=>$v) {
                    $arr[$k]['taste'] = $data[$key][$k];
                }
            }
            if ($key == 'now_price') {
                foreach ($data[$key] as $k=>$v) {
                    $arr[$k]['now_price'] = $data[$key][$k];

                }
            }
            if ($key == 'repertory') {
                foreach ($data[$key] as $k=>$v) {
                    $arr[$k]['repertory'] = $data[$key][$k];
                }
            }
        }
        return $arr;
    }
}