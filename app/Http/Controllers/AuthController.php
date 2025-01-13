<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function login(){
        return view('front.account.login');

    }

    public function register(){
        return view('front.account.register');
    }

    public function processRegister(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',  // Perbaiki 'min' dengan tanda kolon ':'
            'email' => 'required|email|unique:users,email',  // Perbaiki penulisan unique dan email
            'password' => 'required|min:5|confirmed',  // Perbaiki 'min' dengan tanda kolon ':'
        ]);
        
        if ($validator->passes()) {

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make ($request->password);
            $user->save();

            return response()->json([
                'status' => true,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
            
        }
        
        Auth::login($user);

        // Redirect ke halaman home setelah registrasi
        return redirect()->route('front.home');
    }
}
