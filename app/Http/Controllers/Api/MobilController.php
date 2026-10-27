<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MobilResource;
use App\Models\Mobil;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\Rule;

class MobilController extends Controller
{
    /**
     * Nilai yang diizinkan untuk kolom enum.
     */
    private const TRANSMISI = ['Manual', 'Matic', 'CVT'];

    private const BAHAN_BAKAR = ['Bensin', 'Diesel', 'Hybrid', 'Listrik'];

    private const STATUS = ['tersedia', 'sold'];

    /**
     * GET /api/mobil
     *
     * Menampilkan daftar mobil, mendukung pencarian, penyaringan,
     * pengurutan, dan pagination.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        // Kolom pengurutan dibatasi (whitelist) agar tidak bisa diinjeksi lewat query string.
        $sortBy = in_array($request->query('sort_by'), ['id', 'nama', 'brand', 'harga', 'tahun', 'created_at'], true)
            ? $request->query('sort_by')
            : 'id';

        $sortDir = $request->query('sort_dir') === 'asc' ? 'asc' : 'desc';

        $mobils = Mobil::query()
            ->search($request->query('search'))
            ->filter($request->only(['brand', 'status', 'bahan_bakar']))
            ->orderBy($sortBy, $sortDir)
            ->paginate(min(max($request->integer('per_page', 10), 1), 100));

        return MobilResource::collection($mobils)
            ->additional(['message' => 'Daftar mobil berhasil diambil']);
    }

    /**
     * POST /api/mobil
     *
     * Menyimpan unit mobil baru.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'brand' => ['required', 'string', 'max:100'],
            'harga' => ['required', 'integer', 'min:0'],
            'tahun' => ['required', 'integer', 'min:1900', 'max:'.((int) date('Y') + 1)],
            'transmisi' => ['required', Rule::in(self::TRANSMISI)],
            'bahan_bakar' => ['required', Rule::in(self::BAHAN_BAKAR)],
            'status' => ['sometimes', 'nullable', Rule::in(self::STATUS)],
            'foto' => ['nullable', 'url', 'max:2048'],
        ], $this->messages());

        $mobil = Mobil::create($validated);

        return (new MobilResource($mobil))
            ->additional(['message' => 'Data mobil berhasil ditambahkan'])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * GET /api/mobil/{mobil}
     *
     * Menampilkan detail satu unit mobil.
     */
    public function show(Mobil $mobil): JsonResponse
    {
        return (new MobilResource($mobil))
            ->additional(['message' => 'Detail mobil berhasil diambil'])
            ->response()
            ->setStatusCode(200);
    }

    /**
     * PUT/PATCH /api/mobil/{mobil}
     *
     * Memperbarui data mobil yang sudah ada.
     */
    public function update(Request $request, Mobil $mobil): JsonResponse
    {
        $validated = $request->validate([
            'nama' => ['sometimes', 'string', 'max:150'],
            'brand' => ['sometimes', 'string', 'max:100'],
            'harga' => ['sometimes', 'integer', 'min:0'],
            'tahun' => ['sometimes', 'integer', 'min:1900', 'max:'.((int) date('Y') + 1)],
            'transmisi' => ['sometimes', Rule::in(self::TRANSMISI)],
            'bahan_bakar' => ['sometimes', Rule::in(self::BAHAN_BAKAR)],
            'status' => ['sometimes', 'nullable', Rule::in(self::STATUS)],
            'foto' => ['sometimes', 'nullable', 'url', 'max:2048'],
        ], $this->messages());

        $mobil->update($validated);

        return (new MobilResource($mobil->refresh()))
            ->additional(['message' => 'Data mobil berhasil diperbarui'])
            ->response()
            ->setStatusCode(200);
    }

    /**
     * DELETE /api/mobil/{mobil}
     *
     * Menghapus unit mobil dari basis data.
     */
    public function destroy(Mobil $mobil): JsonResponse
    {
        $mobil->delete();

        return response()->json([
            'message' => 'Data mobil berhasil dihapus',
            'data' => null,
        ], 200);
    }

    /**
     * Pesan validasi dalam Bahasa Indonesia.
     *
     * @return array<string, string>
     */
    private function messages(): array
    {
        return [
            'nama.required' => 'Kolom nama mobil wajib diisi.',
            'nama.string' => 'Kolom nama mobil harus berupa teks.',
            'nama.max' => 'Kolom nama mobil maksimal 150 karakter.',
            'brand.required' => 'Kolom brand wajib diisi.',
            'brand.max' => 'Kolom brand maksimal 100 karakter.',
            'harga.required' => 'Kolom harga wajib diisi.',
            'harga.integer' => 'Kolom harga harus berupa angka.',
            'harga.min' => 'Kolom harga tidak boleh negatif.',
            'tahun.required' => 'Kolom tahun wajib diisi.',
            'tahun.integer' => 'Kolom tahun harus berupa angka.',
            'tahun.min' => 'Kolom tahun tidak valid.',
            'tahun.max' => 'Kolom tahun tidak boleh melebihi tahun depan.',
            'transmisi.required' => 'Kolom transmisi wajib diisi.',
            'transmisi.in' => 'Kolom transmisi harus salah satu dari: Manual, Matic, CVT.',
            'bahan_bakar.required' => 'Kolom bahan bakar wajib diisi.',
            'bahan_bakar.in' => 'Kolom bahan bakar harus salah satu dari: Bensin, Diesel, Hybrid, Listrik.',
            'status.in' => 'Kolom status harus salah satu dari: tersedia, sold.',
            'foto.url' => 'Kolom foto harus berupa URL yang valid.',
            'foto.max' => 'Kolom foto maksimal 2048 karakter.',
        ];
    }
}
