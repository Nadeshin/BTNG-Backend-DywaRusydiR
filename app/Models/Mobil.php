<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mobil extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang dipakai model ini.
     *
     * @var string
     */
    protected $table = 'mobils';

    /**
     * Atribut yang boleh diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'brand',
        'harga',
        'tahun',
        'transmisi',
        'bahan_bakar',
        'status',
        'foto',
    ];

    /**
     * Casting tipe data agar konsisten saat dikirim sebagai JSON.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'harga' => 'integer',
            'tahun' => 'integer',
        ];
    }

    /**
     * Scope pencarian berdasarkan nama atau brand.
     */
    public function scopeSearch(Builder $query, ?string $keyword): Builder
    {
        if (blank($keyword)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($keyword) {
            $q->where('nama', 'like', "%{$keyword}%")
                ->orWhere('brand', 'like', "%{$keyword}%");
        });
    }

    /**
     * Scope penyaringan berdasarkan brand, status, dan bahan bakar.
     *
     * @param  array{brand?: string, status?: string, bahan_bakar?: string}  $filters
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['brand'] ?? null, fn (Builder $q, $brand) => $q->where('brand', $brand))
            ->when($filters['status'] ?? null, fn (Builder $q, $status) => $q->where('status', $status))
            ->when($filters['bahan_bakar'] ?? null, fn (Builder $q, $bahan) => $q->where('bahan_bakar', $bahan));
    }
}
