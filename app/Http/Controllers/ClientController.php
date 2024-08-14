<?php

namespace App\Http\Controllers;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ClientController extends Controller
{
    public function index(){
        $clientes = Client::where('status','ACTIVO')->get();
        return response()->json(['clients' => $clientes]);
        //return view('/clients/index')->with('clients', Client::where('status', 'Activo')->get());
    }

    // public function create(){
    //     return view('/clients/create');
    // }

    public function store(Request $request){

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

        // $client=new Client();
        // $client->name=$request->name;
        // $client->last_name=$request->last_name;
        // $client->phone=$request->phone;
        // $client->address=$request->address; 
        // $client->image=$request->image; 
        // $client->status='ACTIVO';
        // $client->save();

        return response()->json(['message' => 'cliente guardado']);
       // return view('/clients/index')->with('clients', Client::where('status', 'Activo')->get());
    }

    public function edit($id){
        // find solo sirven para llaves primarias
        return view('/clients/edit')->with('client', Client::find($id));
    }
    
    public function update(Request $request, $id){
        $client= Client::find($id);
        $client->name=$request->name;
        $client->last_name=$request->last_name;
        $client->phone=$request->phone;
        $client->address=$request->address;
        $client->image=$request->image; 
        $client->status=$request->status;
    
        $client->save();
        return view('/clients/index')->with('clients', Client::where('status', 'Activo')->get());
    }

    public function show($id){
        return view('/clients/show')->with('client', Client::find($id));
    }

    public function delete(Request $request, $id){
        $client= Client::find($id);   
        $client->status='Inactivo';    
        $client->save();
    
        return view('/clients/index')->with('clients', Client::where('status', 'Activo')->get());
       }
    
}