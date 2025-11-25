<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    /**
     * Hiển thị trang hồ sơ cá nhân.
     */
    public function profile()
    {
        $user = session('admin');
        
        // Lấy bản ghi trong bảng customers gắn với email của user (Logic đơn giản và hiệu quả)
        $customer = DB::table('customers')->where('email', $user->email)->first();
        
        // Lấy địa chỉ mặc định liên kết theo customers (dựa trên DB)
        $address = null;
        if ($customer) {
            $address = DB::table('addresses')
                ->where('customer_id', $customer->id)
                ->where('is_default', 1)
                ->first();
        }
        
        return view('account.profile', compact('user', 'customer', 'address'));
    }

    /**
     * Lưu cập nhật thông tin hồ sơ (bao gồm cả update từ trang Checkout).
     */
    public function saveProfile(Request $request)
    {
        $user = session('admin');
        $fromCheckout = $request->input('from') === 'checkout';

        // Validate dữ liệu đầu vào
        $rules = [
            'full_name'    => ($fromCheckout ? 'required' : 'nullable') . '|string|max:120',
            'phone'        => ($fromCheckout ? 'required' : 'nullable') . '|string|max:30',
            'email'        => 'required|email|max:120|unique:users,email,' . $user->id,
            'birthdate'    => 'nullable|date',
            // Các trường địa chỉ
            'address_line' => ($fromCheckout ? 'required' : 'nullable') . '|string|max:255',
            'ward'         => ($fromCheckout ? 'required' : 'nullable') . '|string|max:120',
            'district'     => ($fromCheckout ? 'required' : 'nullable') . '|string|max:120',
            'province'     => ($fromCheckout ? 'required' : 'nullable') . '|string|max:120',
            'from'         => 'nullable|in:checkout'
        ];

        $messages = [
            'full_name.required'    => 'Vui lòng nhập họ và tên.',
            'phone.required'        => 'Vui lòng nhập số điện thoại.',
            'address_line.required' => 'Vui lòng nhập địa chỉ chi tiết.',
            'email.unique'          => 'Email này đã được sử dụng bởi tài khoản khác.',
        ];

        $data = $request->validate($rules, $messages);

        // 1. Cập nhật email, name, username vào bảng users để đồng bộ đăng nhập
        $username = explode('@', $data['email'])[0];
        User::where('id', $user->id)->update([
            'email' => $data['email'],
            'name' => $data['full_name'] ?? $user->name,
            'username' => $username,
        ]);

        // 2. Xử lý bảng Customers (Upsert dựa theo email)
        $existingCustomer = DB::table('customers')->where('email', $data['email'])->first();
        
        // Giữ lại địa chỉ cũ nếu người dùng không nhập địa chỉ mới trong lần cập nhật này
        $defaultAddress = null;
        if ($existingCustomer) {
            $defaultAddress = DB::table('addresses')
                ->where('customer_id', $existingCustomer->id)
                ->where('is_default', 1)
                ->value('address_line');
        }

        $fallbackName = $data['full_name'] ?? ($existingCustomer->full_name ?? ($user->username ?? 'Khách hàng'));
        
        $customerData = [
            'full_name' => $fallbackName,
            'phone'     => $data['phone'] ?? null,
            'birthday'  => $data['birthdate'] ?? null,
            'address'   => $defaultAddress, // Tạm thời giữ địa chỉ cũ
            'email'     => $data['email'],
        ];

        if ($existingCustomer) {
            DB::table('customers')->where('id', $existingCustomer->id)->update($customerData);
            $customerId = $existingCustomer->id;
        } else {
            $customerId = DB::table('customers')->insertGetId($customerData);
        }

        // 3. Xử lý Địa chỉ: Nếu người dùng nhập địa chỉ, set làm mặc định
        if (!empty($data['address_line'])) {
            // Bỏ cờ mặc định của các địa chỉ cũ
            DB::table('addresses')->where('customer_id', $customerId)->update(['is_default' => 0]);
            
            // Cập nhật hoặc thêm mới địa chỉ mặc định
            DB::table('addresses')->updateOrInsert(
                [
                    'customer_id' => $customerId,
                    'address_line' => $data['address_line'] // Dùng địa chỉ làm key để tránh trùng lặp
                ],
                [
                    'customer_id'  => $customerId,
                    'full_name'    => $data['full_name'] ?? $fallbackName,
                    'phone'        => $data['phone'] ?? null,
                    'address_line' => $data['address_line'],
                    'ward'         => $data['ward'] ?? null,
                    'district'     => $data['district'] ?? null,
                    'province'     => $data['province'] ?? null,
                    'is_default'   => 1,
                ]
            );

            // Đồng bộ cột address ngắn gọn vào bảng customers
            DB::table('customers')->where('id', $customerId)->update([
                'address' => $data['address_line'],
            ]);
        }

        // 4. Cập nhật lại session user
        $userFresh = User::find($user->id);
        session(['admin' => $userFresh]);

        if ($fromCheckout) {
            return redirect()->route('checkout')
                ->with('success', 'Đã lưu thông tin giao hàng. Mời bạn tiếp tục thanh toán.');
        }

        return back()->with('success', 'Đã cập nhật hồ sơ thành công.');
    }

    /**
     * Lưu địa chỉ mới (thường dùng cho popup hoặc form thêm địa chỉ phụ).
     */
    public function saveAddress(Request $request)
    {
        $user = session('admin');
        
        $data = $request->validate([
            'full_name'    => 'required|string|max:120',
            'phone'        => 'required|string|max:30',
            'address_line' => 'required|string|max:255',
            'ward'         => 'nullable|string|max:120',
            'district'     => 'nullable|string|max:120',
            'province'     => 'nullable|string|max:120',
        ], [
            'full_name.required'    => 'Vui lòng nhập họ tên người nhận.',
            'phone.required'        => 'Vui lòng nhập số điện thoại.',
            'address_line.required' => 'Vui lòng nhập địa chỉ.',
        ]);

        // Đảm bảo có bản ghi customers dựa theo email đăng nhập
        $cust = DB::table('customers')->where('email', $user->email)->first();
        if (!$cust) {
            $custId = DB::table('customers')->insertGetId([
                'full_name' => $data['full_name'],
                'phone'     => $data['phone'],
                'address'   => $data['address_line'],
                'email'     => $user->email,
            ]);
            $cust = DB::table('customers')->where('id', $custId)->first();
        }

        // Đặt địa chỉ mới này làm mặc định -> Reset các cái cũ về 0
        DB::table('addresses')->where('customer_id', $cust->id)->update(['is_default' => 0]);
        
        // Thêm/Cập nhật địa chỉ mới và set is_default = 1
        DB::table('addresses')->updateOrInsert(
            [
                'customer_id'  => $cust->id, 
                'address_line' => $data['address_line']
            ],
            array_merge($data, ['customer_id' => $cust->id, 'is_default' => 1])
        );

        // Đồng bộ thông tin liên lạc chính vào bảng customers
        DB::table('customers')->where('id', $cust->id)->update([
            'full_name' => $data['full_name'],
            'phone'     => $data['phone'],
            'address'   => $data['address_line'],
        ]);

        return back()->with('success', 'Đã lưu địa chỉ mặc định thành công.');
    }
}