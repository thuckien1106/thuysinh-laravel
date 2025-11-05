<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\{User, Category, Brand, Product, Customer, Address, Order, OrderDetail, Payment, Shipment};

class InitialSeeder extends Seeder
{
    public function run()
    {
        // Helper: chuẩn hóa tên sản phẩm theo Title Case (Unicode) + dọn ký tự thừa
        $normalize = function(string $name): string {
            $n = trim($name);
            // thay '_' '-' bằng khoảng trắng và gom nhiều khoảng trắng
            $n = preg_replace('/[_-]+/u', ' ', $n);
            $n = preg_replace('/\s+/u', ' ', $n);
            // title case đa byte
            if (function_exists('mb_convert_case')) {
                $n = mb_convert_case($n, MB_CASE_TITLE, 'UTF-8');
            } else {
                $n = ucwords(strtolower($n));
            }
            // giữ nguyên các từ viết hoa phổ biến
            $n = preg_replace('/\b(Led)\b/u', 'LED', $n);
            $n = preg_replace('/\b(Co2)\b/u', 'CO2', $n);
            // từ nối nên để thường
            foreach (['Và','Với','Cho','Của','Trong','Hoặc'] as $w) {
                $n = preg_replace('/\b'.$w.'\b/u', mb_strtolower($w,'UTF-8'), $n);
            }
            // chèn khoảng trắng giữa số và đơn vị phổ biến
            $n = preg_replace('/(\d+)\s*(cm|mm|m|l|w)\b/iu', '$1 $2', $n);
            // chuẩn hoá một số cụm từ tiếng Việt có dấu
            $map = [
                '/\b7\s*Mau\b/ui' => 'Bảy Màu',
                '/\bBay Mau\b/ui' => 'Bảy Màu',
                '/\bDuong Xi\b/ui' => 'Dương Xỉ',
                '/\bNguu Mao Chien\b/ui' => 'Ngưu Mao Chiên',
                '/\bRay\b/ui' => 'Ráy',
                '/\bLa Han\b/ui' => 'La Hán',
                '/\bBut Chi\b/ui' => 'Bút Chì',
                '/\bSui\b/ui' => 'Sủi',
                '/\bSuoi\b/ui' => 'Sưởi',
                '/\bLoc\b/ui' => 'Lọc',
                '/\bCa\b/ui' => 'Cá',
            ];
            foreach ($map as $re => $rp) { $n = preg_replace($re, $rp, $n); }
            return $n;
        };
        // Users
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );
        User::updateOrCreate(
            ['username' => 'user'],
            [
                'name' => 'Demo User',
                'email' => 'user@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );

        // Categories
        $categoryNames = ['Cây thủy sinh', 'Cá cảnh', 'Thiết bị'];
        $categories = [];
        foreach ($categoryNames as $name) {
            $c = Category::firstOrCreate(['name' => $name]);
            $categories[$name] = $c->id;
        }

        // Brands
        $brandData = [
            ['name' => 'AquaNature', 'slug' => 'aquanature'],
            ['name' => 'AquaTech', 'slug' => 'aquatech'],
            ['name' => 'FishWorld', 'slug' => 'fishworld'],
        ];
        $brands = [];
        foreach ($brandData as $b) {
            $br = Brand::firstOrCreate(['name' => $b['name']], ['slug' => $b['slug']]);
            $brands[$b['name']] = $br->id;
        }

        // Products (sample)
        $products = [
            // Plants
            ['name' => 'Dương xỉ Java', 'description' => 'Cây dễ trồng.', 'price' => 35000, 'quantity' => 100, 'image' => 'duongxi_java.webp', 'category' => 'Cây thủy sinh', 'brand' => 'AquaNature'],
            ['name' => 'Hồng Điệp', 'description' => 'Cây màu đỏ nổi bật.', 'price' => 55000, 'quantity' => 80, 'image' => 'hong_diep.webp', 'category' => 'Cây thủy sinh', 'brand' => 'AquaNature'],
            ['name' => 'Rêu Java', 'description' => 'Phủ lũa/đá, che lọc.', 'price' => 28000, 'quantity' => 150, 'image' => 'reu_java.webp', 'category' => 'Cây thủy sinh', 'brand' => 'AquaNature'],
            ['name' => 'Trân Châu Ngọc Trai', 'description' => 'Trải nền tiền cảnh.', 'price' => 45000, 'quantity' => 120, 'image' => 'tran_chau_ngoc_trai.webp', 'category' => 'Cây thủy sinh', 'brand' => 'AquaNature'],
            ['name' => 'Ngưu Mao Chiên', 'description' => 'Thảm cỏ xanh mịn.', 'price' => 39000, 'quantity' => 110, 'image' => 'nguu_mao_chien.webp', 'category' => 'Cây thủy sinh', 'brand' => 'AquaNature'],

            // Fish
            ['name' => 'Cá Neon', 'description' => 'Cá bơi theo đàn.', 'price' => 15000, 'quantity' => 200, 'image' => 'ca_neon.webp', 'category' => 'Cá cảnh', 'brand' => 'FishWorld'],
            ['name' => 'Cá Betta', 'description' => 'Cá xiêm đuôi đẹp.', 'price' => 35000, 'quantity' => 120, 'image' => 'ca_betta.webp', 'category' => 'Cá cảnh', 'brand' => 'FishWorld'],
            ['name' => 'Cá Bảy Màu', 'description' => 'Guppy nhiều màu.', 'price' => 12000, 'quantity' => 300, 'image' => 'ca_bay_mau.webp', 'category' => 'Cá cảnh', 'brand' => 'FishWorld'],
            ['name' => 'Cá Ranchu', 'description' => 'Cá vàng đầu lân.', 'price' => 45000, 'quantity' => 70, 'image' => 'ca_ranchu.webp', 'category' => 'Cá cảnh', 'brand' => 'FishWorld'],
            ['name' => 'Cá Chuột Cory', 'description' => 'Dọn nền hiền lành.', 'price' => 25000, 'quantity' => 120, 'image' => 'ca_chuot.webp', 'category' => 'Cá cảnh', 'brand' => 'FishWorld'],
            ['name' => 'Cá Otto', 'description' => 'Ăn rêu hiệu quả.', 'price' => 32000, 'quantity' => 90, 'image' => 'ca_otto.webp', 'category' => 'Cá cảnh', 'brand' => 'FishWorld'],
            ['name' => 'Tép RC', 'description' => 'Tép đỏ Cherry.', 'price' => 8000, 'quantity' => 400, 'image' => 'tep_rc.webp', 'category' => 'Cá cảnh', 'brand' => 'FishWorld'],

            // Equipment
            ['name' => 'Đèn LED 60cm', 'description' => 'LED cho hồ 60cm.', 'price' => 420000, 'quantity' => 20, 'image' => 'den_led_60.webp', 'category' => 'Thiết bị', 'brand' => 'AquaTech'],
            ['name' => 'Đèn LED 90cm', 'description' => 'LED công suất cao.', 'price' => 780000, 'quantity' => 10, 'image' => 'den_led_90.webp', 'category' => 'Thiết bị', 'brand' => 'AquaTech'],
            ['name' => 'Máy Lọc Mini', 'description' => 'Cho hồ nhỏ.', 'price' => 350000, 'quantity' => 15, 'image' => 'loc_mini.webp', 'category' => 'Thiết bị', 'brand' => 'AquaTech'],
            ['name' => 'Máy Lọc Thùng', 'description' => 'Dùng cho hồ 80–120L.', 'price' => 1250000, 'quantity' => 8, 'image' => 'loc_thung.webp', 'category' => 'Thiết bị', 'brand' => 'AquaTech'],
            ['name' => 'CO2 Mini Kit', 'description' => 'Bộ CO2 cơ bản.', 'price' => 690000, 'quantity' => 12, 'image' => 'co2_mini.webp', 'category' => 'Thiết bị', 'brand' => 'AquaTech'],
            ['name' => 'Phân Nước Vi Lượng', 'description' => 'Bổ sung vi lượng.', 'price' => 90000, 'quantity' => 60, 'image' => 'phan_nuoc_vi_luong.webp', 'category' => 'Thiết bị', 'brand' => 'AquaNature'],
            ['name' => 'Nền Trộn AquaSoil 3kg', 'description' => 'Hạt nền dinh dưỡng.', 'price' => 280000, 'quantity' => 30, 'image' => 'nen_aquasoil.webp', 'category' => 'Thiết bị', 'brand' => 'AquaNature'],
            ['name' => 'Sủi Khí Inox', 'description' => 'Tạo bọt mịn.', 'price' => 120000, 'quantity' => 40, 'image' => 'sui_khi_inox.webp', 'category' => 'Thiết bị', 'brand' => 'AquaTech'],
            ['name' => 'Quạt Làm Mát 2 Fan', 'description' => 'Giảm nhiệt 2–3°C.', 'price' => 230000, 'quantity' => 25, 'image' => 'quat_2fan.webp', 'category' => 'Thiết bị', 'brand' => 'AquaTech'],
            ['name' => 'Dụng Cụ Cắt Tỉa', 'description' => 'Bộ kéo + nhíp.', 'price' => 190000, 'quantity' => 35, 'image' => 'bo_cat_tia.webp', 'category' => 'Thiết bị', 'brand' => 'AquaNature'],
        ];
        foreach ($products as $p) {
            $imgPath = public_path('assets/img/products/'.($p['image'] ?? ''));
            if (empty($p['image']) || !file_exists($imgPath)) { continue; }
            Product::updateOrCreate(
                ['name' => $normalize($p['name'])],
                [
                    'description' => $p['description'],
                    'short_description' => $p['description'],
                    'long_description' => ($p['long'] ?? ("{$p['description']}\n\nNguồn gốc: nội địa. Chất lượng đã được kiểm định.\nBảo hành đổi trả trong 7 ngày.")), 
                    'specs' => $p['specs'] ?? "Kích thước: tiêu chuẩn.\nNhiệt độ phù hợp: 22-28°C.\npH: 6.5-7.5.\nĐóng gói an toàn.",
                    'care_guide' => $p['care'] ?? "Đặt nơi thoáng mát, ánh sáng phù hợp. Thay nước định kỳ, theo dõi sức khỏe cá/cây.",
                    'price' => $p['price'],
                    'quantity' => $p['quantity'],
                    'image' => $p['image'],
                    'category_id' => $categories[$p['category']] ?? null,
                    'brand_id' => $brands[$p['brand']] ?? null,
                    'created_at' => now(),
                ]
            );
        }

        // Bổ sung thêm các sản phẩm Cá cảnh (nhiều hơn)
        $moreFish = [
            ['name' => 'Cá Dĩa', 'description' => 'Cá cảnh hình dĩa, màu sắc đa dạng, hiền lành.', 'price' => 95000, 'quantity' => 60, 'image' => 'ca_dia.webp', 'category' => 'Cá cảnh', 'brand' => 'FishWorld'],
            ['name' => 'Cá La Hán Baby', 'description' => 'Đầu gù đặc trưng, phong thủy tốt.', 'price' => 120000, 'quantity' => 35, 'image' => 'ca_lahan.webp', 'category' => 'Cá cảnh', 'brand' => 'FishWorld'],
            ['name' => 'Cá Koi Mini', 'description' => 'Koi mini thích hợp hồ kính nhỏ.', 'price' => 180000, 'quantity' => 25, 'image' => 'ca_koi_mini.webp', 'category' => 'Cá cảnh', 'brand' => 'FishWorld'],
            ['name' => 'Cá Molly', 'description' => 'Dễ nuôi, sinh sản nhanh.', 'price' => 16000, 'quantity' => 180, 'image' => 'ca_molly.webp', 'category' => 'Cá cảnh', 'brand' => 'FishWorld'],
            ['name' => 'Cá Platy', 'description' => 'Màu sắc tươi, hiền.', 'price' => 14000, 'quantity' => 160, 'image' => 'ca_platy.webp', 'category' => 'Cá cảnh', 'brand' => 'FishWorld'],
            ['name' => 'Cá Bút Chì', 'description' => 'Ăn rêu tảo, hỗ trợ làm sạch bể.', 'price' => 28000, 'quantity' => 90, 'image' => 'ca_but_chi.webp', 'category' => 'Cá cảnh', 'brand' => 'FishWorld'],
        ];
        foreach ($moreFish as $p) {
            $imgPath = public_path('assets/img/products/'.($p['image'] ?? ''));
            if (empty($p['image']) || !file_exists($imgPath)) { continue; }
            Product::updateOrCreate(
                ['name' => $normalize($p['name'])],
                [
                    'description' => $p['description'],
                    'short_description' => $p['description'],
                    'long_description' => $p['description']."\n\nNguồn gốc: tuyển chọn. Vận chuyển an toàn.",
                    'specs' => "Kích thước: 3-8cm\nNhiệt độ: 24-28°C\npH: 6.5-7.5",
                    'care_guide' => "Thay nước 20-30%/tuần, cho ăn vừa đủ",
                    'price' => $p['price'],
                    'quantity' => $p['quantity'],
                    'image' => $p['image'],
                    'category_id' => $categories[$p['category']] ?? null,
                    'brand_id' => $brands[$p['brand']] ?? null,
                    'created_at' => now(),
                ]
            );
        }

        // Thêm một số sản phẩm giả (giảm còn 2 gói phụ kiện)
        for ($i=1; $i<=2; $i++) {
            Product::updateOrCreate(
                ['name' => $normalize('Gói phụ kiện thủy sinh '.$i)],
                [
                    'description' => 'Bộ phụ kiện chất lượng cho bể thủy sinh. Bao gồm cọ vệ sinh, nhíp, kéo, sủi khí mini, ống dẫn... phù hợp người mới bắt đầu.',
                    'short_description' => 'Bộ phụ kiện đầy đủ cho bể thủy sinh.',
                    'long_description' => "Bộ phụ kiện giúp bạn dễ dàng lắp đặt và chăm sóc hồ. Chất liệu inox/chống gỉ, kích thước tiêu chuẩn.\nTrong hộp: cọ vệ sinh, nhíp thẳng, nhíp cong, kéo cắt tỉa, ống dẫn khí, van 1 chiều...",
                    'specs' => "Chất liệu: Inox/nhựa.\nSố món: 5-7.\nBảo hành: 6 tháng.",
                    'care_guide' => "Vệ sinh sau khi sử dụng, bảo quản nơi khô ráo.",
                    'price' => 150000 + ($i*10000),
                    'quantity' => 20 + $i,
                    'image' => 'bo_phu_kien_'.$i.'.webp',
                    'category_id' => $categories['Thiết bị'] ?? null,
                    'brand_id' => $brands['AquaTech'] ?? null,
                    'created_at' => now(),
                ]
            );
        }

        // Bổ sung thêm các sản phẩm khác (thiết bị và cây) để phong phú danh mục
        $moreOthers = [
            // Thiết bị
            ['name' => 'Bộ Test pH Nước', 'description' => 'Bộ test nhanh chỉ số pH cho bể thuỷ sinh.', 'price' => 99000, 'quantity' => 50, 'image' => 'bo_test_ph.webp', 'category' => 'Thiết bị', 'brand' => 'AquaTech',
                'specs' => "Thang đo: 4.5-9.0\nSố lần test: ~80 lần", 'care' => 'Đậy kín, để nơi khô ráo'],
            ['name' => 'Sưởi Inox 100W', 'description' => 'Giữ ổn định nhiệt độ cho bể 60-80L.', 'price' => 170000, 'quantity' => 35, 'image' => 'suoi_100w.webp', 'category' => 'Thiết bị', 'brand' => 'AquaTech',
                'specs' => "Công suất: 100W\nBể phù hợp: 60-80L", 'care' => 'Không bật khi ngoài nước'],
            ['name' => 'Vỉ Trồng Rêu Inox', 'description' => 'Giúp cố định rêu, tạo thảm xanh thẩm mỹ.', 'price' => 45000, 'quantity' => 120, 'image' => 'vi_trong_reu.webp', 'category' => 'Thiết bị', 'brand' => 'AquaNature'],
            // Cây thủy sinh
            ['name' => 'Cỏ Nhật', 'description' => 'Cây tiền cảnh, tạo thảm xanh mượt.', 'price' => 30000, 'quantity' => 110, 'image' => 'co_nhat.webp', 'category' => 'Cây thủy sinh', 'brand' => 'AquaNature',
                'specs' => "Ánh sáng: Trung bình\nCO2: Có/khuyến nghị", 'care' => 'Cắt tỉa định kỳ, giữ nền sạch'],
            ['name' => 'Ráy Mini', 'description' => 'Dễ trồng, bám lũa/đá tốt.', 'price' => 42000, 'quantity' => 95, 'image' => 'ray_mini.webp', 'category' => 'Cây thủy sinh', 'brand' => 'AquaNature'],
        ];
        foreach ($moreOthers as $p) {
            $imgPath = public_path('assets/img/products/'.($p['image'] ?? ''));
            if (empty($p['image']) || !file_exists($imgPath)) { continue; }
            Product::updateOrCreate(
                ['name' => $normalize($p['name'])],
                [
                    'description' => $p['description'],
                    'short_description' => $p['description'],
                    'long_description' => ($p['long'] ?? ($p['description']."\n\nNguồn gốc: tiêu chuẩn. Bảo hành chất lượng.")),
                    'specs' => $p['specs'] ?? null,
                    'care_guide' => $p['care'] ?? null,
                    'price' => $p['price'],
                    'quantity' => $p['quantity'],
                    'image' => $p['image'],
                    'category_id' => $categories[$p['category']] ?? null,
                    'brand_id' => $brands[$p['brand']] ?? null,
                    'created_at' => now(),
                ]
            );
        }

        // Áp dụng một số giảm giá mẫu để trang Sale luôn có dữ liệu
        // 1) Giảm cho một số sản phẩm ngẫu nhiên
        $someSaleProducts = Product::inRandomOrder()->limit(3)->get();
        foreach ($someSaleProducts as $sp) {
            \App\Models\ProductDiscount::updateOrCreate(
                ['product_id' => $sp->id, 'start_at' => now()->subHours(1), 'end_at' => now()->addDays(3)],
                ['percent' => rand(10,25), 'note' => 'SEED']
            );
        }

        // 2) Giảm giá cho các gói phụ kiện thủy sinh
        $bundleIds = Product::where('name','like','Gói phụ kiện thủy sinh%')->pluck('id');
        foreach ($bundleIds as $pid) {
            \App\Models\ProductDiscount::updateOrCreate(
                ['product_id' => $pid, 'start_at' => now()->subHours(1), 'end_at' => now()->addDays(10)],
                ['percent' => 20, 'note' => 'Giảm gói phụ kiện']
            );
        }

        // 3) Giảm giá cho nhóm Cá cảnh
        $fishCategoryId = $categories['Cá cảnh'] ?? null;
        if ($fishCategoryId) {
            $fishProducts = Product::where('category_id',$fishCategoryId)->inRandomOrder()->limit(6)->get();
            foreach ($fishProducts as $fp) {
                \App\Models\ProductDiscount::updateOrCreate(
                    ['product_id' => $fp->id, 'start_at' => now()->subHours(1), 'end_at' => now()->addDays(5)],
                    ['percent' => rand(12,25), 'note' => 'Giảm cá cảnh']
                );
            }
        }

        // 3b) Tạo thêm một số giảm giá ngẫu nhiên cho đa dạng sản phẩm (10 sản phẩm, 7 ngày)
        $randomSale = Product::inRandomOrder()->limit(10)->get();
        foreach ($randomSale as $p) {
            $exists = DB::table('product_discounts')
                ->where('product_id',$p->id)
                ->where('end_at','>=', now())
                ->exists();
            if (!$exists) {
                \App\Models\ProductDiscount::create([
                    'product_id' => $p->id,
                    'start_at' => now()->subHours(1),
                    'end_at' => now()->addDays(7),
                    'percent' => rand(10,30),
                    'note' => 'Khuyến mãi tuần lễ',
                ]);
            }
        }

        // 3c) Giảm giá theo Thương hiệu: mỗi thương hiệu chọn 1 sản phẩm có tồn kho cao nhất
        $brandIds = DB::table('brands')->orderBy('id')->pluck('id');
        foreach ($brandIds as $bid) {
            $items = Product::where('brand_id', $bid)->where('quantity','>',0)->orderByDesc('quantity')->limit(1)->get();
            foreach ($items as $it) {
                $exists = DB::table('product_discounts')
                    ->where('product_id',$it->id)
                    ->where('end_at','>=', now())
                    ->exists();
                if (!$exists) {
                    \App\Models\ProductDiscount::create([
                        'product_id' => $it->id,
                        'start_at' => now()->subHours(1),
                        'end_at' => now()->addDays(10),
                        'percent' => rand(10,20),
                        'note' => 'Brand promo',
                    ]);
                }
            }
        }

        // 3d) Giảm giá theo Danh mục: mỗi danh mục chọn 1 sản phẩm có tồn kho cao nhất
        $cateIds = DB::table('categories')->orderBy('id')->pluck('id');
        foreach ($cateIds as $cid) {
            $items = Product::where('category_id', $cid)->where('quantity','>',0)->orderByDesc('quantity')->limit(1)->get();
            foreach ($items as $it) {
                $exists = DB::table('product_discounts')
                    ->where('product_id',$it->id)
                    ->where('end_at','>=', now())
                    ->exists();
                if (!$exists) {
                    \App\Models\ProductDiscount::create([
                        'product_id' => $it->id,
                        'start_at' => now()->subHours(1),
                        'end_at' => now()->addDays(10),
                        'percent' => rand(10,20),
                        'note' => 'Category promo',
                    ]);
                }
            }
        }

        // 3e) Giới hạn tối đa 5 sản phẩm đang giảm giá (ưu tiên tồn kho cao) và đảm bảo 1 product chỉ có 1 bản ghi
        $active = DB::table('product_discounts as pd')
            ->join('products as p','p.id','=','pd.product_id')
            ->where('pd.start_at','<=', now())
            ->where('pd.end_at','>=', now())
            ->orderByDesc('p.quantity')
            ->orderByDesc('pd.start_at')
            ->select('pd.product_id')
            ->pluck('product_id')
            ->toArray();
        // Giữ duy nhất 1 bản ghi giảm/ sản phẩm: lấy product_id đầu tiên theo sắp xếp trên
        $seen = [];
        $orderedUnique = [];
        foreach ($active as $pid) { if (!isset($seen[$pid])) { $seen[$pid]=1; $orderedUnique[]=$pid; } }
        // Cắt còn tối đa 5 sản phẩm
        $keep = array_slice($orderedUnique, 0, 5);
        DB::table('product_discounts')
            ->where('start_at','<=', now())
            ->where('end_at','>=', now())
            ->whereNotIn('product_id', $keep)
            ->delete();
        // Với các product cần giữ, xoá các bản ghi trùng (giữ bản ghi mới nhất)
        foreach ($keep as $pid) {
            $ids = DB::table('product_discounts')
                ->where('product_id',$pid)
                ->orderByDesc('start_at')
                ->pluck('id');
            if ($ids->count() > 1) {
                $idsToDelete = $ids->slice(1)->all();
                DB::table('product_discounts')->whereIn('id',$idsToDelete)->delete();
            }
        }

        // 4) Đồng bộ sản phẩm dựa trên ảnh có trong public/assets/img/products
        // Map tên file -> thông tin sản phẩm hợp lý (danh mục/brand/giá)
        $imageMap = [
            '7mau.jpg' => ['name'=>'Cá Bảy Màu 7 màu', 'category'=>'Cá cảnh', 'brand'=>'FishWorld', 'price'=>15000, 'desc'=>'Guppy nhiều màu sắc, dễ nuôi.'],
            'alivang.jpg' => ['name'=>'Cá Ali Vàng', 'category'=>'Cá cảnh', 'brand'=>'FishWorld', 'price'=>65000, 'desc'=>'Cichlid màu vàng nổi bật, khỏe mạnh.'],
            'anubias.jpg' => ['name'=>'Ráy Anubias', 'category'=>'Cây thủy sinh', 'brand'=>'AquaNature', 'price'=>45000, 'desc'=>'Cây ráy dễ trồng, bám lũa đá tốt.'],
            'bettahaftmoon.jpg' => ['name'=>'Cá Betta Halfmoon', 'category'=>'Cá cảnh', 'brand'=>'FishWorld', 'price'=>55000, 'desc'=>'Betta đuôi Halfmoon đẹp, màu sắc đa dạng.'],
            'cadia.jpg' => ['name'=>'Cá Dĩa', 'category'=>'Cá cảnh', 'brand'=>'FishWorld', 'price'=>95000, 'desc'=>'Cá hình dĩa, hiền lành, màu đẹp.'],
            'cattia.jpg' => ['name'=>'Cá Tìa Xiêm', 'category'=>'Cá cảnh', 'brand'=>'FishWorld', 'price'=>30000, 'desc'=>'Cá tìa dễ nuôi, phù hợp bể nhỏ.'],
            'cothia.jpg' => ['name'=>'Cỏ Thìa', 'category'=>'Cây thủy sinh', 'brand'=>'AquaNature', 'price'=>35000, 'desc'=>'Cây nền dễ trồng, tạo điểm nhấn.'],
            'cuba.jpg' => ['name'=>'Trân Châu Cuba', 'category'=>'Cây thủy sinh', 'brand'=>'AquaNature', 'price'=>50000, 'desc'=>'Cây tiền cảnh tạo thảm đẹp, cần CO2.'],
            'java.jpg' => ['name'=>'Dương Xỉ Java', 'category'=>'Cây thủy sinh', 'brand'=>'AquaNature', 'price'=>35000, 'desc'=>'Cây dễ trồng, chịu được môi trường đa dạng.'],
            'lahan.jpg' => ['name'=>'Cá La Hán', 'category'=>'Cá cảnh', 'brand'=>'FishWorld', 'price'=>120000, 'desc'=>'Cá đầu gù đặc trưng, phong thủy.'],
            'molly.jpg' => ['name'=>'Cá Molly', 'category'=>'Cá cảnh', 'brand'=>'FishWorld', 'price'=>16000, 'desc'=>'Cá hiền, sinh sản nhanh, nhiều màu.'],
            'Neonking.jpg' => ['name'=>'Cá Neon King', 'category'=>'Cá cảnh', 'brand'=>'FishWorld', 'price'=>22000, 'desc'=>'Neon sọc ánh kim, bơi theo đàn.'],
            'nguumaochienlun.jpg' => ['name'=>'Ngưu Mao Chiên Lùn', 'category'=>'Cây thủy sinh', 'brand'=>'AquaNature', 'price'=>39000, 'desc'=>'Cây thảm xanh mịn cho tiền cảnh.'],
            'otto.jpg' => ['name'=>'Cá Otto', 'category'=>'Cá cảnh', 'brand'=>'FishWorld', 'price'=>32000, 'desc'=>'Ăn rêu hiệu quả, hiền lành.'],
            'quatlammat.jpg' => ['name'=>'Quạt Làm Mát 2 Fan', 'category'=>'Thiết bị', 'brand'=>'AquaTech', 'price'=>230000, 'desc'=>'Giảm nhiệt 2–3°C cho bể.'],
            'suikhiinox.jpg' => ['name'=>'Sủi Khí Inox', 'category'=>'Thiết bị', 'brand'=>'AquaTech', 'price'=>120000, 'desc'=>'Tạo bọt mịn, bền đẹp.'],
            'phukienthuysinh2.jpg' => ['name'=>'Gói phụ kiện thủy sinh 2', 'category'=>'Thiết bị', 'brand'=>'AquaTech', 'price'=>190000, 'desc'=>'Bộ phụ kiện đầy đủ cho hồ.'],
            // Tên file bị mã hóa lỗi vẫn thêm được
            'phukienth�yinh1.jpg' => ['name'=>'Gói phụ kiện thủy sinh 1', 'category'=>'Thiết bị', 'brand'=>'AquaTech', 'price'=>180000, 'desc'=>'Bộ phụ kiện cơ bản cho người mới.'],
        ];
        $imgDir = public_path('assets/img/products');
        foreach ($imageMap as $file => $meta) {
            if (!file_exists($imgDir.DIRECTORY_SEPARATOR.$file)) continue;
            Product::updateOrCreate(
                ['name' => $normalize($meta['name'])],
                [
                    'description' => $meta['desc'],
                    'short_description' => $meta['desc'],
                    'long_description' => $meta['desc']."\n\nNguồn gốc: tiêu chuẩn. Bảo hành chất lượng.",
                    'specs' => null,
                    'care_guide' => null,
                    'price' => $meta['price'],
                    'quantity' => 50,
                    'image' => $file,
                    'category_id' => $categories[$meta['category']] ?? null,
                    'brand_id' => $brands[$meta['brand']] ?? null,
                    'created_at' => now(),
                ]
            );
        }

        // 5) Tự động tạo/đồng bộ sản phẩm dựa vào TÊN FILE ẢNH (nguồn dữ liệu chính)
        $files = array_values(array_filter(scandir($imgDir), function($f){
            return preg_match('/\.(jpg|jpeg|png|webp|gif)$/i', $f);
        }));
        foreach ($files as $file) {
            $name = pathinfo($file, PATHINFO_FILENAME);
            // Chuẩn hóa tên hiển thị từ tên file
            $pretty = $normalize($name);
            // Đoán danh mục/brand + giá theo từ khóa trong tên file
            $lower = strtolower($name);
            if (preg_match('/(^|\b)(ca|betta|neon|koi|molly|otto|ali|platy|guppy|7mau|discus|lahan|king)\b/', $lower)) {
                $cat = 'Cá cảnh'; $brand = 'FishWorld'; $price = 15000;
                if (preg_match('/(koi|lahan|discus|dia)/', $lower)) $price = 90000;
                elseif (preg_match('/(neon|guppy|7mau|molly|platy)/', $lower)) $price = 15000;
                elseif (preg_match('/(otto|butchi|ali)/', $lower)) $price = 30000;
            } elseif (preg_match('/(den|suoi|phukien|loc|quat|test|inox|sui|co2)/', $lower)) {
                $cat = 'Thiết bị'; $brand = 'AquaTech'; $price = 120000;
                if (preg_match('/(den|co2)/', $lower)) $price = 350000;
                if (preg_match('/(quat)/', $lower)) $price = 230000;
            } else {
                $cat = 'Cây thủy sinh'; $brand = 'AquaNature'; $price = 35000;
                if (preg_match('/(cuba|trau|co|nguu|ray|anubias|java|reu)/', $lower)) $price = 30000;
            }
            Product::updateOrCreate(
                ['image' => $file],
                [
                    'name' => $pretty,
                    'description' => 'Sản phẩm được tạo từ ảnh '.$file,
                    'short_description' => 'Tạo tự động từ ảnh',
                    'long_description' => 'Mô tả chi tiết sẽ được cập nhật sau.',
                    'price' => $price,
                    'quantity' => 50,
                    'category_id' => $categories[$cat] ?? null,
                    'brand_id' => $brands[$brand] ?? null,
                    'created_at' => now(),
                ]
            );
        }

        // 6) Xóa các sản phẩm không có ảnh thực tế
        foreach (Product::all() as $pr) {
            $path = public_path('assets/img/products/'.($pr->image ?? ''));
            if (empty($pr->image) || !file_exists($path)) {
                $pr->delete();
            }
        }

        // ------------------------------
        // Sample customers + addresses
        // ------------------------------
        $demoUser = User::where('username', 'user')->first();
        // Create a corresponding customer by email for the demo user
        $customer = Customer::firstOrCreate(
            ['email' => $demoUser?->email],
            [
                'full_name' => $demoUser?->name ?? 'Demo User',
                'phone' => '0900000000',
                'gender' => 'Khác',
                'address' => '123 Lý Thường Kiệt, Q.10, TP.HCM',
                'created_at' => now(),
            ]
        );
        Address::updateOrCreate(
            ['customer_id' => $customer->id, 'is_default' => 1],
            [
                'full_name' => $customer->full_name,
                'phone' => '0900000000',
                'address_line' => '123 Lý Thường Kiệt, P.7, Q.10, TP.HCM',
                'ward' => 'P.7',
                'district' => 'Q.10',
                'province' => 'TP.HCM',
                'is_default' => 1,
                'created_at' => now(),
            ]
        );

        // ------------------------------
        // Sample orders with details
        // ------------------------------
        $allProducts = Product::orderBy('id')->get();
        if ($allProducts->count() >= 6 && $demoUser) {
            $makeOrder = function(array $productIndexes, string $status, int $daysAgo = 0) use ($allProducts, $demoUser, $customer) {
                $total = 0;
                $items = [];
                foreach ($productIndexes as $i => $idx) {
                    $p = $allProducts[$idx % $allProducts->count()];
                    $qty = ($i % 3) + 1; // 1..3
                    $items[] = [$p, $qty];
                    $total += (float)$p->price * $qty;
                }
                $o = Order::create([
                    'user_id' => $demoUser->id,
                    'customer_id' => $customer->id,
                    'total' => $total,
                    'status' => $status, // canonical code
                    'customer_name' => $customer->full_name,
                    'customer_address' => $customer->address,
                    'created_at' => now()->subDays($daysAgo),
                ]);
                foreach ($items as [$p, $qty]) {
                    OrderDetail::create([
                        'order_id' => $o->id,
                        'product_id' => $p->id,
                        'quantity' => $qty,
                        'price' => $p->price,
                    ]);
                }
                Payment::create([
                    'order_id' => $o->id,
                    'method' => 'cod',
                    'amount' => $total,
                    'status' => $status === 'completed' ? 'paid' : ($status === 'cancelled' ? 'pending' : 'pending'),
                    'paid_at' => $status === 'completed' ? now()->subDays(max(0,$daysAgo-1)) : null,
                ]);
                Shipment::create([
                    'order_id' => $o->id,
                    'carrier' => 'local',
                    'status' => $status === 'completed' ? 'delivered' : ($status === 'shipping' ? 'shipping' : ($status === 'cancelled' ? 'cancelled' : 'pending')),
                    'shipped_at' => in_array($status, ['shipping','completed']) ? now()->subDays(max(0,$daysAgo-1)) : null,
                    'delivered_at' => $status === 'completed' ? now()->subDays(max(0,$daysAgo-2)) : null,
                ]);
                return $o;
            };

            // Create ~6 demo orders in different states
            $makeOrder([0,1], 'processing', 0);
            $makeOrder([2,3,4], 'shipping', 1);
            $makeOrder([5], 'completed', 2);
            $makeOrder([1,4], 'cancelled', 3);
            $makeOrder([0,2,5], 'completed', 5);
            $makeOrder([3,4], 'processing', 6);
        }
    }
}
