<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProdukResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'desa_id' => $this->desa_id,
            'nama_produk' => $this->nama_produk,
            'harga' => $this->harga,
            'created_at' => $this->created_at,
            'updated_at' => $this->created_at,
            'photo' => PhotoResource::collection($this->photo)
        ];
    }
}