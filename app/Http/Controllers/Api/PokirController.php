<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pokir\AddAspirasiRequest;
use App\Http\Requests\Pokir\RequestRevisionRequest;
use App\Http\Requests\Pokir\StorePokirRequest;
use App\Http\Requests\Pokir\UpdatePokirRequest;
use App\Services\PokirService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PokirController extends Controller
{
    public function __construct(
        private PokirService $pokirService
    ) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $data = $this->pokirService->index($request->user(), $request->only([
                'status', 'opd_id', 'dapil_id', 'search', 'per_page',
            ]));
            return ApiResponse::success($data, 'Daftar Pokir berhasil dimuat.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function store(StorePokirRequest $request): JsonResponse
    {
        try {
            $pokir = $this->pokirService->store($request->user(), $request->validated());
            return ApiResponse::success($pokir, 'Pokir berhasil dibuat.', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function show(Request $request, int $id): JsonResponse
    {
        try {
            $pokir = $this->pokirService->show($request->user(), $id);
            return ApiResponse::success($pokir, 'Detail Pokir berhasil dimuat.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function update(UpdatePokirRequest $request, int $id): JsonResponse
    {
        try {
            $pokir = $this->pokirService->update($request->user(), $id, $request->validated());
            return ApiResponse::success($pokir, 'Pokir berhasil diperbarui.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        try {
            $this->pokirService->destroy($request->user(), $id);
            return ApiResponse::success(null, 'Pokir berhasil dihapus.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    // ─── Workflow Actions ─────────────────────────────────────────────────────

    /**
     * POST /api/v1/pokir/{id}/submit
     */
    public function submit(Request $request, int $id): JsonResponse
    {
        try {
            $pokir = $this->pokirService->submit($request->user(), $id);
            return ApiResponse::success($pokir, 'Pokir berhasil disubmit.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    /**
     * POST /api/v1/pokir/{id}/verify
     */
    public function verify(Request $request, int $id): JsonResponse
    {
        try {
            $pokir = $this->pokirService->verify($request->user(), $id);
            return ApiResponse::success($pokir, 'Pokir berhasil diverifikasi.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    /**
     * POST /api/v1/pokir/{id}/request-revision
     */
    public function requestRevision(RequestRevisionRequest $request, int $id): JsonResponse
    {
        try {
            $pokir = $this->pokirService->requestRevision($request->user(), $id, $request->validated()['flags']);
            return ApiResponse::success($pokir, 'Permintaan revisi berhasil dikirim.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    /**
     * POST /api/v1/pokir/{id}/finalize
     */
    public function finalize(Request $request, int $id): JsonResponse
    {
        try {
            $pokir = $this->pokirService->finalize($request->user(), $id);
            return ApiResponse::success($pokir, 'Pokir berhasil difinalisasi.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    /**
     * POST /api/v1/pokir/{id}/aspirasi
     */
    public function addAspirasi(AddAspirasiRequest $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validated();
            $pokir = $this->pokirService->addAspirasi(
                $request->user(),
                $id,
                $validated['aspirasi_id'],
                $validated['position'] ?? 0
            );
            return ApiResponse::success($pokir, 'Aspirasi berhasil ditambahkan ke Pokir.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    /**
     * DELETE /api/v1/pokir/{id}/aspirasi/{aspirasiId}
     */
    public function removeAspirasi(Request $request, int $id, int $aspirasiId): JsonResponse
    {
        try {
            $pokir = $this->pokirService->removeAspirasi($request->user(), $id, $aspirasiId);
            return ApiResponse::success($pokir, 'Aspirasi berhasil dilepas dari Pokir.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }
}
