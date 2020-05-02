<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use Validator;
class CategoryController extends Controller
{
    public function addCategory(Request $request){
    	$validate = Validator::make($request->all(),
        [
            'cat_name' => 'required|string|regex:/[a-zA-Z ]*$/|unique:table_category,cat_name',
        ],
        [
            'cat_name.required' => 'Vui lòng nhập loại tin !',
            'cat_name.string'   => 'Vui lòng nhập ký tự tường minh !',
            'cat_name.regex'    => 'Vui lòng nhập ký tự hợp lệ !',
            'cat_name.unique'   => 'Loại tin này đã tồn tại !',
        ]);
        if ($validate->fails()) {
          return response()->json([
            'message'=>$validate->errors(),
            'error' => true
            ],200);
        }
      	
    	$data           = $request->all();
    	$status         = empty($data['status']) ? 0 : 1; 
    	$cate           = new Category;
    	$cate->cat_name = $data['cat_name'];
    	$cate->status   = $status;
    	$cate->save();
    	return response()->json([
            'message' => 'Đã thêm loại tin !'
        ],200);
    }

    public function viewCategory(){
    	$cate = Category::get();
    	return response()->json([
            'data' => $cate
        ],200);
    }

    public function viewCategoryID($id = null){
        $cate = Category::find($id);
        if($cate == null){
            return response()->json([
                'findError' => true
            ],201); 
        }
        return response()->json([
            'data' => $cate
        ],200);
    }

    public function editCategory(Request $request, $id = null){
    	$validate = Validator::make($request->all(),
        [
            'cat_name' => 'required|string|regex:/[a-zA-Z ]*$/|unique:table_category,cat_name,'.$id,
        ],
        [
            'cat_name.required' => 'Vui lòng nhập loại tin !',
            'cat_name.string'   => 'Vui lòng nhập ký tự tường minh !',
            'cat_name.regex'    => 'Vui lòng nhập ký tự hợp lệ !',
            'cat_name.unique'   => 'Loại tin này đã tồn tại !',
        ]);
        if($validate->fails()) {
          return response()->json([
            'message'=>$validate->errors(),
            'error' => true
            ],200);
        }
    	$data = $request->all();
    	$status         = empty($data['status']) ? 0 : 1; 
    	Category::where(['id'=>$id])->update(['cat_name'=>$data['cat_name'],'status'=>$status]);
    	return response()->json([
            'message' => 'Đã chỉnh sửa loại tin !'
        ],200);
    }
    public function deleteCategory($id = null){
    	Category::where(['id'=>$id])->delete();
    	return response()->json([
            'message' => 'Đã xóa loại tin !'
        ],200);
    }
}
