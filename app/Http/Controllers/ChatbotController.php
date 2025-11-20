<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    private function extractReply($data)
    {
        if (!isset($data['candidates'][0])) return null;

        $cand = $data['candidates'][0];

        // Format chuẩn API v1 (2025)
        if (isset($cand['content']['parts'][0]['text'])) {
            return $cand['content']['parts'][0]['text'];
        }

        // Format fallback
        if (isset($cand['content'][0]['parts'][0]['text'])) {
            return $cand['content'][0]['parts'][0]['text'];
        }

        return null;
    }

    public function ask(Request $request)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey) {
            return response()->json(['reply' => '❌ Chưa cấu hình API key']);
        }

        $prompt = $request->message;

        // MODEL 2025 (đời mới nhất – giống Python của bạn)
        $model = "models/gemini-2.5-flash-lite";

        // Body theo tiêu chuẩn API v1
        $postData = [
            "contents" => [
                [
                    "parts" => [
                        ["text" =>
"Bạn là trợ lý AquaShop — chuyên tư vấn cá, cây và phụ kiện hồ thủy sinh.

Yêu cầu định dạng:
1. Trình bày văn bản đẹp như người thật viết, xuống dòng tự nhiên.
2. KHÔNG sử dụng bất kỳ ký tự đặc biệt nào như *, -, _, #, |, ~, { }, [ ], >.
3. Không dùng markdown.
4. Có thể dùng emoji như ✨🐠🌿🔥💡.
5. Trình bày thông tin theo dạng đoạn văn hoặc từng mục bằng cách xuống dòng, nhưng KHÔNG dùng ký tự đầu dòng.
6. Giọng văn thân thiện, giải thích rõ ràng, dễ hiểu.
7. Tránh tuyệt đối mọi dạng danh sách có dấu đầu dòng.

Câu hỏi của khách: $prompt"



                        ]
                    ]
                ]
            ]
        ];

        try {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL,
                "https://generativelanguage.googleapis.com/v1/$model:generateContent?key=$apiKey"
            );
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

            $response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($response, true);

            $reply = $this->extractReply($data);

            if (!$reply) {
                return response()->json([
                    "reply" => "⚠ Không thể lấy phản hồi từ AI, thử lại sau!",
                    "debug" => $data,
                    "raw" => $response
                ]);
            }

            return response()->json(['reply' => $reply], 200, [], JSON_UNESCAPED_UNICODE);


        } catch (\Exception $e) {
            return response()->json([
                'reply' => '⚠ Lỗi hệ thống AI!',
                'error' => $e->getMessage()
            ]);
        }
    }
}
