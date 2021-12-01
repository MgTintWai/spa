<?php

namespace App\Http\Controllers;

use App\Models\Product;

use Illuminate\Http\Request;

use App\Http\Requests\ProductStoreRequest;

use App\Http\Requests\ProductUpdateRequest;


class ProductController extends Controller

{

    public function index()
    {
        // For Search
        // $products = Product::query();
        // if(request('search')){
        //     return $products->where('name','like','%' . request('search') . '%')
        //     ->orderBy('id','desc')->get();
        // }else{
        //     return Product::orderBy('id','desc')->get();
        // }
        // End Search

        // Next Way For Search
        
        return Product::when(request('search'),function($query){
            $query->where('name','like','%' . request('search') . '%');
        })->orderBy('id','desc')->paginate(5);
        
        // End Search

        // return Product::orderBy('id','desc')->get();

        // For Pagination

        // return Product::orderBy('id','desc')->paginate(5);

        // return Product::all();

    }

    public function store(ProductStoreRequest $request)

    {
        $product = Product::create($request->only('name','price'));

        return $product;
    }

    // Instead $id ,we can use [Product $product]

    public function show(Product $product)
    {
        // $product = Product::find($id);
        
        return $product ;
    }

    public function update(ProductUpdateRequest $request, Product $product)
    {
        // $product = Product::find($id);

        $product->update($request->only('name','price'));

        return $product;
    }

    public function destroy(Product $product)
    {
        // $product = Product::find($id);
        $product->delete();

        return $product;
    }
}
