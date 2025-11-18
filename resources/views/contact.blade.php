@extends('layouts.header')
@section('title', 'Liên hệ - AquaShop')
@section('content')

<style>
  /* =============== Fade-in animation =============== */
  .fade-in {
    opacity: 0;
    transform: translateY(22px);
    transition: 0.7s ease;
  }

  .fade-in.visible {
    opacity: 1;
    transform: translateY(0);
  }

  /* =============== Premium Contact Box =============== */
  .contact-box {
    background: rgba(255, 255, 255, 0.75);
    padding: 50px;
    border-radius: 28px;
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.06);
    backdrop-filter: blur(14px);
    border: 1px solid rgba(255, 255, 255, 0.4);
    background-image:
      radial-gradient(circle at 20% 5%, rgba(0, 150, 136, .18), transparent 65%),
      radial-gradient(circle at 90% 95%, rgba(0, 150, 136, .25), transparent 70%);
  }

  /* Section title */
  .contact-title {
    font-size: 36px;
    font-weight: 800;
    text-align: center;
    background: linear-gradient(90deg, #009688, #00bfa5, #00897b);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: textGlow 5s ease infinite;
  }

  @keyframes textGlow {
    0% {
      text-shadow: 0 0 10px rgba(0, 255, 204, 0.2);
    }

    50% {
      text-shadow: 0 0 25px rgba(0, 255, 204, 0.4);
    }

    100% {
      text-shadow: 0 0 10px rgba(0, 255, 204, 0.2);
    }
  }

  /* Input fields */
  .form-control-lg {
    border-radius: 14px !important;
    padding: 12px 16px;
    box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.06);
  }

  .form-control-lg:focus {
    box-shadow: 0 0 0 4px rgba(0, 150, 136, 0.25);
  }

  /* Student info card */
  .info-card {
    border-radius: 18px;
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(0, 0, 0, 0.08);
    box-shadow: 0 8px 26px rgba(0, 0, 0, 0.07);
    transition: 0.25s;
  }

  .info-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
  }

  /* Contact image */
  .contact-img {
    border-radius: 20px;
    box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15);
    transition: 0.35s ease;
  }

  .contact-img:hover {
    transform: scale(1.03);
  }

  /* Map box */
  .map-box {
    margin-top: 30px;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
    border: 2px solid rgba(255, 255, 255, 0.6);
  }
  /* Full width map */
  .map-section {
    width: 100%;
  }

  .map-container {
    width: 100%;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 12px 28px rgba(0,0,0,0.15);
    border: 2px solid rgba(255,255,255,0.6);
  }
  /* Premium Ocean Button */
  .btn-ocean {
    background: linear-gradient(90deg, #009688, #00bfa5, #00897b);
    background-size: 200% 200%;
    color: white !important;
    border: none;
    padding: 12px 26px;
    border-radius: 14px;
    font-weight: 700;
    letter-spacing: 0.3px;
    font-size: 17px;
    transition: 0.35s ease;
    box-shadow: 0 6px 20px rgba(0, 150, 136, 0.25);
  }

  .btn-ocean:hover {
    background-position: 100% 0;
    transform: translateY(-3px);
    box-shadow: 0 8px 26px rgba(0, 150, 136, 0.35);
  }

</style>

<section class="contact-box fade-in">
  <h2 class="contact-title mb-5">Liên hệ với chúng tôi</h2>

  <div class="row g-4 align-items-start">

    <!-- FORM -->
    <div class="col-md-6 fade-in">
      @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if($errors->any())
      <div class="alert alert-danger">{{ $errors->first() }}</div>
      @endif

      <form method="POST" action="{{ route('contact.submit') }}">
        @csrf

        <div class="mb-3">
          <label class="form-label fw-semibold">Họ và tên</label>
          <input type="text" name="name" class="form-control form-control-lg"
            placeholder="Nguyễn Văn A" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Email</label>
          <input type="email" name="email" class="form-control form-control-lg"
            placeholder="example@email.com">
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Nội dung</label>
          <textarea rows="4" name="message" class="form-control form-control-lg"
            placeholder="Nhập tin nhắn của bạn..." required></textarea>
        </div>

        <button class="btn btn-ocean w-100 py-2 fw-bold" style="font-size: 18px;">
          Gửi tin nhắn
        </button>
      </form>
    </div>

    <!-- INFORMATION + IMAGE -->
    <div class="col-md-6 fade-in">
      <div class="info-card p-4 mb-4">
        <h5 class="fw-bold mb-3 text-primary">
          <i class="bi bi-people-fill me-2"></i>Nhóm thực hiện
        </h5>

        <ul class="list-unstyled mb-3">
          <li class="mb-1"><i class="bi bi-person-badge me-2 text-primary"></i>Lê Nguyễn Tuấn Đạt</li>
          <li class="mb-1"><i class="bi bi-person-badge me-2 text-primary"></i>Hồ Kiến Thức</li>
          <li class="mb-1"><i class="bi bi-person-badge me-2 text-primary"></i>Trần Hữu Thịnh</li>
        </ul>

        <div class="mb-2"><i class="bi bi-telephone me-2 text-success"></i>
          <strong>Điện thoại:</strong> 0332643954
        </div>
        <div>
          <i class="bi bi-envelope me-2 text-danger"></i>
          <strong>Email:</strong>
          <a href="mailto:datle5721@gmail.com">datle5721@gmail.com</a>
        </div>

      </div>

      @php
      $img = 'assets/img/contact_us.webp';
      $fallback = 'assets/img/hero_fish.jpg';
      $src = file_exists(public_path($img))
      ? asset($img).'?v='.filemtime(public_path($img))
      : asset($fallback);
      @endphp

      <img src="{{ $src }}" class="img-fluid contact-img mb-3" alt="Liên hệ AquaShop">
     
    </div>

  </div>
</section>
<!-- FULL WIDTH MAP SECTION -->
      <section class="map-section fade-in" style="margin-top:40px;">
        <div class="map-container">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15677.632969956579!2d106.62164381939711!3d10.806920323993774!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752be27ea41e05%3A0xfa77697a39f13ab0!2zMTQwIEzDqiBUcuG7jW5nIFThuqVuLCBUw6J5IFRo4bqhbmgsIFTDom4gUGjDuiwgVGjDoG5oIHBo4buRIEjhu5MgQ2jDrSBNaW5oLCA3MDAwMDA!5e0!3m2!1svi!2s!4v1739899545220!5m2!1svi!2s"
            width="100%"
            height="420"
            style="border:0;"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
          </iframe>
        </div>
      </section>


@include('layouts.footer')

<!-- Fade-in on scroll script -->
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const els = document.querySelectorAll(".fade-in");
    const obs = new IntersectionObserver(entries => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          e.target.classList.add("visible");
        }
      });
    }, {
      threshold: 0.15
    });
    els.forEach(el => obs.observe(el));
  });
</script>

@endsection