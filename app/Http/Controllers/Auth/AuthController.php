<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Rule;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:15'],
                'address' => ['required', 'string', 'max:255'],
                // 'image' => ['required', 'string', 'max:255'],
                'type' => ['required', 'string', 'max:255'],
                'status' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
    
            $user=new User();
            $user->name=$request->name;
            $user->last_name=$request->last_name;
            $user->phone=$request->phone;
            $user->address=$request->address; 
            // $user->image=$request->image;
            
            $user->image='default.png'; 
    
            $user->type=$request->type;
            $user->status='ACTIVO';
            $user->email=$request->email;
            $user->password=Hash::make($request->password);
            $user->save();
    
            if ($request->hasFile('image')) {
    
                $extension = $request->image->extension();
                $new_name = 'user_' . $user->id . '_1.' . $extension;
                $path = $request->image->storeAs('/images/users', $new_name, 'public');
                $user->image= $path;
                $user->save();
            }
    
            // Disparar el evento de registro para enviar el correo de verificación
          //  event(new Registered($user));

            $token = $user->createToken('apiToken')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
              //  'message' => 'Usuario registrado. Por favor, verifica tu correo electrónico.',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ]);
        }
        
    }

     // Login
     public function login(Request $request)
     {
        try {
            $data = $request->validate([
                'email' => 'required|string',
                'password' => 'required|string'
            ]);
    
            $user = User::where('email', $data['email'])->first();
    
            if (!$user || !Hash::check($data['password'], $user->password)) {
                return response([
                    'msg' => 'incorrect username or password'
                ], 401);
            }
    
            $token = $user->createToken('apiToken')->plainTextToken;
    
            $res = [
                'user' => $user,
                'token' => $token
            ];
    
            return response($res, 201);
        } catch (\Throwable $th) {
            return response()->json(['error'=> $e->getMessage()]);
        }
         
     }
 
     public function loginGoogle(Request $request)
     {
        try {
            $data = $request->validate([
                'email' => 'required|string',
            ]);
    
            $user = User::where('email', $data['email'])->first();
    
            // if (!$user || !Hash::check($data['password'], $user->password)) {
            //     return response([
            //         'msg' => 'incorrect username or password'
            //     ], 401);
            // }
    
            $token = $user->createToken('apiToken')->plainTextToken;
    
            $res = [
                'user' => $user,
                'token' => $token
            ];
    
            return response($res, 201);
        } catch (\Throwable $th) {
            return response()->json(['error'=> $e->getMessage()]);
        }
         
     }
     
 
     // Logout
     public function logout(Request $request)
    {
        try {
            $user = $request->user(); // Verifica si el usuario está autenticado
           // dd($user);// Verifica si el usuario está autenticado

            if ($user) {
                $user->tokens()->delete(); // Revoca los tokens del usuario
                return response()->json(['message' => 'Usuario cerró sesión con éxito']);

            }else{
                return response()->json(['error' => 'Usuario no esta logueado']);

            }

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        try {
           // dd($request);
            $user = $request->user(); 
            //dd($user);
            //dd($request->user());// Verifica si el usuario está autenticado
            if (!$user) {
                return response()->json(['error' => 'Usuario no autenticado'], 401);
            }
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:15'],
                'address' => ['required', 'string', 'max:255'],
                // 'image' => ['required', 'string', 'max:255'],
                'type' => ['required', 'string', 'max:255'],
                'status' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255',  'unique:users,email,' . $user->id,],
                //'password' => ['required', 'confirmed',],
            ]);

            //$user=User::where('email',$request->email)->first(); 
           // dd($user);
            $user->name=$request->name;
            $user->last_name=$request->last_name;
            $user->phone=$request->phone;
            $user->address=$request->address; 
            // $user->image=$request->image;
            //$user->image='default.png'; 
            $user->type=$request->type;
            $user->status= $request->status;
            $user->email=$request->email;
           // $user->password=Hash::make($request->password);
            $user->save();
    
            if ($request->hasFile('image')) {
    
                $extension = $request->image->extension();
                $new_name = 'user_' . $user->id . '_1.' . $extension;
                $path = $request->image->storeAs('/images/users', $new_name, 'public');
                $user->image= $path;
                $user->save();
            }



            return response()->json(['success' => 'Usuario actualizado']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }


}
