<?php


namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Order;
use App\Models\DetailOrder;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Mail\OrderCreated;
use App\Mail\OrderUpdate;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{

        public function index($id)
        {
            // $user = Auth::user();

            //       if ($user != null) {
            //     $orders = Order::where('user_id', $user->id)->get();

            //     return view('orders.orders_user', compact('orders'));
            //     } else 
            //     {
            //     return redirect()->route('login');}

            $orders = Order::where('user_id',$id)->orderBy('time', 'desc')->get();
            
            return response()->json(['orders'=> $orders]);
        }


        public function admin(Request $request)
        {
    
                $query = Order::select('orders.*', 'users.name as user_name')
                    ->join('users', 'orders.user_id', '=', 'users.id');
    
                if ($request->has('status') && in_array($request->status, ['Pendiente','Cancelado', 'Pagado', 'Enviado', 'Entregado'])) {
                    $query->where('orders.status', $request->status);
                }
    
                if ($request->has('order_by_date') && $request->order_by_date == '1') {
                    $query->orderBy('orders.date', 'desc');
                }
    
                if ($request->has('order_by_date') && $request->order_by_date == '0') {
                    $query->orderBy('orders.date', 'asc');
                }
    
                $orders = $query->get();
                return response()->json(['orders' => $orders,'statusFilter' => $request->status,'dateOrderFilter' => $request->order_by_date]);
                // return view('orders.index', [
                //     'orders' => $orders,
                //     'statusFilter' => $request->status,
                //     'dateOrderFilter' => $request->order_by_date
                // ]);
           
        }


        public function show(Request $request){

        if(Auth::user()->type == 'ADMIN'){
        $orders = Order::get();
        return view('/orders.admindetail', compact('orders'));
          
        }}

        public function createDetailOrder(Request $request,$IdOrder){
            //dd($request);
            foreach ($request->datos as $item) {
                $detalle = new DetailOrder();
                $detalle->id_order = $IdOrder;
                $detalle->id_product = $item['id'];
                $detalle->quantity = $item['quantity'];
                $detalle->price = $item['price'];
                $detalle->subtotal = $item['price'] * $item['quantity'];
                $detalle->save();
    
                $product = Product::find($item['id']);
                $existence = $product->existence - $item['quantity'];
                $product->update(['existence' => $existence]);
            }
        }
        
        public function createOrder(Request $request){
            $order = new Order();
            $order->time = now();
            $order->phone = $request->phone;
            $order->address = $request->address;
            $order->status = 'Pagado';
            $order->user_id = $request->user_id;
            $order->save();
            // Enviar el correo de confirmación de la orden
            Mail::to($request->user()->email)->send(new OrderCreated($order));

            return response()->json(['order' => $order]);
        }


        

        
       

       public function prod($orderId)
        {
            // $orders = Order::select('orders.*', 'products.name as product_name')
            //     ->join('products', 'orders.product_id', '=', 'products.id')
            //     ->where('orders.id', $orderId)
            //     ->get();

            $detailorder = DetailOrder::with('product')->where('id_order', $orderId)->get();
            return response()->json(['detailorder' => $detailorder]);
            //dd($detailorder);
        // if ($detailorder->isNotEmpty()) {
               // return view('/orders.admindetail')->with('orders', $detailorder);
            //}
        }

    
        public function updateOrder($id, Request $request)
        {
 
            $order = Order::find($id);
        
            if ($order) {
                $order->status = $request->status;
                $order->save();
        
                return response()->json(['order' => $order,'success'=>'orden actualizada']);
            } else {
                return response()->json(['error'=>'orden no enconrtada']);
            }
        
            // $orders = Order::all();
        
            // return view('/orders.index', compact('orders', 'message'));
        }

        public function enviarOrden(Request $request, $id)
    {
        // Encuentra la orden por ID
        $order = Order::with('user')->findOrFail($id);
        
        // Actualiza el estado de la orden a "Enviado"
        $order->status = 'Enviado';
        $order->save();

        // Obtén los detalles de la orden
        $detailsOrder = DetailOrder::with('product')->where('id_order', $id)->get();

        // Obtén los datos del envío desde la solicitud
        $shippingCompany = $request->shippingCompany;
        $trackingNumber = $request->trackingNumber;

        // Envía el correo con los detalles del envío
        Mail::to($order->user->email)->send(new OrderUpdate($order, $shippingCompany, $trackingNumber, $detailsOrder));

        // Retorna una respuesta en formato JSON
        return response()->json(['message' => 'Correo enviado correctamente.']);
    }



}

