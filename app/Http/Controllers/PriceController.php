<?php

namespace App\Http\Controllers;
use League\Csv\Reader;
use App\Models\Price;
use App\Models\Product;
use App\Models\Account;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Services\Services;

class PriceController extends Controller
{
    public function __construct(public Services $services)
    {
        
    }
    public function read_prices(){
        $filePath = public_path('csv/import.csv');
        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0); // If your CSV has headers

        $records = $csv->getRecords();

        foreach ($records as $record) {
            $product = Product:: where('sku', $record['sku'])->first();
            
            $price = Price::where('product_id', $product->id)->first();
            if(empty($price)){
                $price = new Price();
                $price->product_id = $product->id;
                $account = Account::where('external_reference', $record['account_ref'])->first();
                if(!empty($account)){
                    $price->account_id = $account->id;
                }
                $user = Account::where('external_reference', $record['user_ref'])->first();
                if(!empty($user)){
                    $price->user_id = $user->id;
                }
                $price->quantity = $record['quantity'];
                $price->value = $record['value'];
                $price->save();
            }
        }
    }

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
    public function find_prices(Request $request){
        
        $received = $request->getContent();
        $dataJson = json_decode($received, true);
       
        $account_id = $dataJson['account'];
        $account = null;
        if(!empty($account_id)){
            $account = Account::where('external_reference', $account_id)->first();
        }
        $products_finder = $dataJson['products']; 
        $productsArray = [];
        foreach($products_finder as $product){
            $productFind = Product::where('sku',$product)->get();
            foreach($productFind as $prd){
                $productsArray[] = ['sku' => $prd->sku, 'price'=> $this->services->getRealPrice($prd->id, $account)];
            }
        }
        return $productsArray;
    }

    
}
