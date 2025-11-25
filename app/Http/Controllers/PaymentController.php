<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Services\MomoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function start(Order $order, MomoService $momo)
    {
        // Chỉ tạo thanh toán cho đơn đang xử lý/chưa trả tiền
        $payment = Payment::firstOrCreate(
            ['order_id' => $order->id, 'method' => 'online'],
            ['amount' => $order->total, 'status' => 'pending']
        );

        // orderId MoMo phải duy nhất, thêm suffix thời gian
        $momoOrderId = $order->id . '-' . now()->timestamp;
        $orderInfo = 'Thanh toan don hang #'.$order->id;
        $extra = json_encode(['order_id' => $order->id]);
        $amount = (int) $order->total;
        if ($amount < 1000) {
            return redirect()->route('order.thankyou', $order->id)
                ->with('error', 'MoMo chỉ hỗ trợ đơn hàng từ 1.000đ trở lên. Vui lòng thêm sản phẩm hoặc chọn phương thức khác.');
        }
        $momoRes = $momo->createPayment($momoOrderId, $amount, $orderInfo, $extra);

        // Lưu requestId/transactionId để đối soát
        $payment->transaction_id = $momoRes['requestId'] ?? $momoOrderId;
        $payment->save();

        $payUrl = $momoRes['payUrl'] ?? null;
        $qrCodeUrl = $momoRes['qrCodeUrl'] ?? null;
        $deeplink = "momo://app?action=payWithApp&phone=0332643954&amount={$amount}&comment=Thanh toan don {$order->id}";
        $qrData = urlencode($payUrl ?? $deeplink);
        if (!$qrCodeUrl) {
            $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={$qrData}";
        }
        $transferContent = $order->customer_phone ?? $order->phone ?? ('ORDER'.$order->id);

        return view('payment_momo', [
            'order'       => $order,
            'payment'     => $payment,
            'payUrl'      => $payUrl ?? $deeplink,
            'qrCodeUrl'   => $qrCodeUrl,
            'transferContent' => $transferContent,
            'momoDeepLink' => $deeplink,
            'expireAtTs'  => $momoRes['expire_at'],
        ]);
    }

    public function return(Request $request)
    {
        // MoMo redirect sẽ gọi return_url, hiển thị thông báo dựa trên resultCode
        $orderIdRaw = $request->input('orderId');
        $orderId = $orderIdRaw ? explode('-', $orderIdRaw)[0] : null;
        $resultCode = (int) $request->input('resultCode', -1);
        $message = $request->input('message', 'Không rõ');

        if ($orderId && $resultCode === 0) {
            Payment::where('order_id', $orderId)->update(['status' => 'paid', 'paid_at' => now()]);
            Order::where('id', $orderId)->update(['status' => 'processing']);
            return redirect()->route('order.thankyou', $orderId)->with('success', 'Thanh toán MoMo thành công.');
        }

        return redirect()->route('order.thankyou', $orderId ?? 0)
            ->with('error', 'Thanh toán MoMo thất bại: '.$message);
    }

    public function ipn(Request $request, MomoService $momo)
    {
        $data = $request->all();
        Log::info('MoMo IPN', $data);

        if (!$momo->verifyIpn($data)) {
            return response()->json(['message' => 'invalid signature'], 400);
        }

        $orderIdRaw = $data['orderId'] ?? null;
        $orderId = $orderIdRaw ? explode('-', $orderIdRaw)[0] : null;
        $resultCode = (int) ($data['resultCode'] ?? -1);

        if ($orderId) {
            $status = $resultCode === 0 ? 'paid' : 'failed';
            Payment::where('order_id', $orderId)->update([
                'status'    => $status,
                'paid_at'   => $status === 'paid' ? now() : null,
                'amount'    => $data['amount'] ?? 0,
                'method'    => 'online',
                'transaction_id' => $data['requestId'] ?? null,
            ]);
            Order::where('id', $orderId)->update([
                'status' => $status === 'paid' ? 'processing' : 'cancelled',
            ]);
        }

        return response()->json(['message' => 'ok']);
    }

    /**
     * Demo: giả lập thanh toán thành công khi quét QR nội bộ.
     */
    public function mock(Order $order, Request $request)
    {
        $acc = $request->input('acc', '0332643954');

        $payment = Payment::firstOrCreate(
            ['order_id' => $order->id],
            ['amount' => $order->total, 'status' => 'pending', 'method' => 'online']
        );

        if ($payment->status !== 'paid') {
            $payment->status = 'paid';
            $payment->paid_at = now();
            $payment->method = 'momo';
            $payment->amount = $order->total;
            $payment->transaction_id = $payment->transaction_id ?: 'MOCK-' . now()->timestamp;
            $payment->save();

            $order->status = 'processing';
            $order->save();
        }

        return redirect()
            ->route('order.thankyou', $order->id)
            ->with('success', 'Đã thanh toán MoMo thành công.');
    }
}
