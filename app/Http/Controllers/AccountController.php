<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    public function profile()
    {
        $user = session('admin');
        // Lấy bản ghi customers gắn với email user (đơn giản và hiệu quả)
        $customer = DB::table('customers')->where('email', $user->email)->first();
        // Địa chỉ mặc định liên kết theo customers (theo DB)
        $address = null;
        if ($customer) {
            $address = DB::table('addresses')
                ->where('customer_id', $customer->id)
                ->where('is_default', 1)
                ->first();
        }
        return view('account.profile', compact('user','customer','address'));
    }

    public function saveProfile(Request $request)
    {
        $user = session('admin');
        $fromCheckout = $request->input('from') === 'checkout';
        $rules = [
            'full_name'    => ($fromCheckout ? 'required' : 'nullable').'|string|max:120',
            'phone'        => ($fromCheckout ? 'required' : 'nullable').'|string|max:30',
            'email'        => 'required|email|max:120|unique:users,email,'.$user->id,
            'birthdate'    => 'nullable|date',
            // Address fields
            'address_line' => ($fromCheckout ? 'required' : 'nullable').'|string|max:255',
            'ward'         => ($fromCheckout ? 'required' : 'nullable').'|string|max:120',
            'district'     => ($fromCheckout ? 'required' : 'nullable').'|string|max:120',
            'province'     => ($fromCheckout ? 'required' : 'nullable').'|string|max:120',
            'from'         => 'nullable|in:checkout'
        ];
        $data = $request->validate($rules);

        // Lưu thông tin người dùng (nếu có cột)
        // Cập nhật email về bảng users để đồng bộ đăng nhập
        User::where('id',$user->id)->update(['email'=>$data['email']]);

        // Upsert vào bảng customers dựa theo email
        // Lấy địa chỉ mặc định theo customers nếu có
        $existingCustomer = DB::table('customers')->where('email',$data['email'])->first();
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
            'phone' => $data['phone'] ?? null,
            'birthday' => $data['birthdate'] ?? null,
            'address' => $defaultAddress,
            'email' => $data['email'],
        ];
        $existing = $existingCustomer;
        if ($existing) {
            DB::table('customers')->where('id',$existing->id)->update($customerData);
        } else {
            DB::table('customers')->insert($customerData);
            $existing = DB::table('customers')->where('email',$data['email'])->first();
        }

        // If user provided address, set/update default shipping address and mirror to customers.address
        if (!empty($data['address_line'])) {
            // Ensure customer row
            $cust = $existing ?: DB::table('customers')->where('email', $data['email'])->first();
            if (!$cust) {
                DB::table('customers')->insert([
                    'full_name' => $fallbackName,
                    'phone'     => $data['phone'] ?? null,
                    'address'   => $data['address_line'],
                    'email'     => $data['email'],
                ]);
                $cust = DB::table('customers')->where('email', $data['email'])->first();
            }

            DB::table('addresses')->where('customer_id',$cust->id)->update(['is_default'=>0]);
            DB::table('addresses')->updateOrInsert(
                ['customer_id'=>$cust->id, 'is_default'=>1],
                [
                    'customer_id' => $cust->id,
                    'full_name'   => $data['full_name'] ?? $fallbackName,
                    'phone'       => $data['phone'] ?? null,
                    'address_line'=> $data['address_line'],
                    'ward'        => $data['ward'] ?? null,
                    'district'    => $data['district'] ?? null,
                    'province'    => $data['province'] ?? null,
                    'is_default'  => 1,
                ]
            );

            DB::table('customers')->where('id',$cust->id)->update([
                'address' => $data['address_line'],
            ]);
        }

        // Cập nhật session
        $user = User::find($user->id);
        session(['admin'=>$user]);

        if ($fromCheckout) {
            return redirect()->route('checkout')->with('success','Đã lưu thông tin. Tiếp tục thanh toán.');
        }
        return back()->with('success','Đã lưu thông tin tài khoản.');
    }

    public function saveAddress(Request $request)
    {
        $user = session('admin');
        $data = $request->validate([
            'full_name' => 'required|string|max:120',
            'phone' => 'required|string|max:30',
            'address_line' => 'required|string|max:255',
            'ward' => 'nullable|string|max:120',
            'district' => 'nullable|string|max:120',
            'province' => 'nullable|string|max:120',
        ]);

        // Bảo đảm có bản ghi customers dựa theo email đăng nhập
        $cust = DB::table('customers')->where('email', $user->email)->first();
        if (!$cust) {
            DB::table('customers')->insert([
                'full_name' => $data['full_name'],
                'phone'    => $data['phone'],
                'address'  => $data['address_line'],
                'email'    => $user->email,
            ]);
            $cust = DB::table('customers')->where('email', $user->email)->first();
        }

        // Đặt mặc định 1 địa chỉ theo customer_id
        DB::table('addresses')->where('customer_id',$cust->id)->update(['is_default'=>0]);
        DB::table('addresses')->updateOrInsert(
            ['customer_id'=>$cust->id, 'is_default'=>1],
            array_merge($data, ['customer_id'=>$cust->id, 'is_default'=>1])
        );

        // Đồng bộ địa chỉ/name/phone vào customers
        DB::table('customers')->where('id',$cust->id)->update([
            'full_name' => $data['full_name'],
            'phone' => $data['phone'],
            'address' => $data['address_line'],
        ]);

        return back()->with('success','Đã lưu địa chỉ mặc định.');
    }
}
