<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\SsoReferenceService;
use App\Services\SsoUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private SsoUserService $ssoUserService,
        private SsoReferenceService $ssoReferenceService
    ) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $users = $this->ssoUserService->getAll($request->user());
            return ApiResponse::success($users, 'Daftar user berhasil dimuat.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function show(Request $request, int $id): JsonResponse
    {
        try {
            $user = $this->ssoUserService->getById($request->user(), $id);
            return ApiResponse::success($user, 'Detail user berhasil dimuat.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            $user = $this->ssoUserService->create($request->user(), $request->validated());
            return ApiResponse::success($user, 'User berhasil dibuat.', 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        try {
            $user = $this->ssoUserService->update($request->user(), $id, $request->validated());
            return ApiResponse::success($user, 'User berhasil diperbarui.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        try {
            $this->ssoUserService->delete($request->user(), $id);
            return ApiResponse::success(null, 'User berhasil dihapus.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function toggleActive(Request $request, int $id): JsonResponse
    {
        try {
            $user = $this->ssoUserService->toggleActive($request->user(), $id);
            return ApiResponse::success($user, 'Status user berhasil diubah.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function dapilOptions(Request $request): JsonResponse
    {
        try {
            $dapils = $this->ssoReferenceService->getDapils($request->user());
            return ApiResponse::success($dapils, 'Opsi Dapil berhasil dimuat.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function fraksiOptions(Request $request): JsonResponse
    {
        try {
            $fraksis = $this->ssoReferenceService->getFraksis($request->user());
            return ApiResponse::success($fraksis, 'Opsi Fraksi berhasil dimuat.');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 400);
        }
    }
}
