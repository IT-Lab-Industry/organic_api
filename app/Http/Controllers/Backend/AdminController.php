<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ForgetPassword;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Hash;
use App\Models\Admin;
use Illuminate\Support\Facades\File;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class AdminController extends Controller
{
    public function registration(Request $request){
        // if(Admin::count()  == 0){
            
        // }else{
        //     return Response()->json([
        //         'status'    => 402,
        //         'message'   => 'You are not elegible this request.'
        //     ]);
        // }

        $validator = Validator::make($request->all(),[
                'name'  => 'required|string|max:255',
                'email' => 'required|unique:admins,email|email',
                'password'  => [
                    'required',
                    // Password::min(8)->symbols()->mixedCase()->numbers()->uncompromised()
                ],
                'profile_image' => 'required|mimes:jpg,png,jpeg,gif,svg'

            ]);
    
    
    
            if($validator->fails()){
                return Response()->json([
                    'status'    => 401,
                    'errors'    => $validator->errors()->all()
                ]);
            }
    
            $user_type = 'admin';
            $admin = new Admin();
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->password = Hash::make($request->password);
            if($request->hasFile('profile_image')){
                $image = $request->file('profile_image');
                $name = md5($image->getClientOriginalName()) . '_' . md5(time()) . '_rh' . '.jpg';
                $path = public_path() . '/profile' . '/' . $name;
                if(!File::exists(public_path() . '/profile')){
                    File::makeDirectory(public_path() . '/profile');
                }
                $imageManager = new ImageManager(new Driver());
                $imageManager->read($image)->save($path);
                $admin->profile_image = $name;

            }
            $admin->user_type = $user_type;
            $admin->status = 1;
            $admin->verify = 1;
    
            if($admin->save()){
                $token = $admin->createToken($admin->email)->plainTextToken;
                return Response()->json([
                    'status'    => 200,
                    'message'   => 'Admin Registration Successfully',
                    'token'    => $token
                ]);
            }else{
                return Response()->json([
                    'status'    => 402,
                    'message'   => 'Something went wrong. Please try again.'
                ]);
            }
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'email'     => 'required',
            'password'  => 'required'
        ]);
        if($validator->fails()){
            return Response()->json([
                'status'    => 401,
                'errors'    => $validator->errors()->all()
            ]);
        }
        $user = Auth::guard('admin')->attempt([
            'email'     => $request->email,
            'password'  => $request->password
        ]);

        if($user){
            if(Auth::guard('admin')->user()->verify != 0){
                if(Auth::guard('admin')->user()->status != 0){
                    $token = Auth::guard('admin')->user()->createToken(Auth::guard('admin')->user()->email)->plainTextToken;
                    return Response()->json([
                        'status'    => 200,
                        'message'   => 'Login Successfully',
                        'token'     => $token
                    ]);
                }else{
                    return Response()->json([
                        'status'    => 402,
                        'message'   => 'You are suspended user!'
                    ]);
                }
            }else{
                return Response()->json([
                    'status'    => 402,
                    'message'   => 'User is not verified'
                ]);
            }
        }else{
            return Response()->json([
                'status'    => 402,
                'message'   => 'Email or Password not matched.'
            ]);
        }

    }

    public function forget_password(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email'
        ]);
        if($validator->fails()){
            return Response()->json([
                'status'=> 401,
                'errors'=> $validator->errors()->all()
            ]);
        }
        $admin = Admin::where('email',$request->email)->first();
        if($admin){
            $user_type = 'admin';
            $token = md5(time() . uniqid()) . '_rh';
            $storeData = new ForgetPassword();
            $storeData->user_id = $admin->id;
            $storeData->user_type = $user_type;
            $storeData->token = $token;
            
            if($storeData->save()){
                $data = [
                    'admin' => $admin,
                    'token' => $token
                ];
                if(Mail::to($admin->email)->send(new \App\Mail\ForgetPassword($data))){
                    return Response()->json([
                        'status'    => 200,
                        'message'   => 'Reset Password link is send.'
                    ]);
                }
                
            }
        }
    }

    public function check_token($token){
        $data = ForgetPassword::where('token',$token)->first();
        if($data){
            if(Carbon::now()->lessThan(Carbon::parse($data->created_at)->addMinutes(30))){
                 return Response()->json([
                    'status'    => 200,
                    'token'     =>  $token,
                    'message'   => 'Token is Valied.'
                ]);
            }else{
                return Response()->json([
                    'status'    => 401,
                    'message'   => 'Token is Expired.'
                ]); 
            }
        }else{
            return Response()->json([
                'status'    => 401,
                'message'   => 'Invalid token.'
            ]);
        }
    }

    public function resetPassword(Request $request){
        $validator = Validator::make($request->all(),[
            'token'     => 'required',
            'password'  => [
                'required',
                // Password::min(8)->symbols()->mixedCase()->uncompromised()
            ]
        ]);
        if($validator->fails()){
            return Response()->json([
                'status'    => 401,
                'errors'    => $validator->errors()->all()
            ]);
        }
        $token = $request->token;

        $data = ForgetPassword::where('token',$token)->first();
        if($data){
            if(Carbon::now()->lessThan(Carbon::parse($data->created_at)->addMinutes(30))){
                 $admin = Admin::where('id',$data->user_id)->first();
                 $admin->password = Hash::make($request->password);
                 if($admin->update()){
                    $data->delete();
                    return Response()->json([
                        'status'    => 200,
                        'message'   => 'Password Update Successfully.'
                    ]);
                 }
            }else{
                return Response()->json([
                    'status'    => 401,
                    'message'   => 'Token is Expired.'
                ]);
            }
        }else{
            return Response()->json([
                'status'    => 401,
                'message'   => 'Invalid token.'
            ]);
        }
    }

    
}
