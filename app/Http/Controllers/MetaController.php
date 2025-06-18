<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use DOMDocument;
use DOMXPath;

class MetaController extends Controller
{
    public function fetchMeta(Request $request): JsonResponse
    {
        $request->validate([
            'url' => 'required|url'
        ]);

        $url = $request->input('url');

        try {
            $response = Http::timeout(10)->get($url);
            
            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'URL에 접근할 수 없습니다.'
                ], 400);
            }

            $html = $response->body();
            $dom = new DOMDocument();
            @$dom->loadHTML($html, LIBXML_NOERROR);
            $xpath = new DOMXPath($dom);

            // G마켓 상품 페이지인지 확인
            if (str_contains($url, 'gmarket.co.kr')) {
                return $this->extractGmarketData($xpath, $html, $url);
            }

            // 일반적인 메타데이터 추출
            return $this->extractGeneralMeta($xpath);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '메타데이터를 가져오는데 실패했습니다: ' . $e->getMessage()
            ], 500);
        }
    }

    private function extractGmarketData(DOMXPath $xpath, string $html, string $url): JsonResponse
    {
        // G마켓 상품 이미지 추출 시도
        $imageUrl = null;
        
        // 방법 1: 상품 코드 추출 후 이미지 URL 생성
        if (preg_match('/goodsCode=(\d+)/', $url, $matches)) {
            $goodsCode = $matches[1];
            // G마켓 상품 이미지 URL 패턴들 시도
            $imagePatterns = [
                "https://gdimg.gmarket.co.kr/{$goodsCode}/goodsimg/2024/01/01/0.jpg",
                "https://gdimg.gmarket.co.kr/{$goodsCode}/goodsimg/2023/12/01/0.jpg",
                "https://gdimg.gmarket.co.kr/{$goodsCode}/goodsimg/2023/11/01/0.jpg",
                "https://gdimg.gmarket.co.kr/{$goodsCode}/goodsimg/2023/10/01/0.jpg",
                "https://gdimg.gmarket.co.kr/{$goodsCode}/goodsimg/2023/09/01/0.jpg",
                "https://gdimg.gmarket.co.kr/{$goodsCode}/goodsimg/2023/08/01/0.jpg",
                "https://gdimg.gmarket.co.kr/{$goodsCode}/goodsimg/2023/07/01/0.jpg",
                "https://gdimg.gmarket.co.kr/{$goodsCode}/goodsimg/2023/06/01/0.jpg",
                "https://gdimg.gmarket.co.kr/{$goodsCode}/goodsimg/2023/05/01/0.jpg",
                "https://gdimg.gmarket.co.kr/{$goodsCode}/goodsimg/2023/04/01/0.jpg",
                "https://gdimg.gmarket.co.kr/{$goodsCode}/goodsimg/2023/03/01/0.jpg",
                "https://gdimg.gmarket.co.kr/{$goodsCode}/goodsimg/2023/02/01/0.jpg",
                "https://gdimg.gmarket.co.kr/{$goodsCode}/goodsimg/2023/01/01/0.jpg",
            ];
            
            // 각 패턴을 시도해서 유효한 이미지 찾기
            foreach ($imagePatterns as $pattern) {
                try {
                    $imageResponse = Http::timeout(5)->head($pattern);
                    if ($imageResponse->successful()) {
                        $imageUrl = $pattern;
                        break;
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
        }
        
        // 방법 2: HTML에서 이미지 찾기 (더 구체적인 선택자)
        if (!$imageUrl) {
            $imageSelectors = [
                "//img[contains(@class, 'item_img')]",
                "//img[contains(@class, 'goods_img')]",
                "//img[contains(@class, 'product_img')]",
                "//img[contains(@class, 'main_img')]",
                "//img[contains(@id, 'item_img')]",
                "//img[contains(@id, 'goods_img')]",
                "//img[contains(@alt, '상품이미지')]",
                "//img[contains(@alt, '상품')]",
                "//div[contains(@class, 'item_img')]//img",
                "//div[contains(@class, 'goods_img')]//img",
                "//div[contains(@class, 'product_img')]//img",
            ];
            
            foreach ($imageSelectors as $selector) {
                $imageNodes = $xpath->query($selector);
                if ($imageNodes->length > 0) {
                    $src = $imageNodes->item(0)->getAttribute('src');
                    if ($src && !str_contains($src, 'data:image')) {
                        $imageUrl = $src;
                        break;
                    }
                }
            }
        }
        
        // 방법 3: JavaScript 변수에서 이미지 URL 추출
        if (!$imageUrl) {
            $jsPatterns = [
                '/"imageUrl"\s*:\s*"([^"]+)"/',
                '/"imgUrl"\s*:\s*"([^"]+)"/',
                '/"goodsImg"\s*:\s*"([^"]+)"/',
                '/"mainImg"\s*:\s*"([^"]+)"/',
                '/imageUrl\s*=\s*["\']([^"\']+)["\']/',
                '/imgUrl\s*=\s*["\']([^"\']+)["\']/',
            ];
            
            foreach ($jsPatterns as $pattern) {
                if (preg_match($pattern, $html, $matches)) {
                    $imageUrl = $matches[1];
                    break;
                }
            }
        }
        
        // 방법 4: og:image 메타 태그 확인
        if (!$imageUrl) {
            $ogImage = $this->getMetaContent($xpath, 'og:image');
            if ($ogImage) {
                $imageUrl = $ogImage;
            }
        }

        // 제목 추출
        $title = $this->getMetaContent($xpath, 'og:title') ?: $this->getTitle($xpath);
        
        // 설명 추출
        $description = $this->getMetaContent($xpath, 'og:description') ?: $this->getMetaContent($xpath, 'description');

        return response()->json([
            'success' => true,
            'data' => [
                'title' => $title,
                'description' => $description,
                'image' => $imageUrl
            ]
        ]);
    }

    private function extractGeneralMeta(DOMXPath $xpath): JsonResponse
    {
        // Open Graph 메타데이터 추출
        $ogImage = $this->getMetaContent($xpath, 'og:image');
        $ogTitle = $this->getMetaContent($xpath, 'og:title');
        $ogDescription = $this->getMetaContent($xpath, 'og:description');

        // 일반 메타데이터 추출 (Open Graph가 없는 경우)
        $title = $ogTitle ?: $this->getTitle($xpath);
        $description = $ogDescription ?: $this->getMetaContent($xpath, 'description');
        $image = $ogImage ?: $this->getFirstImage($xpath);

        return response()->json([
            'success' => true,
            'data' => [
                'title' => $title,
                'description' => $description,
                'image' => $image
            ]
        ]);
    }

    private function getMetaContent(DOMXPath $xpath, string $property): ?string
    {
        $nodes = $xpath->query("//meta[@property='{$property}' or @name='{$property}']");
        return $nodes->length > 0 ? $nodes->item(0)->getAttribute('content') : null;
    }

    private function getTitle(DOMXPath $xpath): ?string
    {
        $nodes = $xpath->query('//title');
        return $nodes->length > 0 ? $nodes->item(0)->textContent : null;
    }

    private function getFirstImage(DOMXPath $xpath): ?string
    {
        $nodes = $xpath->query('//img[@src]');
        return $nodes->length > 0 ? $nodes->item(0)->getAttribute('src') : null;
    }
} 