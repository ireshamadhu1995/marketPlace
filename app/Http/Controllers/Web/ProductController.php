<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\APIHelper;
use App\Models\Product;
use App\Models\User;

class ProductController extends Controller
{
    public function getDetailsOfAProduct($id)
    {
        $product = Product::find($id);
        if(!$product)
            return APIHelper::makeAPIResponse(false, "Product not found", null, 400);

        else{
            $data = Product::with('sellers')->find($id);
            return APIHelper::makeAPIResponse(true, "Product detail", $data, 200);

        }

    }

    public function getProductListBySeller($id)
    {
        $seller = User::find($id);
        if(!$seller)
            return APIHelper::makeAPIResponse(false, "Seller not found", null, 400);

        else{
            $data = User::with('products')->find($id);
            return APIHelper::makeAPIResponse(true, "Product Lists", $data, 200);

        }

    }

    public function getSellerDetailOfAProduct($id)
    {
        $product = Product::find($id);
        if(!$product)
            return APIHelper::makeAPIResponse(false, "Product not found", null, 400);

        else{
            $data = product::with('sellers')->find($id);
            return APIHelper::makeAPIResponse(true, "Seller Details", $data->sellers, 200);

        }

        

    }

    
    public function getAllProducts(Request $request)
    {
        $products = Product::with('sellers')->get();
        return APIHelper::makeAPIResponse(true, "All Product List", $products, 200);

    }

    public function getAllSellers(Request $request)
    {
        $sellers = User::get();
        return APIHelper::makeAPIResponse(true, "All Sellers List", $sellers, 200);

    }

    public function searchProductByName(Request $request)
    {
        $products = Product::query()
        ->where('name', 'LIKE', "%{$request->name}%")->with('sellers')
        ->get();
        return APIHelper::makeAPIResponse(true, "Searched Products", $products, 200);

    }

    public function getSameProductsOfMultipleSellers(Request $request)
    {
        $products = Product::query()
        ->where('name', 'LIKE', "{$request->name}")->with('sellers')
        ->get();
        return APIHelper::makeAPIResponse(true, "Searched Products", $products, 200);

    }
}
