<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderedProduct;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;




class MainController extends Controller
{

   

    public function invoice($id)
    {
        $id=base64_decode($id);

        $order=Order::find($id);

       // return view('invoice',compact('order'));

        $pdf=PDF::loadView('invoice', compact('order'));
        return $pdf->stream("invoice.pdf");
    }

    public function deleteOrder(Request $request,$id)
    {

        try{

            $id=base64_decode($id);

            OrderedProduct::whereOrderId($id)->delete();
            Order::whereId($id)->delete();
    
            $request->session()->flash("success","Order deleted");

        }

        catch(\Exception $e)
        {
            $request->session()->flash("error","Not deleted");
        }

       

        return redirect()->back();
    }

    public function updateOrder(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'order_id' => 'required|numeric',
                'customer_name' => 'required',
                'customer_phone' => 'required',
                'product.*' => 'required|numeric',
                'qty.*' => 'required|numeric'
            ]);

            if ($validator->fails()) {
                $response['status'] = "error";
                $response['message'] = implode("", $validator->errors()->all());

                return response()->json($response);
            } else {

                $amount = 0;

                $products_array = array();
                $qty_array = array();

                foreach ($request->product as $op) {
                    array_push($products_array, $op);
                }

                foreach ($request->qty as $q) {
                    array_push($qty_array, $q);
                }

                OrderedProduct::whereOrderId($request->order_id)->delete();

                foreach ($products_array as $key => $p) {
                    $product = Product::find($p);
                    $op = new OrderedProduct;
                    $op->order_id = $request->order_id;
                    $op->product_id = $p;
                    $op->qty = $qty_array[$key];
                    $op->amount = ($product->price * $qty_array[$key]);
                    $op->save();

                    $amount=$amount+$op->amount;
                }

                $order=Order::find($request->order_id);
                $order->customer_name=$request->customer_name;
                $order->customer_phone=$request->customer_phone;
                $order->net_amount=$amount;
                $order->save();

                $response['status']="success";
                $response['message']="Order updated";
                $request->session()->flash('success','Order updated successfully');

                return response()->json($response);
            }
        } catch (\Exception $e) {
            $response['status'] = "error";
            $response['message'] = "Something went wrong";

            return response()->json($response);
        }
    }

    public function editOrder($id)
    {
        $id = base64_decode($id);

        $order = Order::find($id);

        $ordered_products = OrderedProduct::whereOrderId($id)->get();

        $products_array = array();
        $qty_array = array();

        foreach ($ordered_products as $key => $op) {
            array_push($products_array, $op->product_id);
            array_push($qty_array, $op->qty);
        }

        $products = $this->products();

        return view('edit_order', compact('order', 'ordered_products', 'products_array', 'qty_array', 'products'));
    }

    public function storeOrder(Request $request)
    {

        try {

            $validator = Validator::make($request->all(), [
                'customer_name' => 'required|string',
                'customer_phone' => 'required|string',
                'product.*' => 'required|numeric',
                'qty.*' => 'required|numeric'
            ]);

            if ($validator->fails()) {
                $response['status'] = "error";
                $response['message'] = implode("", $validator->errors()->all());

                return response()->json($response);
            } else {

                if (Order::get()->count() == 0) {
                    $order_id = 1000;
                } else {

                    $last_order = Order::OrderByDesc('id')->first();

                    $order_id = $last_order->order_id + 1;
                }

                $order = new Order;
                $order->order_id = $order_id;
                $order->customer_name = $request->customer_name;
                $order->customer_Phone = $request->customer_phone;
                $order->net_amount = 0;
                $order->order_date = date('Y-m-d H:i:s');
                $order->save();

                $products_array = array();
                $qty_array = array();

                foreach ($request->product as $op) {
                    array_push($products_array, $op);
                }

                foreach ($request->qty as $q) {
                    array_push($qty_array, $q);
                }

                $amount = 0;

                foreach ($products_array as $key => $op) {

                    $product_details = Product::find($products_array[$key]);
                    $product = new OrderedProduct;
                    $product->order_id = $order->id;
                    $product->product_id = $products_array[$key];
                    $product->qty = $qty_array[$key];
                    $product->amount = ($product_details->price * $qty_array[$key]);
                    $product->save();

                    $amount = $amount + $product->amount;
                }

                Order::where('id', $order->id)->update(['net_amount' => $amount]);

                if ($order->id > 0) {
                    $response['status'] = "success";
                    $response['message'] = "Order added";
                    $request->session()->flash("status", "Order added successfully");
                    return response()->json($response);
                }
            }
        } catch (\Exception $e) {
            $response['message'] = "Something went wrong";
            $response['status'] = "error";

            return response()->json($response);
        }
    }

    public function products()
    {
        return Product::all();
    }

    public function addOrder()
    {

        return view('add_order', ['products' => $this->products()]);
    }

    public function orders()
    {
        $orders = Order::all();

        return view('orders', compact('orders'));
    }
    public function productList()
    {
        $products = Product::whereIsActive(1)->get();

        return view('products', compact('products'));
    }

    public function addProduct()
    {

        $categories = $this->activeCategories();
        return view('add_product', compact('categories'));
    }

    public function activeCategories()
    {
        return Category::whereIsActive(1)->get();
    }

    public function storeProduct(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:products',
            'image' => 'required|image|file|mimes:jpeg,jpg,png',
            'price' => 'required',
            'category' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            $request->session()->flash('error', implode("", $validator->errors()->all()));

            return redirect()->back()->withInput();
        } else {

            if ($request->hasFile('image')) {

                $file_name = rand(9999, 1000) . "_" . date("Y-M-d-H-i-s-h-a") . "." . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->storeAs('products', $file_name, 'public');
            } else {

                $file_name = "NoFile";
            }

            $product = new Product;
            $product->name = $request->name;
            $product->category = $request->category;
            $product->image = $file_name;
            $product->price = $request->price;
            $product->save();

            if ($product->id > 0) {
                $request->session()->flash('success', 'Product added successfully');

                return redirect()->back();
            } else {

                $request->session()->flash('error', 'Product not added');

                return redirect()->back()->withInput();
            }
        }
    }

    public function editProduct($id)
    {
        $id = base64_decode($id);

        $product = Product::find($id);
        $categories = $this->activeCategories();

        return view('edit_Product', compact('product', 'categories'));
    }

    public function updateProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|numeric',
            'name' => 'required|unique:products,name,' . $request->product_id,
            'price' => 'required',
            'category' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            $request->session()->flash('error', implode("", $validator->errors()->all()));

            return redirect()->back();
        } else {

            $product = Product::find($request->product_id);


            if ($request->hasFile('image')) {

                if (file_exists(storage_path("app/public/products/" . $product->image))) {
                    unlink(storage_path("app/public/products/" . $product->image));
                }


                $file_name = rand(1000, 9999) . "_" . date('Y-M-d-H-i-s-h-a') . "." . $request->file('image')->getClientOriginalExtension();

                $request->file('image')->storeAs('products', $file_name, 'public');
            } else {

                $file_name = $product->image;
            }

            Product::where('id', $request->product_id)->update([
                'name' => $request->name,
                'image' => $file_name,
                'category' => $request->category,
                'price' => $request->price
            ]);

            $request->session()->flash('success', "Product updated successfully");

            return redirect()->back();
        }
    }

    public function deleteProduct(Request $request, $id)
    {

        try{

            $id = base64_decode($id);

            $product = Product::find($id);
    
            if (file_exists(storage_path("app/public/products/" . $product->image))) {
                unlink(storage_path("app/public/products/" . $product->image));
            }
    
            Product::whereId($id)->delete();
    
            $request->session()->flash('success', "Product deleted");

        }

        catch(\Exception $e)
        {
            $request->session()->flash('error','Not deleted May be added to orders');
        }
       

        return redirect()->back();
    }
}
