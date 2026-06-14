<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Kecamatan\StoreKecamatanRequest;
use App\Http\Requests\Kecamatan\UpdateKecamatanRequest;
use App\Services\MasterDataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KecamatanController extends Controller
{
    public function __construct(private MasterDataService $masterDataService) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $dapilId = $request->query('dapil_id') ? (int) $request->query('dapil_id') : null;
            $data    = $this->masterDataService->getAllKecamatan($dapilId);
            return ApiResponse::success($data, 'Daftar Kecamatan berhasil dimuat.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $kecamatan = $this->masterDataService->findKecamatan($id);
            return ApiResponse::success($kecamatan, 'Detail Kecamatan berhasil dimuat.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function store(StoreKecamatanRequest $request): JsonResponse
    {
        try {
            $kecamatan = $this->masterDataService->createKecamatan($request->validated());
            return ApiResponse::success($kecamatan, 'Kecamatan berhasil dibuat.', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function update(UpdateKecamatanRequest $request, int $id): JsonResponse
    {
        try {
            $kecamatan = $this->masterDataService->updateKecamatan($id, $request->validated());
            return ApiResponse::success($kecamatan, 'Kecamatan berhasil diperbarui.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->masterDataService->deleteKecamatan($id);
            return ApiResponse::success(null, 'Kecamatan berhasil dihapus.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }
}
