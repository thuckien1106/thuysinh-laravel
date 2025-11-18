@extends('layouts.header')
@section('title', 'Giới thiệu - AquaShop')
@section('content')

<style>
/* ====================== Fade-in on scroll ====================== */
.fade-in {
  opacity: 0;
  transform: translateY(25px);
  transition: 0.7s ease;
}
.fade-in.visible {
  opacity: 1;
  transform: translateY(0);
}

/* ====================== Section Wrapper (Glass) ====================== */
.about-box {
  padding: 60px;
  border-radius: 28px;
  background: rgba(255,255,255,0.75);
  backdrop-filter: blur(14px);
  border: 1px solid rgba(255,255,255,0.5);
  box-shadow: 0 15px 35px rgba(0,0,0,0.08);
  background-image:
    radial-gradient(circle at top left, rgba(0,150,136,0.18), transparent 40%),
    radial-gradient(circle at bottom right, rgba(0,150,136,0.25), transparent 55%);
}

/* ====================== Title Gradient ====================== */
.about-title {
  font-size: 36px;
  font-weight: 800;
  background: linear-gradient(90deg,#009688,#00bfa5,#00897b);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-size: 220% 220%;
  animation: titleFlow 6s ease infinite;
}
@keyframes titleFlow {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

/* ====================== Image ====================== */
.about-img {
  border-radius: 22px;
  box-shadow: 0 12px 28px rgba(0,0,0,0.18);
  transform: scale(1);
  transition: .35s ease;
}
.about-img:hover {
  transform: scale(1.04);
}

/* ====================== Ocean Button ====================== */
.btn-ocean {
  background: linear-gradient(90deg,#009688,#00bfa5);
  border: none;
  padding: 13px 30px;
  border-radius: 40px;
  font-weight: 600;
  color: white;
  transition: .25s ease;
}
.btn-ocean:hover {
  background: linear-gradient(90deg,#00bfa5,#009688);
  transform: translateY(-3px);
}

/* ====================== Timeline ====================== */
.timeline-container {
  max-width: 750px;
  margin: auto;
  position: relative;
  padding-left: 20px;
}
.timeline-line {
  position: absolute;
  top: 0;
  left: 12px;
  width: 4px;
  height: 100%;
  background: #00bfa5;
  border-radius: 4px;
}
.timeline-item {
  padding-left: 45px;
  position: relative;
  margin-bottom: 40px;
}
.timeline-dot {
  width: 18px;
  height: 18px;
  border-radius: 50%;
  position: absolute;
  left: 5px;
  top: 0;
  box-shadow: 0 0 8px rgba(0,0,0,0.2);
}
</style>

<!-- ========================= MAIN INTRODUCTION ========================= -->
<section class="about-box fade-in mb-5">
  <div class="row align-items-center">

    <div class="col-md-6 fade-in">
      <h2 class="about-title mb-3">Về AquaShop</h2>

      <p class="text-secondary mb-3" style="font-size: 17px;">
        AquaShop ra đời với mong muốn mang đến một thế giới thu nhỏ đầy sức sống
        ngay trong ngôi nhà của bạn. Chúng tôi tin rằng thủy sinh không chỉ là thú chơi,
        mà còn là liệu pháp tinh thần giúp bạn thư giãn sau những giờ phút mệt mỏi.
      </p>

      <p class="text-secondary mb-4" style="font-size: 17px;">
        Với đa dạng cây thủy sinh, cá cảnh và phụ kiện chất lượng cao,
        chúng tôi đồng hành cùng bạn trong từng bước tạo dựng hồ cá hoàn hảo –
        từ thiết kế đến chăm sóc dài lâu.
      </p>

      <a href="{{ route('contact') }}" class="btn btn-ocean">
        Liên hệ ngay
      </a>
    </div>

    <div class="col-md-6 text-center fade-in">
      <img src="{{ asset('assets/img/logo.png') }}"
           alt="AquaShop"
           class="img-fluid about-img"
           style="max-width: 75%;">
    </div>

  </div>
</section>

<!-- ========================= MISSION - VISION - VALUES ========================= -->
<section class="fade-in my-5">
  <h2 class="about-title text-center mb-4">Sứ mệnh – Tầm nhìn – Giá trị cốt lõi</h2>

  <div class="row g-4">

    <div class="col-md-4">
      <div class="p-4 rounded-4 shadow-sm h-100"
           style="background:rgba(255,255,255,0.8); backdrop-filter:blur(10px);">
        <h5 class="fw-bold text-primary mb-2">
          <i class="bi bi-flag-fill me-2"></i> Sứ mệnh
        </h5>
        <p class="text-secondary">
          Mang đến sản phẩm thủy sinh chất lượng,
          tạo nên không gian sống xanh và thư giãn cho mọi gia đình.
        </p>
      </div>
    </div>

    <div class="col-md-4">
      <div class="p-4 rounded-4 shadow-sm h-100"
           style="background:rgba(255,255,255,0.8); backdrop-filter:blur(10px);">
        <h5 class="fw-bold text-success mb-2">
          <i class="bi bi-eye-fill me-2"></i> Tầm nhìn
        </h5>
        <p class="text-secondary">
          Trở thành thương hiệu dẫn đầu về thủy sinh tại Việt Nam
          với trải nghiệm khách hàng tuyệt vời nhất.
        </p>
      </div>
    </div>

    <div class="col-md-4">
      <div class="p-4 rounded-4 shadow-sm h-100"
           style="background:rgba(255,255,255,0.8); backdrop-filter:blur(10px);">
        <h5 class="fw-bold text-info mb-2">
          <i class="bi bi-gem me-2"></i> Giá trị cốt lõi
        </h5>
        <p class="text-secondary">
          Uy tín – Chất lượng – Tận tâm – Sáng tạo trong từng sản phẩm & dịch vụ.
        </p>
      </div>
    </div>

  </div>
</section>

<!-- ========================= WHY CHOOSE US ========================= -->
<section class="my-5 fade-in">
  <h2 class="about-title text-center mb-4">Tại sao chọn AquaShop?</h2>

  <div class="row g-4 text-center">

    <div class="col-md-3">
      <div class="p-4 rounded-4 shadow-sm h-100">
        <i class="bi bi-patch-check-fill fs-1 text-primary mb-3"></i>
        <h5 class="fw-bold">Sản phẩm chất lượng</h5>
        <p class="text-secondary">Cây – cá – phụ kiện được tuyển chọn kỹ lưỡng.</p>
      </div>
    </div>

    <div class="col-md-3">
      <div class="p-4 rounded-4 shadow-sm h-100">
        <i class="bi bi-lightning-charge-fill fs-1 text-warning mb-3"></i>
        <h5 class="fw-bold">Giao hàng nhanh</h5>
        <p class="text-secondary">Đóng gói kỹ – vận chuyển an toàn toàn quốc.</p>
      </div>
    </div>

    <div class="col-md-3">
      <div class="p-4 rounded-4 shadow-sm h-100">
        <i class="bi bi-heart-pulse-fill fs-1 text-danger mb-3"></i>
        <h5 class="fw-bold">Tư vấn tận tâm</h5>
        <p class="text-secondary">Hỗ trợ chọn cây – cá, setup hồ theo nhu cầu.</p>
      </div>
    </div>

    <div class="col-md-3">
      <div class="p-4 rounded-4 shadow-sm h-100">
        <i class="bi bi-brush-fill fs-1 text-success mb-3"></i>
        <h5 class="fw-bold">Thiết kế hồ chuyên nghiệp</h5>
        <p class="text-secondary">Tạo hồ cá thẩm mỹ – bền vững – dễ chăm.</p>
      </div>
    </div>

  </div>
</section>

<!-- ========================= TIMELINE ========================= -->
<section class="my-5 fade-in">
  <h2 class="about-title text-center mb-4">Hành trình phát triển AquaShop</h2>

  <div class="timeline-container">
    <div class="timeline-line"></div>

    <div class="timeline-item fade-in">
      <div class="timeline-dot bg-primary"></div>
      <h5 class="fw-bold text-primary">2019 – Khởi đầu</h5>
      <p class="text-secondary">
        AquaShop được thành lập với mong muốn lan tỏa thú chơi thủy sinh xanh – sạch – đẹp.
      </p>
    </div>

    <div class="timeline-item fade-in">
      <div class="timeline-dot bg-success"></div>
      <h5 class="fw-bold text-success">2021 – Mở rộng</h5>
      <p class="text-secondary">
        Mở rộng kho hàng, hợp tác nhiều nhà cung cấp uy tín trong và ngoài nước.
      </p>
    </div>

    <div class="timeline-item fade-in">
      <div class="timeline-dot bg-info"></div>
      <h5 class="fw-bold text-info">2023 – Bứt phá</h5>
      <p class="text-secondary">
        Website nâng cấp hoàn toàn, phục vụ hơn hàng nghìn khách hàng mỗi tháng.
      </p>
    </div>

    <div class="timeline-item fade-in">
      <div class="timeline-dot bg-warning"></div>
      <h5 class="fw-bold text-warning">2025 – Tương lai</h5>
      <p class="text-secondary">
        Tiếp tục phát triển hệ sinh thái thủy sinh toàn diện và bền vững.
      </p>
    </div>

  </div>
</section>

@include('layouts.footer')

<!-- ========================= Scroll Animation Script ========================= -->
<script>
document.addEventListener("DOMContentLoaded", function() {
  const elements = document.querySelectorAll(".fade-in");

  const observer = new IntersectionObserver(entries => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        e.target.classList.add("visible");
      }
    });
  }, { threshold: 0.15 });

  elements.forEach(el => observer.observe(el));
});
</script>

@endsection
