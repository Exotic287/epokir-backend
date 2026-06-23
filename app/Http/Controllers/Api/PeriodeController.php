<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Periode\StorePeriodeRequest;
use App\Http\Requests\Periode\UpdatePeriodeRequest;
use App\Services\PeriodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PeriodeController extends Controller
{
    public function __construct(private PeriodeService $periodeService) {}

    public function index(): JsonResponse
    {
        try {
            $data = $this->periodeService->getAll();
            return ApiResponse::success($data, 'Daftar periode berhasil dimuat.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function active(): JsonResponse
    {
        try {
            $data = $this->periodeService->getActive();
            return ApiResponse::success($data, 'Periode aktif berhasil dimuat.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function store(StorePeriodeRequest $request): JsonResponse
    {
        try {
            $periode = $this->periodeService->create($request->validated(), $request->user()?->name);
            return ApiResponse::success($periode, 'Periode berhasil dibuat.', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function update(UpdatePeriodeRequest $request, int $id): JsonResponse
    {
        try {
            $periode = $this->periodeService->update($id, $request->validated());
            return ApiResponse::success($periode, 'Periode berhasil diperbarui.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function freeze(int $id): JsonResponse
    {
        try {
            $periode = $this->periodeService->freeze($id);
            return ApiResponse::success($periode, 'Periode berhasil di-freeze.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function activate(int $id): JsonResponse
    {
        try {
            $periode = $this->periodeService->activate($id);
            return ApiResponse::success($periode, 'Periode berhasil diaktifkan kembali.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function deactivate(Request $request, int $id): JsonResponse
    {
        try {
            $periode = $this->periodeService->deactivate($id, $request->input('alasan'));
            return ApiResponse::success($periode, 'Periode berhasil dinonaktifkan.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }
}
