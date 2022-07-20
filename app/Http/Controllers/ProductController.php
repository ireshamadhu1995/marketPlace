<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Helpers\APIHelper;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //this will retrieve the product list of a logged user(seller)
        try {
            $seller = auth()->user();
           
            if(!$seller){
                return APIHelper::makeAPIResponse(false, "Error Occured", null, 400);

            } else{
                $products = auth()->user()->products;
                return APIHelper::makeAPIResponse(true, "Products List", $products, 200);
            }
            } catch (\Exception $e) {
            report($e);
            return APIHelper::makeAPIResponse(false, "Internal Service Error", null, 500);
            }

        }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProductRequest $request)
    {
     
        try {
            if(!auth()->user()){
                return APIHelper::makeAPIResponse(false, "Unauthenticated User", null, 401);
            } else {
                if($request->file('image')){
                    $file= $request->file('image');
                    $filename= date('YmdHi').$file->getClientOriginalName();
                    $file-> move(public_path('public/Image'), $filename);
                }
    
    
                $product = new Product();
                $product->name = $request->input('name');
                $product->price = $request->input('price');
                $product->stock = $request->input('stock');
                $product->img_path = public_path('public/Image').$filename;
                $product->description = $request->input('description');
                $product->save();
    
                $seller = auth()->user();
                $product->sellers()->attach($seller);
    
                $data = Product::with('sellers')->find($product->id);
    
                return APIHelper::makeAPIResponse(true, "Product added successfully", $data, 200);
    

            }
            


        } catch (\Exception $e) {
            report($e);
            return APIHelper::makeAPIResponse(false, "Internal Service Error", null, 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        if(!$product)
            return APIHelper::makeAPIResponse(false, "Product not found", null, 400);
       
        else{
            return APIHelper::makeAPIResponse(true, "Product detail", $product, 200);

        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, $id)
    {
        try {
            $selected_product = Product::find($id);
           
            if(!$selected_product)
                return APIHelper::makeAPIResponse(false, "Product not found", null, 400);

            else{
                $product_seller = $selected_product->sellers()->first();
                if($product_seller->id != auth()->user()->id){
                    return APIHelper::makeAPIResponse(false, "unauthenticated user", null, 401);
                } else{
                $img_path = $request->input('img_path');
                if($request->file('image')){
                    $file= $request->file('image');
                    $filename= date('YmdHi').$file->getClientOriginalName();
                    $file-> move(public_path('public/Image'), $filename);
                    $img_path = public_path('public/Image').$filename;
                }
                $product = Product::find($id);
                $product->name = $request->input('name');
                $product->price = $request->input('price');
                $product->stock = $request->input('stock');
                $product->img_path = $img_path;
                $product->description = $request->input('description');
                $product->save();

            
                $data = Product::with('sellers')->find($product->id);

                return APIHelper::makeAPIResponse(true, "Product updated successfully", $data, 200);

            }

            }
        

        } catch (\Exception $e) {
            report($e);
            return APIHelper::makeAPIResponse(false, "Internal Service Error", null, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            $product = Product::find($id);

            $data = $product;
            if(!$product)
                return APIHelper::makeAPIResponse(false, "Product not found", null, 400);

            $product_seller = $product->sellers()->first();
            if($product_seller->id != auth()->user()->id){
                return APIHelper::makeAPIResponse(false, "unauthenticated user", null, 401);
            } 

            else {
                $seller = auth()->user();
                $product->sellers()->detach($seller);
                $product->delete();
                return APIHelper::makeAPIResponse(true, "Product deleted successfully", $data, 200);
            
            }
 
        } catch (\Exception $e) {
            report($e);
            return APIHelper::makeAPIResponse(false, "Internal Service Error", null, 500);
        }
    }
}
