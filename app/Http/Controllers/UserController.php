<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use Validator;

class UserController extends Controller
{
    
	// Đăng Ký tài khoản
    public function signup(Request $request)
    {
        //  Validate thông tin đăng ký
        $validate = Validator::make($request->all(),
        [
            'name'      => 'required|string',
            'email'     => 'required|string|email|unique:users,email|regex:/^[a-z][a-z0-9_\.]{5,32}@[a-z0-9]{2,}(\.[a-z0-9]{2,4}){1,2}$/',
            'password'  => 'required|string|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,40}$/|confirmed',
        ],
        [
            'name.required'       => 'Vui lòng nhập tên của bạn !',
            'name.string'         => 'Vui lòng nhập đúng định dạng !',
            'email.required'      => 'Vui lòng nhập email của bạn !',
            'email.string'        => 'Vui lòng nhập đúng định dạng email !',
            'email.email'         => 'Vui lòng nhập đúng định dạng email !',
            'email.unique'        => 'Email này đã tồn tại. Vui lòng nhập một email khác !',
            'email.regex'         => 'Vui lòng nhập đúng định dạng email !',
            'password.required'   => 'Vui lòng nhập mật khẩu !',
            'password.string'     => 'Vui lòng nhập đúng định dạng mật khẩu !',
            'password.regex'      => 'Mật khẩu bao gồm ký tự hoa thường và có ít nhất 8 ký tự !',
            'password.confirmed'  => 'Mật khẩu xác nhận không đúng !',
            
        ]);
        // Gửi thông báo lỗi
        if ($validate->fails()) {
          return response()->json([
            'message'=>$validate->errors(),
            'error' => true
            ],200);
        }
        // Thêm user
        $data           = $request->all();
        $status         = empty($data['status']) ? 0 : 1; 
        $user           = new User;
        $user->name     = $data['name'];
        $user->email    = $data['email'];
        $user->password = bcrypt($data['password']);
        $user->rule     = 2;
        $user->status   = 2;
        $user->save();
        return response()->json([
            'message' => 'Successfully created user!'
        ], 200);
    }
  //  Đăng Nhập
    public function login(Request $request)
    {

         $validate = Validator::make($request->all(),
        [
            'email' => 'required|string|email|regex:/^[a-z][a-z0-9_\.]{5,32}@[a-z0-9]{2,}(\.[a-z0-9]{2,4}){1,2}$/',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ],
        [
            'email.required'      => 'Vui lòng nhập email của bạn !',
            'email.string'        => 'Vui lòng nhập đúng định dạng email !',
            'email.email'         => 'Vui lòng nhập đúng định dạng email !',
            'email.regex'         => 'Vui lòng nhập đúng định dạng email !',
            'password.required'   => 'Vui lòng nhập mật khẩu !',
            'password.string'     => 'Vui lòng nhập đúng định dạng mật khẩu !',
        ]);
         //  Validate thông tin đăng nhập
        if ($validate->fails()) {
          return response()->json([
            'message' => $validate->errors(),
            'error'   => true
            ],200);
        }
        // Tiến hành đăng nhập
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials)){
            return response()->json([
                'errLogin' => 'Email hoặc mật khẩu không đúng !',
                'failLogin' => true
            ], 200);
        }
            
        if($request->user()->status == 2){
            return response()->json([
                'errLogin' => 'Tài khoản của bạn chưa được cấp quyền !',
                'failLogin' => true
            ], 200);
        }
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        // Gửi thông tin đăng nhập cho client
        return response()->json([
            'user'         => $user,
            'access_token' => $tokenResult->accessToken,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
        ]);
    }
  //  Đăng xuất
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
    //  Get Thông tin user
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
