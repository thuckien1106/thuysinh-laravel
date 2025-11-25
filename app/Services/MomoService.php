<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class MomoService
{
    /**
     * Tạo yêu cầu thanh toán MoMo (sandbox/test) và trả về payUrl/qrCodeUrl.
     *
     * @param string $orderId   ID đơn hàng (duy nhất).
     * @param int    $amount    Số tiền VNĐ.
     * @param string $orderInfo Nội dung hiển thị MoMo.
     * @param string $extraData Dữ liệu thêm (sẽ được base64).
     *
     * @return array{payUrl:?string, qrCodeUrl:?string, deeplink:?string, requestId:string, raw_response:array, expire_at:int}
     */
    public function createPayment(string $orderId, int $amount, string $orderInfo = 'Thanh toán đơn hàng', string $extraData = ''): array
    {
        $cfg = config('momo');
        $requestId = Str::uuid()->toString();
        $expireTs = now()->addMinutes($cfg['expire'] ?? 15)->timestamp;

        $payload = [
            'partnerCode'     => $cfg['partner_code'],
            'accessKey'       => $cfg['access_key'],
            'requestId'       => $requestId,
            'amount'          => (string) $amount,
            'orderId'         => $orderId,
            'orderInfo'       => $orderInfo,
            'redirectUrl'     => $cfg['return_url'],
            'ipnUrl'          => $cfg['ipn_url'],
            'lang'            => 'vi',
            'extraData'       => $extraData ? base64_encode($extraData) : '',
            'requestType'     => 'captureWallet',
            'autoCapture'     => true,
            'orderExpireTime' => $expireTs,
        ];

        $payload['signature'] = $this->signPayment($payload, $cfg['secret_key']);

        $res = Http::asJson()->post($cfg['endpoint'], $payload);
        if (!$res->ok() || !$res->json()) {
            throw new RuntimeException('Gọi MoMo thất bại: '.$res->body());
        }

        $data = $res->json() ?? [];
        if (($data['resultCode'] ?? -1) !== 0) {
            throw new RuntimeException('MoMo trả về lỗi: '.(($data['message'] ?? '') ?: 'unknown'));
        }

        return [
            'payUrl'       => $data['payUrl'] ?? null,
            'qrCodeUrl'    => $data['qrCodeUrl'] ?? null,
            'deeplink'     => $data['deeplink'] ?? null,
            'requestId'    => $requestId,
            'raw_response' => $data,
            'expire_at'    => $expireTs,
        ];
    }

    /**
     * Ký payload theo chuẩn MoMo create (cố định thứ tự trường).
     */
    public function signPayment(array $payload, string $secret): string
    {
        $raw = sprintf(
            'accessKey=%s&amount=%s&extraData=%s&ipnUrl=%s&orderId=%s&orderInfo=%s&partnerCode=%s&redirectUrl=%s&requestId=%s&requestType=%s',
            $payload['accessKey'] ?? '',
            $payload['amount'] ?? '',
            $payload['extraData'] ?? '',
            $payload['ipnUrl'] ?? '',
            $payload['orderId'] ?? '',
            $payload['orderInfo'] ?? '',
            $payload['partnerCode'] ?? '',
            $payload['redirectUrl'] ?? '',
            $payload['requestId'] ?? '',
            $payload['requestType'] ?? ''
        );

        return hash_hmac('sha256', $raw, $secret);
    }

    /**
     * Xác minh chữ ký IPN/return của MoMo.
     * Tự động bỏ qua trường signature trong $data.
     */
    public function verifyIpn(array $data): bool
    {
        if (!isset($data['signature'])) {
            return false;
        }

        $fields = [
            'partnerCode', 'accessKey', 'requestId', 'amount', 'orderId',
            'orderInfo', 'orderType', 'transId', 'resultCode', 'message',
            'payType', 'responseTime', 'extraData'
        ];

        $rawParts = [];
        foreach ($fields as $field) {
            $rawParts[] = $field.'='.($data[$field] ?? '');
        }
        $raw = implode('&', $rawParts);

        $secret = config('momo.secret_key');
        $expected = hash_hmac('sha256', $raw, $secret);

        return hash_equals($expected, $data['signature']);
    }
}
