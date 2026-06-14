<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Opd\StoreOpdRequest;
use App\Http\Requests\Opd\UpdateOpdRequest;
use App\Services\MasterDataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OpdController extends Controller
{
    public function __construct(private MasterDataService $masterDataService) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $activeOnly = filter_var($request->query('active_only', true), FILTER_VALIDATE_BOOLEAN);
            $data = $this->masterDataService->getAllOpd($activeOnly);
            return ApiResponse::success($data, 'Daftar OPD berhasil dimuat.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $opd = $this->masterDataService->findOpd($id);
            return ApiResponse::success($opd, 'Detail OPD berhasil dimuat.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function store(StoreOpdRequest $request): JsonResponse
    {
        try {
            $opd = $this->masterDataService->createOpd($request->validated());
            return ApiResponse::success($opd, 'OPD berhasil dibuat.', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function update(UpdateOpdRequest $request, int $id): JsonResponse
    {
        try {
            $opd = $this->masterDataService->updateOpd($id, $request->validated());
            return ApiResponse::success($opd, 'OPD berhasil diperbarui.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->masterDataService->deleteOpd($id);
            return ApiResponse::success(null, 'OPD berhasil dihapus.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }
}
