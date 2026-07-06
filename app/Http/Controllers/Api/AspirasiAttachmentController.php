<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use App\Models\AspirasiAttachment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AspirasiAttachmentController extends Controller
{
    /**
     * GET /api/v1/aspirasi/{id}/attachments/{attachmentId}/view
     * Stream file dengan Content-Disposition: inline supaya browser menampilkannya
     * langsung (preview), bukan men-download. URL storage statis sebelumnya tidak
     * mengirim header ini sama sekali, sehingga sebagian browser/download manager
     * (mis. Internet Download Manager) memaksanya jadi download.
     */
    public function view(int $id, int $attachmentId): StreamedResponse
    {
        $attachment = AspirasiAttachment::where('id', $attachmentId)
            ->where('aspirasi_id', $id)
            ->first();

        if (! $attachment || ! Storage::disk('public')->exists($attachment->file_path)) {
            abort(404, 'Attachment tidak ditemukan.');
        }

        return Storage::disk('public')->response(
            $attachment->file_path,
            $attachment->file_name,
            ['Cache-Control' => 'private, max-age=3600'],
        );
    }

    /**
     * POST /api/v1/aspirasi/{id}/attachments
     * Upload satu atau lebih file (foto / dokumen) untuk sebuah Aspirasi.
     * Dewan hanya bisa mengelola attachment milik aspirasinya sendiri.
     */
    public function store(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'files' => ['required', 'array', 'min:1', 'max:10'],
            'files.*' => ['file', 'max:20480', 'mimes:jpeg,jpg,png,webp,pdf,doc,docx'],
        ]);

        $aspirasi = Aspirasi::find($id);

        if (! $aspirasi) {
            return ApiResponse::error('Aspirasi tidak ditemukan.', 404);
        }

        $user = $request->user();
        if ($user->isDewan() && $aspirasi->user_id !== $user->id) {
            return ApiResponse::error('Anda tidak memiliki akses ke aspirasi ini.', 403);
        }

        $created = [];

        foreach ($request->file('files', []) as $file) {
            $path = $file->store("aspirasi/{$id}", 'public');

            $created[] = AspirasiAttachment::create([
                'aspirasi_id' => $id,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
            ]);
        }

        $mapped = array_map(fn ($a) => [
            'id' => $a->id,
            'file_name' => $a->file_name,
            'file_path' => $a->file_path,
            'file_type' => $a->file_type,
            'file_size' => $a->file_size,
            'url' => $a->url,
            'view_url' => $a->view_url,
        ], $created);

        return ApiResponse::success($mapped, count($created).' file berhasil diupload.', 201);
    }

    /**
     * DELETE /api/v1/aspirasi/{id}/attachments/{attachmentId}
     * Hapus sebuah attachment beserta filenya dari storage.
     */
    public function destroy(Request $request, int $id, int $attachmentId): JsonResponse
    {
        $attachment = AspirasiAttachment::where('id', $attachmentId)
            ->where('aspirasi_id', $id)
            ->first();

        if (! $attachment) {
            return ApiResponse::error('Attachment tidak ditemukan.', 404);
        }

        $user = $request->user();
        if ($user->isDewan() && $attachment->aspirasi->user_id !== $user->id) {
            return ApiResponse::error('Anda tidak memiliki akses ke attachment ini.', 403);
        }

        Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();

        return ApiResponse::success(null, 'Attachment berhasil dihapus.');
    }
}
