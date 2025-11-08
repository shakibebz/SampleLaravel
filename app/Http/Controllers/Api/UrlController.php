<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Str;
use App\Models\Url;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UrlController extends Controller
{
    /**
     * POST /api/v1/shorten
     * Create a short URL.
     */
    public function shorten(Request $request): JsonResponse
    {
        $request->validate([
            'url' => ['required', 'url', 'max:2048'],
        ]);

        try {
            $originalUrl = $request->input('url');


            do {
                $code = Str::random(6);
            } while (Url::where('short_code', $code)->exists());

            $url = Url::create([
                'original_url' => $originalUrl,
                'short_code'   => $code,
            ]);


            $shortDomain = config('app.url');
            $shortUrl = rtrim($shortDomain, '/') . '/' . $url->short_code;

            return response()->json(['short_url' => $shortUrl], Response::HTTP_CREATED);

        } catch (\Throwable $e) {
            return response()->json(['message' => 'Server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * GET /{short_code}
     * Redirect to the original URL.
     */
    public function redirect($short_code)
    {

        $url = Url::where('short_code', $short_code)->first();

        if (!$url) {
            return response()->json(['message' => 'Not found'], Response::HTTP_NOT_FOUND);
        }

        $url->increment('clicks');

        return redirect()->away($url->original_url, Response::HTTP_MOVED_PERMANENTLY);
    }

    /**
     * GET /api/v1/urls
     * List all shortened URLs.
     */
    public function index(): JsonResponse
    {
        $urls = Url::orderBy('created_at', 'desc')->get(['id', 'original_url', 'short_code', 'clicks', 'created_at']);

        return response()->json($urls, Response::HTTP_OK);
    }

    /**
     * DELETE /api/v1/urls/{id}
     * Delete a short URL record (optional).
     */
    public function destroy($id): JsonResponse
    {
        $url = Url::find($id);

        if (!$url) {
            return response()->json(['message' => 'Not found'], Response::HTTP_NOT_FOUND);
        }

        $url->delete();

        return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
    }
}
