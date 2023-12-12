<?php
// app/Services/MeuServico.php

namespace App\Services;
use App\Models\Price;
use App\Models\Product;

class Services
{
    public function live_prices(){
        $filePath = public_path('json/live_prices.json');
        if (file_exists($filePath)) {
            $jsonContent = file_get_contents($filePath);           
            $products = json_decode($jsonContent, true);
            return response()->json($products);
        }
    }
    public function get_prices_from_db(){
        $prices = Price::all();
    }

    public function getRealPrice($id, $account=null){
        $product = Product::find($id);
        $productPrice = $product->price;

        $pricesFromJson = $this->live_prices()->getData();
        if(!empty($account)){
            $results = array_filter($pricesFromJson, function($register) use ($product, $account)  {
                return $register->sku === $product->sku && $account->external_reference === $product->account;
            });
        }else{
            $results = array_filter($pricesFromJson, function($register) use ($product)  {
                return $register->sku === $product->sku;
            });
        }
        $results = array_values($results);
        $lessPrice = null;

        foreach ($results as $result) {
            $actualPrice = $result->price;

            if ($lessPrice === null || $actualPrice < $lessPrice) {
                $lessPrice = $actualPrice;
            }
        }

        if ($lessPrice !== null) {
            $productPrice= $lessPrice;
        } else {
            
            $price = Price::where('product_id', $product->id)->min('value');
            $productPrice = $price;
            
        }
        if($productPrice == 0){
            $productPrice = $product->price;
        }
        $productPrice = number_format($productPrice, 2, '.', '');
        
        return $productPrice;
        

    }
}
