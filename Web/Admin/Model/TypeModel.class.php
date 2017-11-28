<?php
namespace Admin\Model;
use Think\Model;

class TypeModel extends Model
{
    /**
     *处理显示列表数据
     */
    public function getData(){
        $arr = $this->select();
        foreach($arr as $k=>$v) {
            $str='';
            $num=substr_count($arr[$k]['path'], ',');
            for($i= 1; $i < $num; $i++){
                $str .= "　　";
            }
            $str .= '┗-'.$arr[$k]['name']; 
            $arr[$k]['name'] = $str;
        }
        return $arr;
    }

    //自动验证，分类名
    protected $_validate = [
    //检测分类名是否存在
    ['name', '', '分类名已经存在', 0, 'unique'],
    //检测分类名是否为空
    ['name', 'require', '请输出分类名'],
    //使用正则
    // ['name','/^[\x{4e00}-\x{9fa5}A-Za-z0-9]{3,16}$/u', '请输入合法分类名'],
    ];


    /**
     * 处理添加商品参数模板数据
     */
    
    public function getParam() {
        $arr = $this->getfield('id,name,path');
        foreach($arr as $k=>$v) {
            $str = '';
            $num = substr_count($arr[$k]['path'], ',');
            //让顶级分类不能被选中
            $dis = ($num > 1) ? '' :'disabled';
            $arr[$k]['dis'] = $dis;
            //根据PATH来添加符号，产生层次感
            for($i= 1; $i < $num; $i++){
                $str .= "　";
            }
            $str .= '┗'.$arr[$k]['name']; 
            $arr[$k]['name'] = $str;
        }
        return $arr;    
    }
}