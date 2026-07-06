<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Aspirasi\StoreAspirasiRequest;
use App\Http\Requests\Aspirasi\UpdateAspirasiRequest;
use App\Services\AspirasiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AspirasiController extends Controller
{
    public function __construct(
        private AspirasiService $aspirasiService
    ) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $data = $this->aspirasiService->index($request->user(), $request->only([
                'opd_id', 'dapil_id', 'source', 'is_complete', 'is_used_in_pokir', 'status',
                'kecamatan', 'desa', 'urusan', 'created_by', 'search', 'tahun', 'per_page',
            ]));
            return ApiResponse::success($data, 'Daftar aspirasi berhasil dimuat.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function store(StoreAspirasiRequest $request): JsonResponse
    {
        try {
            $aspirasi = $this->aspirasiService->store($request->user(), $request->validated());
            return ApiResponse::success($aspirasi, 'Aspirasi berhasil dibuat.', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function show(Request $request, int $id): JsonResponse
    {
        try {
            $aspirasi = $this->aspirasiService->show($request->user(), $id);
            return ApiResponse::success($aspirasi, 'Detail aspirasi berhasil dimuat.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function update(UpdateAspirasiRequest $request, int $id): JsonResponse
    {
        try {
            $aspirasi = $this->aspirasiService->update($request->user(), $id, $request->validated());
            return ApiResponse::success($aspirasi, 'Aspirasi berhasil diperbarui.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        try {
            $this->aspirasiService->destroy($request->user(), $id);
            return ApiResponse::success(null, 'Aspirasi berhasil dihapus.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    /**
     * GET /api/v1/aspirasi/available
     * Aspirasi yang belum digunakan di Pokir manapun (untuk dipilih saat menyusun Pokir baru).
     */
    public function available(Request $request): JsonResponse
    {
        try {
            $data = $this->aspirasiService->available($request->user(), $request->only(['q', 'urusan']));
            return ApiResponse::success($data, 'Aspirasi tersedia berhasil dimuat.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function tabCounts(Request $request): JsonResponse
    {
        try {
            // Tanpa wrapper ApiResponse — kontraknya AspirasiTabCounts mentah (lihat aspirasi.service.ts)
            $counts = $this->aspirasiService->tabCounts($request->user(), $request->only(['created_by', 'tahun']));
            return response()->json($counts);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function bulkArchive(Request $request): JsonResponse
    {
        $request->validate([
            'ids'   => ['required', 'array', 'min:1'],
            'ids.*' => ['integer'],
        ]);

        try {
            $count = $this->aspirasiService->bulkArchive($request->user(), $request->input('ids'));
            return ApiResponse::success(['count' => $count], "{$count} aspirasi berhasil dipindahkan ke arsip kerja.");
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }
}
