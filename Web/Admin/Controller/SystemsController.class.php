<?php
namespace Admin\Controller;
use Think\Controller;
class SystemsController extends Controller {
    public function index(){
        $this->display('Systems/systems');
    }
}