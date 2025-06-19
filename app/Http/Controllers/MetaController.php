<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MetaController extends Controller
{
    public function fetchMeta(Request $request)
    {
        try {
            $request->validate([
                'url' => 'required|url'
            ]);

            $url = $request->input('url');
            $response = Http::timeout(10)->get($url);
            
            if (!$response->successful()) {
                return response()->json([
                    'error' => 'URL에 접근할 수 없습니다.',
                    'status' => $response->status()
                ], 400);
            }

            $html = $response->body();
            $metaData = $this->extractMetaData($html, $url);
            
            return response()->json([
                'data' => $metaData
            ]);

        } catch (\Exception $e) {
            Log::error('메타데이터 가져오기 실패: ' . $e->getMessage(), [
                'url' => $request->input('url'),
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'error' => '메타데이터를 가져오는데 실패했습니다.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function extractMetaData($html, $baseUrl)
    {
        $metaData = [
            'title' => '',
            'description' => '',
            'image' => ''
        ];

        if (preg_match('/<meta[^>]*property=["\']og:title["\'][^>]*content=["\']([^"\']*)["\']/', $html, $matches)) {
            $metaData['title'] = trim($matches[1]);
        } elseif (preg_match('/<title[^>]*>([^<]*)<\/title>/', $html, $matches)) {
            $metaData['title'] = trim($matches[1]);
        }

        if (preg_match('/<meta[^>]*property=["\']og:description["\'][^>]*content=["\']([^"\']*)["\']/', $html, $matches)) {
            $metaData['description'] = trim($matches[1]);
        } elseif (preg_match('/<meta[^>]*name=["\']description["\'][^>]*content=["\']([^"\']*)["\']/', $html, $matches)) {
            $metaData['description'] = trim($matches[1]);
        }
        if (preg_match('/<meta[^>]*property=["\']og:image["\'][^>]*content=["\']([^"\']*)["\']/', $html, $matches)) {
            $imageUrl = trim($matches[1]);
            $metaData['image'] = $this->resolveRelativeUrl($imageUrl, $baseUrl);
        } elseif (preg_match('/<img[^>]*src=["\']([^"\']*)["\']/', $html, $matches)) {
            $imageUrl = trim($matches[1]);
            $metaData['image'] = $this->resolveRelativeUrl($imageUrl, $baseUrl);
        }

        return $metaData;
    }

    private function resolveRelativeUrl($url, $baseUrl)
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }
        
        if (strpos($url, '//') === 0) {
            $parsedBase = parse_url($baseUrl);
            return $parsedBase['scheme'] . ':' . $url;
        }

        if (strpos($url, '/') === 0) {
            $parsedBase = parse_url($baseUrl);
            return $parsedBase['scheme'] . '://' . $parsedBase['host'] . $url;
        }

        return rtrim($baseUrl, '/') . '/' . ltrim($url, '/');
    }

    public function fetchOgMeta(Request $request)
    {
        $url = $request->input('url');
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(5)->withHeaders([
                'accept-encoding' => 'gzip, deflate, br',
                'accept-language' => 'ko-KR,ko;q=0.9,en-US;q=0.8,en;q=0.7',
                'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
            ])->get($url);

            if (!$response->successful()) {
                return response()->json(['error' => 'URL에 접근할 수 없습니다.'], 400);
            }

            $html = $response->body();

            $og = [
                'title' => '',
                'description' => '',
                'image' => '',
                'url' => ''
            ];

            if (preg_match('/<meta[^>]+property=["\']og:title["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $m)) {
                $og['title'] = $m[1];
            }
            if (preg_match('/<meta[^>]+property=["\']og:description["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $m)) {
                $og['description'] = $m[1];
            }
            if (preg_match('/<meta[^>]+property=["\']og:image["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $m)) {
                $og['image'] = $m[1];
            }
            if (preg_match('/<meta[^>]+property=["\']og:url["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $m)) {
                $og['url'] = $m[1];
            }

            return response()->json(['data' => $og]);
        } catch (\Exception $e) {
            return response()->json(['error' => '메타데이터를 가져오는데 실패했습니다.', 'message' => $e->getMessage()], 500);
        }
    }
} 