<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\SsoReference\StoreDapilRequest;
use App\Http\Requests\SsoReference\UpdateDapilRequest;
use App\Services\SsoReferenceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SsoDapilController extends Controller
{
    public function __construct(
        private SsoReferenceService $ssoReferenceService
    ) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $dapils = $this->ssoReferenceService->getDapils($request->user());
            return ApiResponse::success($dapils, 'Daftar Dapil berhasil dimuat.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function show(Request $request, int $id): JsonResponse
    {
        try {
            $dapil = $this->ssoReferenceService->getDapilById($request->user(), $id);
            return ApiResponse::success($dapil, 'Detail Dapil berhasil dimuat.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function store(StoreDapilRequest $request): JsonResponse
    {
        try {
            $dapil = $this->ssoReferenceService->createDapil($request->user(), $request->validated());
            return ApiResponse::success($dapil, 'Dapil berhasil dibuat.', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function update(UpdateDapilRequest $request, int $id): JsonResponse
    {
        try {
            $dapil = $this->ssoReferenceService->updateDapil($request->user(), $id, $request->validated());
            return ApiResponse::success($dapil, 'Dapil berhasil diperbarui.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        try {
            $this->ssoReferenceService->deleteDapil($request->user(), $id);
            return ApiResponse::success(null, 'Dapil berhasil dihapus.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }
}
