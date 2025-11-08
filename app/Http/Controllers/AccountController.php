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
        // LÃ¡ÂºÂ¥y bÃ¡ÂºÂ£n ghi customers gÃ¡ÂºÂ¯n vÃ¡Â»â€ºi email user (Ã„â€˜Ã†Â¡n giÃ¡ÂºÂ£n vÃƒÂ  hiÃ¡Â»â€¡u quÃ¡ÂºÂ£)
        $customer = DB::table('customers')->where('email', $user->email)->first();
        // Ã„ÂÃ¡Â»â€¹a chÃ¡Â»â€° mÃ¡ÂºÂ·c Ã„â€˜Ã¡Â»â€¹nh liÃƒÂªn kÃ¡ÂºÂ¿t theo customers (theo DB)
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

        // LÃ†Â°u thÃƒÂ´ng tin ngÃ†Â°Ã¡Â»Âi dÃƒÂ¹ng (nÃ¡ÂºÂ¿u cÃƒÂ³ cÃ¡Â»â„¢t)
        // CÃ¡ÂºÂ­p nhÃ¡ÂºÂ­t email vÃ¡Â»Â bÃ¡ÂºÂ£ng users Ã„â€˜Ã¡Â»Æ’ Ã„â€˜Ã¡Â»â€œng bÃ¡Â»â„¢ Ã„â€˜Ã„Æ’ng nhÃ¡ÂºÂ­p
        User::where('id',$user->id)->update(['email'=>$data['email']]);

        // Upsert vÃƒÂ o bÃ¡ÂºÂ£ng customers dÃ¡Â»Â±a theo email
        // LÃ¡ÂºÂ¥y Ã„â€˜Ã¡Â»â€¹a chÃ¡Â»â€° mÃ¡ÂºÂ·c Ã„â€˜Ã¡Â»â€¹nh theo customers nÃ¡ÂºÂ¿u cÃƒÂ³
        $existingCustomer = DB::table('customers')->where('email',$data['email'])->first();
        $defaultAddress = null;
        if ($existingCustomer) {
            $defaultAddress = DB::table('addresses')
                ->where('customer_id', $existingCustomer->id)
                ->where('is_default', 1)
                ->value('address_line');
        }

        $fallbackName = $data['full_name'] ?? ($existingCustomer->full_name ?? ($user->username ?? 'KhÃƒÂ¡ch hÃƒÂ ng'));
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

        // CÃ¡ÂºÂ­p nhÃ¡ÂºÂ­t session
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

        // BÃ¡ÂºÂ£o Ã„â€˜Ã¡ÂºÂ£m cÃƒÂ³ bÃ¡ÂºÂ£n ghi customers dÃ¡Â»Â±a theo email Ã„â€˜Ã„Æ’ng nhÃ¡ÂºÂ­p
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

        // Ã„ÂÃ¡ÂºÂ·t mÃ¡ÂºÂ·c Ã„â€˜Ã¡Â»â€¹nh 1 Ã„â€˜Ã¡Â»â€¹a chÃ¡Â»â€° theo customer_id
        DB::table('addresses')->where('customer_id',$cust->id)->update(['is_default'=>0]);
        DB::table('addresses')->updateOrInsert(
            ['customer_id'=>$cust->id, 'is_default'=>1],
            array_merge($data, ['customer_id'=>$cust->id, 'is_default'=>1])
        );

        // Ã„ÂÃ¡Â»â€œng bÃ¡Â»â„¢ Ã„â€˜Ã¡Â»â€¹a chÃ¡Â»â€°/name/phone vÃƒÂ o customers
        DB::table('customers')->where('id',$cust->id)->update([
            'full_name' => $data['full_name'],
            'phone' => $data['phone'],
            'address' => $data['address_line'],
        ]);

        return back()->with('success','Đã lưu địa chỉ mặc định.');
    }
}

