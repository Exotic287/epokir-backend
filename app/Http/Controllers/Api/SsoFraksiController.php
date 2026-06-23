<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\SsoReference\StoreFraksiRequest;
use App\Http\Requests\SsoReference\UpdateFraksiRequest;
use App\Services\SsoReferenceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SsoFraksiController extends Controller
{
    public function __construct(
        private SsoReferenceService $ssoReferenceService
    ) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $fraksis = $this->ssoReferenceService->getFraksis($request->user());
            return ApiResponse::success($fraksis, 'Daftar Fraksi berhasil dimuat.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function show(Request $request, int $id): JsonResponse
    {
        try {
            $fraksi = $this->ssoReferenceService->getFraksiById($request->user(), $id);
            return ApiResponse::success($fraksi, 'Detail Fraksi berhasil dimuat.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function store(StoreFraksiRequest $request): JsonResponse
    {
        try {
            $fraksi = $this->ssoReferenceService->createFraksi($request->user(), $request->validated());
            return ApiResponse::success($fraksi, 'Fraksi berhasil dibuat.', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function update(UpdateFraksiRequest $request, int $id): JsonResponse
    {
        try {
            $fraksi = $this->ssoReferenceService->updateFraksi($request->user(), $id, $request->validated());
            return ApiResponse::success($fraksi, 'Fraksi berhasil diperbarui.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        try {
            $this->ssoReferenceService->deleteFraksi($request->user(), $id);
            return ApiResponse::success(null, 'Fraksi berhasil dihapus.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }
}
