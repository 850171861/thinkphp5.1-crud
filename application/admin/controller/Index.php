<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\UserModel;

class Index extends Controller
{
    //首页模板
    public function index()
    {   
        // 返回index.html
        return $this->fetch();
    }

    //创建数据模板
    public function create()
    {
        // 返回create.html
        return $this->fetch();
    }

    //保存数据  url = "{:url('save')}"
    public function save(Request $request)
    {  
        //接受收前端的数据
        $data = $request->param();
        //拿到model字段
        $user = new UserModel();
        print_r(!empty($data['id']));die();
        //如果$data里面ID 并且是否未null &&  $data的ID是否为空 
        if(isset($data['id']) && !empty($data['id'])){
            //修改数据，第一个数据，第二个修改数据的ID
            $res = $user->save($data,['id' => $data['id']]);
        }else{
            //保存$data数据到user
            $res = $user->save($data);
        }

        //如果插入数据库成功 返回前端code=1，以及信息
        if($res){
            return json(['code'=>1,'msg'=>'操作成功']);
        }else{
            return json(['code'=>0,'msg'=>'操作失败']);
        }
    }
    //删除数据    url = "{:url('delete')}"
    public function delete(Request $request)
    {   
        //接受前端的传过来的ID
        $id = $request->param('id');
        //拿到model字段
        $db = new UserModel();
        // 对model进行操作，找到model的id并删除
        $res = $db->where('id',$id) ->delete();
        //如果插入数据库成功 返回前端code=1，以及信息
        if($res){
            return json(['code'=>1,'msg'=>'删除成功']);
        }else{
            return json(['code'=>0,'msg'=>'删除失败']);
        }
    }
    //修改数据  url = "{:url('edit')}"
    public function edit(Request $request){
        // 接收前端ID 
        $id = $request->param('id');
        // 拿到model
        $db = new UserModel();
        // 对模型进行操作，找到id并查询一条数据，find查询一条，select查询多条
        $info = $db->where('id',$id)->find();
        // 取出$info值，传过view模板
        $this->assign('info',$info);
        //  返回edit.html
        return $this->fetch();
    }
    //查询数据  url = "{:url('api')}"
    public function api(Request $request)
    {
        // 接受页的参数
        $page = $request->param('page'); 
        // 接受查询条数
        $limit = $request->param('limit');
        // 拿到model
        $user = new UserModel();
        // 拿到这个user表的总条数
        $count = $user->count('id');
        // 默认查询1页10条数据
        $list = $user->limit($limit)->page($page)->order('id desc') -> select();
        return json(['code'=>0,'msg'=>'获取成功','count'=> $count,'data'=>$list]);
    }

}

?>