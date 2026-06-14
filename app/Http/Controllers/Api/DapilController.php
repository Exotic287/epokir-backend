<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dapil\StoreDapilRequest;
use App\Http\Requests\Dapil\UpdateDapilRequest;
use App\Services\MasterDataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DapilController extends Controller
{
    public function __construct(private MasterDataService $masterDataService) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $activeOnly = filter_var($request->query('active_only', true), FILTER_VALIDATE_BOOLEAN);
            $data = $this->masterDataService->getAllDapil($activeOnly);
            return ApiResponse::success($data, 'Daftar Dapil berhasil dimuat.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $dapil = $this->masterDataService->findDapil($id);
            return ApiResponse::success($dapil, 'Detail Dapil berhasil dimuat.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function store(StoreDapilRequest $request): JsonResponse
    {
        try {
            $dapil = $this->masterDataService->createDapil($request->validated());
            return ApiResponse::success($dapil, 'Dapil berhasil dibuat.', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function update(UpdateDapilRequest $request, int $id): JsonResponse
    {
        try {
            $dapil = $this->masterDataService->updateDapil($id, $request->validated());
            return ApiResponse::success($dapil, 'Dapil berhasil diperbarui.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->masterDataService->deleteDapil($id);
            return ApiResponse::success(null, 'Dapil berhasil dihapus.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }
}
