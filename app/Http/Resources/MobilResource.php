<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MobilResource extends JsonResource
{
    /**
     * Transform resource menjadi representasi array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'brand' => $this->brand,
            'harga' => (int) $this->harga,
            'harga_format' => 'Rp '.number_format((int) $this->harga, 0, ',', '.'),
            'tahun' => (int) $this->tahun,
            'transmisi' => $this->transmisi,
            'bahan_bakar' => $this->bahan_bakar,
            'status' => $this->status,
            'foto' => $this->foto,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
