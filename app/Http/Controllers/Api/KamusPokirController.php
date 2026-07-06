<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\KamusPokir\StoreKamusPokirRequest;
use App\Http\Requests\KamusPokir\UpdateKamusPokirRequest;
use App\Services\KamusPokirService;
use Illuminate\Http\JsonResponse;

class KamusPokirController extends Controller
{
    public function __construct(private KamusPokirService $kamusPokirService) {}

    public function index(): JsonResponse
    {
        try {
            return ApiResponse::success($this->kamusPokirService->getAll(), 'Daftar Kamus Pokir berhasil dimuat.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    /**
     * GET /api/v1/kamus-pokir/categories — untuk picker Pokir (Dewan).
     */
    public function categories(): JsonResponse
    {
        try {
            return ApiResponse::success($this->kamusPokirService->getCategories(), 'Kategori Kamus Pokir berhasil dimuat.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            return ApiResponse::success($this->kamusPokirService->find($id), 'Detail Kamus Pokir berhasil dimuat.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function store(StoreKamusPokirRequest $request): JsonResponse
    {
        try {
            $data = $this->kamusPokirService->create($request->validated());
            return ApiResponse::success($data, 'Kamus Pokir berhasil dibuat.', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function update(UpdateKamusPokirRequest $request, int $id): JsonResponse
    {
        try {
            $data = $this->kamusPokirService->update($id, $request->validated());
            return ApiResponse::success($data, 'Kamus Pokir berhasil diperbarui.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->kamusPokirService->delete($id);
            return ApiResponse::success(null, 'Kamus Pokir berhasil dihapus.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    /**
     * PATCH /api/v1/kamus-pokir/{id}/toggle-active
     */
    public function toggleActive(int $id): JsonResponse
    {
        try {
            $data = $this->kamusPokirService->toggleActive($id);
            return ApiResponse::success($data, 'Status Kamus Pokir berhasil diperbarui.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }
}
