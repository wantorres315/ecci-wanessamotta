<?php

namespace App\Http\Controllers;
use App\Http\Resources\ProductsResource;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    

   
    public function index(){
        $products = ProductsResource::collection(
            Product::orderBy("created_at","desc")->paginate(12),
        ); 
        return Inertia::render('Dashboard', [
            'products' => $products,
        ]);
        
    }

    
    public function show($id){
        return $id;
    }
}
