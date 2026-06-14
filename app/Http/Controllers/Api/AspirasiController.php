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
                'opd_id', 'dapil_id', 'source', 'is_complete', 'search', 'tahun', 'per_page',
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
}
