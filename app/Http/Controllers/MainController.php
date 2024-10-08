<?php

namespace App\Http\Controllers;

use auth;
use App\Models\Cart;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class MainController extends Controller
{
    function home()
    {
        $products = Product::latest()->take(12)->get();
        $featuredProducts = $products->where('featured', true);

        // dd($products);

        return view('homepage', compact('featuredProducts', 'products'));
    }


    function addToCart($id)
    {

        // $isExists = Cart::where('user_id', auth()->id())->where('product_id', $id)->exists();

        // if ($isExists) {
        //     $cart = Cart::where('product_id', $id)->where('user_id', auth()->id())->first();
        //     $cart->qty += 1;
        //     $cart->save();
        // } else {
        //     $cart = new Cart();
        //     $cart->user_id = auth()->id();
        //     $cart->product_id = $id;
        //     $cart->qty = 1;
        //     $cart->save();
        // }

        $isExists = Cart::where(['product_id' => $id, 'user_id' => auth()->user()->id])->first();

        $products = Stock::where('product_id', $id)->first();
        if ($isExists) {
            if ($products->stock > 0 && $isExists->qty < $products->stock) {
                $cart = Cart::where(['product_id' => $id, 'user_id' => auth()->user()->id])->first();
                $cart->qty = $cart->qty + 1;
                $cart->save();
            }
        } else {
            $cart = new Cart();
            $cart->qty = 1;
            $cart->product_id = $id;
            $cart->user_id = auth()->id();
            $cart->save();
        }



        return back();
    }


    // function productCount() {
    //    $test = Cart::select('id','qty')->get();
    //    dd($test);
    // }


    function productShow($id)
    {
        $product = Product::with(['categories:id', 'stocks'])->findOrFail($id);
        $categorIds = $product->categories->pluck('id');


        $filteredProducts = Product::whereNot('id', $id)->whereHas('categories', function ($query) use ($categorIds) {
            $query->whereIn('category_id', $categorIds);
        })->get();


        return view('frontend.productview', compact('product', 'filteredProducts'));
    }


    function contactus()
    {

        return view('contactus');
    }
    function aboutus()
    {

        return view('aboutus');
    }
    function userProfile()
    {
        return view('frontend.userProfile');
    }


    function categoryArcheive($id)
    {
        $products = Product::whereHas('categories', function ($query) use ($id) {
            $query->where('category_id', $id);
        })->get();
        $category  = Category::find($id, ['id', 'title']);
        return view('frontend.CategoryArcheive', compact('products', 'category'));
    }



    public function cartDetails()
    {
        $cartDetails = Cart::where('user_id', auth()->user()->id)->with('products')->get();


        // dd($cartDetails);
        return view('frontend.cartDetails', compact('cartDetails'));
    }



    public function cartDetailsUpdate(Request $request)
    {

        foreach ($request->qty as $key => $cartItem) {
            $cart = Cart::find($key);
            $products = Stock::where('product_id', $cart->product_id)->first();
            // dd($cartItem);
            if ($cartItem <= $products->stock) {
                $cart->qty = $cartItem;
                $cart->save();
            } else {
                return back()->with('msg', 'Low on stock, Please order later!');
            }
        }
        return back();
        //  dd($request->all());
    }


    public function cartDetailsDelete($id)
    {
        $cart = Cart::find($id)->delete();
        return back();
    }
}
