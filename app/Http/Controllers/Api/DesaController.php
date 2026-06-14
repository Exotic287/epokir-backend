<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Desa\StoreDesaRequest;
use App\Http\Requests\Desa\UpdateDesaRequest;
use App\Services\MasterDataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DesaController extends Controller
{
    public function __construct(private MasterDataService $masterDataService) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $kecamatanId = $request->query('kecamatan_id') ? (int) $request->query('kecamatan_id') : null;
            $dapilId     = $request->query('dapil_id') ? (int) $request->query('dapil_id') : null;
            $data        = $this->masterDataService->getAllDesa($kecamatanId, $dapilId);
            return ApiResponse::success($data, 'Daftar Desa berhasil dimuat.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $desa = $this->masterDataService->findDesa($id);
            return ApiResponse::success($desa, 'Detail Desa berhasil dimuat.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function store(StoreDesaRequest $request): JsonResponse
    {
        try {
            $desa = $this->masterDataService->createDesa($request->validated());
            return ApiResponse::success($desa, 'Desa berhasil dibuat.', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function update(UpdateDesaRequest $request, int $id): JsonResponse
    {
        try {
            $desa = $this->masterDataService->updateDesa($id, $request->validated());
            return ApiResponse::success($desa, 'Desa berhasil diperbarui.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->masterDataService->deleteDesa($id);
            return ApiResponse::success(null, 'Desa berhasil dihapus.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }
}
