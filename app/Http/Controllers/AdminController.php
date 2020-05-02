<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Validator;
use App\Post;
use Hash;

class AdminController extends Controller
{
    public function addUser(Request $request){
    	$validate = Validator::make($request->all(),
        [
            'name' 		=> 'required|string',
            'email' 	=> 'required|email|unique:users,email',
            'password' 	=> 'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,40}$/',
            'rule' 		=> 'required',
        ],
        [
            'name.required'         => 'Tên không được để trống !',
            'name.string'           => 'Vui lòng nhập đúng tên !',
            'email.required'        => 'Email không được để trống !',
            'email.email'           => 'Vui lòng nhập đúng định dạng email !',
            'email.unique'          => 'Email này đã tồn tại !',
            'password.required'     => 'Vui lòng nhập mật khẩu !',
            'password.regex'        => 'Mật khẩu bao gồm ký tự hoa thường và có ít nhất 8 ký tự !',
            'rule.required'         => 'Vui lòng chọn rule !',
        ]);
        if ($validate->fails()) {
          return response()->json([
            'message'=>$validate->errors(),
            'error' => true
            ],200);
        }
      	
    	$data 			= $request->all();
    	$status         = empty($data['status']) ? 0 : 1; 
    	$user 			= new User;
    	$user->name 	= $data['name'];
    	$user->email 	= $data['email'];
    	$user->password = bcrypt($data['password']);
    	$user->rule 	= $data['rule'];
    	$user->status 	= $status;
    	$user->save();
    	return response()->json([
            'message' => 'Đã thêm thành viên !'
        ],201);
    }
    public function viewUser(Request $request){
    	$user = User::where('id', '!=', $request->user()->id)->get();
    	return response()->json([
            'data' => $user
        ],201);
    }
    public function viewUserID($id = null){
        $users = User::find($id);
        if($users == null){
            return response()->json([
                'findError' => true
            ],201); 
        }
        return response()->json([
            'data' => $users
        ],200);
    }
    public function editUser(Request $request, $id = null){
    	$validate = Validator::make($request->all(),
        [
            'name'      => 'required|string',
            'email'     => 'required|email|unique:users,email,'.$id,
            'rule'      => 'required',
        ],
        [
            'name.required'         => 'Tên không được để trống !',
            'name.string'           => 'Vui lòng nhập đúng tên !',
            'email.required'        => 'Email không được để trống !',
            'email.email'           => 'Vui lòng nhập đúng định dạng email !',
            'email.unique'          => 'Email này đã tồn tại !',
            'rule.required'         => 'Vui lòng chọn rule !',
        ]);
        if ($validate->fails()) {
          return response()->json([
            'message'=>$validate->errors(),
            'error' => true
            ],200);
        }
      	
    	$data 			= $request->all();
    	$status         = empty($data['status']) ? 0 : 1; 
    	User::where(['id'=>$id])->update([
    		'name' 		=> $data['name'],
    		'email' 	=> $data['email'],
    		'rule'		=> $data['rule'],
    		'status'	=> $status
    	]);
    	return response()->json([
            'message' => 'Đã chỉnh sửa thành viên !'
        ],201);
    }
    public function deleteUser($id = null){
        $user = User::findOrFail($id);
        $post = Post::where(['user_id'=>$user->id])->first();
        if($post != null){
            if(file_exists('post/'.$post->post_image)){
                unlink('post/'.$post->post_image);
            }
            Post::where(['id'=>$id])->delete();
        }
    	User::where(['id'=>$id])->delete();
    	return response()->json([
            'message' => 'Đã xóa thành viên !'
        ],201);
    }
    public function changePassword(Request $request){
        $validate = Validator::make($request->all(),
        [
          'curr_pass' => 'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,40}$/',
          'new_pass'  => 'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,40}$/|different:curr_pass',
          'conf_pass' => 'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,40}$/|different:curr_pass|same:new_pass',
        ],
        [
          'curr_pass.required'  => 'Vui lòng nhập mật khẩu !',
          'curr_pass.regex'     => 'Mật khẩu bao gồm ký tự hoa thường và có ít nhất 8 ký tự !',
          'new_pass.required'   => 'Vui lòng nhập mật khẩu mới !',
          'new_pass.regex'      => 'Mật khẩu bao gồm ký tự hoa thường và có ít nhất 8 ký tự !',
          'new_pass.different'  => 'Mật khẩu mới của bạn phải khác mật khẩu cũ !',
          'conf_pass.required'  => 'Vui lòng nhập mật khẩu xác nhận !',
          'conf_pass.regex'     => 'Mật khẩu bao gồm ký tự hoa thường và có ít nhất 8 ký tự !',
          'conf_pass.different' => 'Mật khẩu xác nhận của bạn phải khác mật khẩu cũ !',
          'conf_pass.same'      => 'Mật khẩu xác nhận không khớp !',
        ]);
        if ($validate->fails()) {
          return response()->json([
            'message'=>$validate->errors(),
            'error' => true
            ],200);
        }
        $data = $request->all();
        if (!Hash::check($data['curr_pass'], $request->user()->password)) {
           return response()->json([
                'message' => 'Mật khẩu hiện tại không đúng',
                'errorPass' => true
            ],200);
        }
        User::where('id',$request->user()->id)->update(['password'=>bcrypt($data['new_pass'])]);
        return response()->json(['message' => 'Đổi mật khẩu thành công'],200);
    }
}
