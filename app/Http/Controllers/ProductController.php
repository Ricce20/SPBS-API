<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){
        $products = Product::with('supplier')->where('status','ACTIVO')->get();
        foreach ($products as $product) {
            $product->image_1 = asset('/storage/' . $product->image_1);
            $product->image_2 = asset('/storage/' . $product->image_2);
            $product->image_3 = asset('/storage/' . $product->image_3);
            
        }
        return response()->json(['products' => $products]);
    }

    public function indexCat(){
        $products = Product::where('status','Activo')->get();
        foreach ($products as $product) {
            $product->image_1 = asset('/storage/' . $product->image_1);
            
        }
        return response()->json(['products' => $products]);
    }

//    public function create(){
//     return view('/products/create')->with('suppliers', Supplier::where('status', 'Activo')->get());
//    }

   public function store(Request $request){
    try {
        $product=new Product();
    $product->supplier_id=$request->supplier_id;
    $product->name=$request->name;
    $product->price=$request->price;
    $product->description=$request->description;
    $product->existence=$request->existence;
    // $product->image_1=$request->image1;
    // $product->image_2=$request->image2;
    // $product->image_3=$request->image3;

    $product->image_1='default.png';
    $product->image_2='default.png';
    $product->image_3='default.png';

    $product->capability=$request->capability;
    $product->capability_type=$request->capability_type;
    $product->color=$request->color;
    $product->type=$request->type;
    $product->status=$request->status;

    $product->save();

                if ($request->hasFile('image1')) {

                    $extension = $request->image1->extension();
                    $new_name = 'product_' . $product->id . '_1.' . $extension;
                    $path = $request->image1->storeAs('/images/products', $new_name, 'public');
                    $product->image_1 = $path;
                    $product->save();
                }

                if ($request->hasFile('image2')) {

                $extension = $request->image2->extension();
                $new_name = 'product_' . $product->id . '_2.' . $extension;
                $path = $request->image2->storeAs('/images/products', $new_name, 'public');
                $product->image_2 = $path;
                $product->save();
            }

            if ($request->hasFile('image3')) {

                $extension = $request->image3->extension();
                $new_name = 'product_' . $product->id . '_3.' . $extension;
                $path = $request->image3->storeAs('/images/products', $new_name, 'public');
                $product->image_3 = $path;
                $product->save();
            }
            
    return response()->json(['success'=> 'Producto creado correctamente']);

    } catch (\Exception $e) {
        return response()->json(['error'=> $e->getMessage()]);

    }

    

    // return view('/products/index')->with('products', Product::where('status', 'Activo')->get());
   }

//    public function edit($id){
//     return view('/products/edit')->with('product', Product::find($id))->with('suppliers', Supplier::where('status', 'Activo')->get());
//    }

   public function update(Request $request, $id){
    try {
        $product= Product::find($id);
        $product->supplier_id=$request->supplier_id;
        $product->name=$request->name;
        $product->price=$request->price;
        $product->description=$request->description;
        $product->existence=$request->existence;
        // $product->image_1=$request->image1;
        // $product->image_2=$request->image2;
        // $product->image_3=$request->image3;
        $product->capability=$request->capability;
        $product->capability_type=$request->capability_type;
        $product->color=$request->color;
        $product->type=$request->type;
        $product->status=$request->status;
    
        $product->save();
    
        if ($request->hasFile('image1')) {

            $extension = $request->image1->extension();
            $new_name = 'product_-' . $product->id . '_1.' . $extension;
            $path = $request->image1->storeAs('/images/products', $new_name, 'public');
            $product->image_1 = $path;
            $product->save();
        }

        if ($request->hasFile('image2')) {

        $extension = $request->image2->extension();
        $new_name = 'product_-' . $product->id . '_2.' . $extension;
        $path = $request->image2->storeAs('/images/products', $new_name, 'public');
        $product->image_2 = $path;
        $product->save();
    }
    

    if ($request->hasFile('image3')) {

        $extension = $request->image3->extension();
        $new_name = 'product_-' . $product->id . '_3.' . $extension;
        $path = $request->image3->storeAs('/images/products', $new_name, 'public');
        $product->image_3 = $path;
        $product->save();
    }
    
            return response()->json(['success'=> 'Producto Actuualizado']);
        } catch (\Exception $e) {
            return response()->json(['error'=> $e->getMessage()]);
        }
   
   }

   public function show($id){
    $product = Product::with('supplier')->find($id);
    if(!$product){
        return response()->json(['error'=>'Producto No Encontrado']);
    }
    return response()->json(['product'=> $product]);
}

   public function delete($id){
    $product= Product::find($id);
    if(!$product){
        return response()->json(['error'=>'Producto No Encontrado']);
    }
    $product->status='Inactivo';
    $product->save();
    return response()->json(['error'=>'Producto Eliminado']);

    // return view('/products/index')->with('products', Product::where('status', 'Activo')->get());
   }


}