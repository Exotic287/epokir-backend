<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\KamusUsulan\StoreKamusUsulanRequest;
use App\Http\Requests\KamusUsulan\UpdateKamusUsulanRequest;
use App\Services\KamusUsulanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KamusUsulanController extends Controller
{
    public function __construct(private KamusUsulanService $service) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $mode = $request->query('mode', 'grouped');
            $filters = [
                'q' => $request->query('q'),
                'skema' => $request->query('skema'),
                'status' => $request->query('status'),
            ];

            $result = $mode === 'flat'
                ? $this->service->getFlat($filters)
                : $this->service->getGrouped($filters);

            return response()->json($result);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function bidangUrusan(): JsonResponse
    {
        try {
            $data = $this->service->getBidangUrusanList();
            return ApiResponse::success($data, 'Daftar bidang urusan berhasil dimuat.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function store(StoreKamusUsulanRequest $request): JsonResponse
    {
        try {
            $item = $this->service->create($request->validated());
            return ApiResponse::success($item, 'Item kamus berhasil ditambahkan.', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function update(UpdateKamusUsulanRequest $request, int $id): JsonResponse
    {
        try {
            $item = $this->service->update($id, $request->validated());
            return ApiResponse::success($item, 'Item kamus berhasil diperbarui.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function toggleStatus(int $id): JsonResponse
    {
        try {
            $item = $this->service->toggleStatus($id);
            return ApiResponse::success($item, 'Status item kamus berhasil diperbarui.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }
}
