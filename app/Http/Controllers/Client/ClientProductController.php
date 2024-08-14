<?php

namespace App\Http\Controllers\client;
use App\Http\Controllers\controller;
use App\Models\Product;
use App\Models\order;
use App\Models\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientProductController extends Controller

{
   public function index(){
    return view('/client_public.cat')->with('products', Product::all());
    }

   public function show($id){
    
    return view('/client_public.detail')->with('product', Product::find($id));
    }

    public function view(){
       // if(Auth::user()->type=='ADMIN')
       $users = User::where('type','CLIENTE')->where('status','ACTIVO')->get();
       foreach($users as $user){
            $user->image = asset('/storage/' . $user->image);
            
       }
       return response()->json(['users'=> $users]);
       //return view('users.index')->with('Users', user::all());
       // else {
            return redirect()->route('login');
        //}   
    }
    
        public function showOrders($userId)
        {
            $user = User::find($userId);
    
            if ($user) {
                $orders = Order::where('user_id', $user->id)->get();
    
                return view('users.orders', compact('user', 'orders'));
            } else {
                // Manejo si el usuario no es encontrado
                // Puedes redirigir a una página de error o hacer algo más
            }
        }


        public function edit($userId)
        {
           
            try {
                $user = User::find($userId);
                return response()->json(['user' => $user]);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()]);
            }
            // if ($user) {
            //     return view('users.edit', compact('user'));
            // } else {
            //     abort(404);
            // }
        }


        public function update(Request $request, $userId)
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
                    // 'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
                    // 'password' => ['required', 'confirmed', Rules\Password::defaults()],
                ]);
    
                $user= User::find($userId);
                $user->name=$request->name;
                $user->last_name=$request->last_name;
                $user->phone=$request->phone;
                $user->address=$request->address; 
                // $user->image=$request->image;
                
               // $user->image='default.png'; 
        
                $user->type=$request->type;
                $user->status= $request->status;
                $user->email=$request->email;
                //$user->password=Hash::make($request->password);
                $user->save();
        
                if ($request->hasFile('image')) {
        
                    $extension = $request->image->extension();
                    $new_name = 'user_' . $user->id . '_1.' . $extension;
                    $path = $request->image->storeAs('/images/users', $new_name, 'public');
                    $user->image= $path;
                    $user->save();
                }
                return response()->json(['message' =>'Cliente actualizado']);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()]);
            }
            

           

            // $user = User::find($userId);

            // if ($user) {
            //     $user->update($request->all());

            //     return redirect()->route('user.edit', ['userId' => $user->id])->with('success', 'Usuario actualizado exitosamente.');
            // } else {
            //     abort(404);
            // }
        }

        public function delete($id){
            try {
                $user = User::find($id);
                $user->status = 'INACTIVO';
                $user->save();
                return response()->json(['message'=> 'cliente eliminado']);
            } catch (\Exception $e) {
                return response()->json(['error'=> $e->getMessage()]);
            }
        }
}

