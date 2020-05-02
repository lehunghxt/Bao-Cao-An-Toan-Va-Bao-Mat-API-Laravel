<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Post;
use Image;
use Validator;
class PostController extends Controller
{
    public function addPost(Request $request){
    	$validate = Validator::make($request->all(),
            [
                'post_name'    => 'required|string|regex:/[a-zA-Z ]*$/|unique:post,post_name',
                'post_content' => 'required|string',
                'cat_id'       => 'required',
                'image'        => 'required|regex:/^data:image/'
            ],
            [
                'post_name.required'        => 'Vui lòng nhập tên bài đăng !',
                'post_name.string'          => 'Vui lòng nhập đúng định dạng !',
                'post_name.unique'          => 'Bài đăng này đã tồn tại !',
                'post_name.regex'           => 'Vui lòng nhập ký tự hợp lệ !',
                'post_content.required'     => 'Vui lòng nhập nội dung bài đăng !',
                'post_content.string'       => 'Vui lòng nhập đúng định dạng !',
                'cat_id.required'           => 'Vui lòng chọn loại tin !',
                'image.required'            => 'Vui lòng chọn một hình ảnh !',
                'image.regex'               => 'Bạn chỉ được upload hình ảnh !',
            ]);
      	if ($validate->fails()) {
          return response()->json([
            'message'=>$validate->errors(),
            'error' => true
            ],200);
        }
        
    	$data 				= $request->all();
    	$status 			= empty($data['status']) ? 0 : 1; 
    	$post 				= new Post;
        // Upload Image
        if($request->get('image')){
          $image = $request->get('image');
          $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
          Image::make($request->get('image'))->save(public_path('post/').$name);
          $post->post_image = $name; 
        }
    	$post->post_name 	= PostController::xss_clean($data['post_name']);
    	$post->post_content = PostController::xss_clean($data['post_content']);
    	$post->user_id 		= $data['user_id'];
    	$post->cat_id 		= $data['cat_id'];
    	$post->status 		= $status;
    	$post->save();
    	return response()->json([
            'message' => 'Đã thêm tin !'
        ],201);
    }

    public function editPost(Request $request, $id = null){
        $validate = Validator::make($request->all(),
            [
                'post_name'     => 'required|string|regex:/[a-zA-Z ]*$/|unique:post,post_name'.$id,
                'post_content'  => 'required|string',
                'cat_id'        => 'required',
            ],
            [
                'post_name.required'        => 'Vui lòng nhập tên bài đăng !',
                'post_name.string'          => 'Vui lòng nhập đúng định dạng !',
                'post_name.unique'          => 'Bài đăng này đã tồn tại !',
                'post_name.regex'           => 'Vui lòng nhập ký tự hợp lệ !',
                'post_content.required'     => 'Vui lòng nhập nội dung bài đăng !',
                'post_content.string'       => 'Vui lòng nhập đúng định dạng !',
                'cat_id.required'           => 'Vui lòng chọn loại tin !',
            ]);
        if ($validate->fails()) {
          return response()->json([
            'message'=>$validate->errors(),
            'error' => true
            ],200);
        }
        $data               = $request->all();
        $status             = empty($data['status']) ? 0 : 1; 
        // Upload Image
        if($request->get('image')){
          if(file_exists('post/'.$data['img'])){
            unlink('post/'.$data['img']);
          }
          $image = $request->get('image');
          $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
          Image::make($request->get('image'))->save(public_path('post/').$name);
          Post::where(['id'=>$id])->update(['post_image' => $name]);
        }
        Post::where(['id'=>$id])->update([
            'post_name'     => $data['post_name'],
            'post_content'  => $data['post_content'],
            'cat_id'        => $data['cat_id'],
            'user_id'       => $data['user_id'],
            'status'        => $status
        ]);
        return response()->json([
            'message' => 'Đã sửa tin !'
        ],201); 
    }
    public function viewPost(Request $request){
        $check = $request->user();
        // admin
        if($check->rule==1){
            return response()->json([
                'data' => Post::join('users', 'post.user_id', '=', 'users.id')
                                ->join('table_category', 'post.cat_id', '=', 'table_category.id')
                                ->select('post.*', 'users.name', 'table_category.cat_name')->get(),
            ],201);
        }else{
            // user
            if( Post::where('user_id',$check->id)->count() > 0){
                return response()->json([
                    'data' => Post::where(['user_id' => $check->id])
                                ->join('users', 'post.user_id', '=', 'users.id')
                                ->join('table_category', 'post.cat_id', '=', 'table_category.id')->get(),
                ],201);
            }else{
                return response()->json([
                    'message' => 'Chưa có bài đăng nào.',
                    'emptyPost' => true,
                ],201);
             
            }
        }
    }

    public function viewPostID($id = null){
        $post = Post::find($id);
        if($post == null){
            return response()->json([
                'message'   => 'Không tìm thấy tin cần chỉnh sửa !',
                'findError' => true
            ],201); 
        }
        return response()->json([
                    'data' => $post
                ],201);
    }

    public function deletePost($id = null){
        $post = Post::where(['id'=>$id])->first();
        if(file_exists('post/'.$post->post_image)){
            unlink('post/'.$post->post_image);
        }
        Post::where(['id'=>$id])->delete();
        return response()->json([
            'message' => 'Đã xóa tin !'
        ],201);
    }
}
