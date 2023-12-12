<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\Services;
use App\Models\Account;

class ProductsResource extends JsonResource
{
     /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $account = Account::find(auth()->user()->id);
        $services = app(Services::class);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'sku' => $this->sku,
            'price' => $services->getRealPrice($this->id, $account),
            'oldPrice' => $this->price,
            'imageUrl' => 'https://picsum.photos/200/300?random='.$this->id
        ];
    }
}
