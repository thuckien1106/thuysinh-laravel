</main>

<style>
/* ===================== PREMIUM FOOTER ===================== */
.footer-ocean {
  background: linear-gradient(135deg, #0091ea, #0064b7);
  color: #e3f2fd;
  padding: 50px 0 40px;
  margin-top: 40px;
  position: relative;
  border-top: 1px solid rgba(255,255,255,0.15);
  box-shadow: 0 -8px 20px rgba(0,0,0,0.06);
}

/* Glass top line */
.footer-ocean::before {
  content: "";
  position: absolute;
  top: -1px;
  left: 0;
  width: 100%;
  height: 6px;
  background: rgba(255,255,255,0.35);
  backdrop-filter: blur(4px);
}

/* Links */
.footer-ocean a {
  color: #bbdefb;
  font-weight: 500;
  transition: .25s ease;
  text-decoration: none;
}
.footer-ocean a:hover {
  color: #fff;
  text-shadow: 0 0 10px rgba(255,255,255,0.5);
}

/* Footer title */
.footer-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: #ffffff;
  margin-bottom: 12px;
}

/* Social icons */
.footer-social a {
  display: inline-flex;
  width: 38px;
  height: 38px;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  margin-right: 8px;
  background: rgba(255,255,255,0.12);
  color: #fff;
  font-size: 18px;
  transition: .25s ease;
}
.footer-social a:hover {
  background: #ffffff;
  color: #0064b7;
  transform: translateY(-3px);
  box-shadow: 0 4px 14px rgba(255,255,255,0.25);
}

.footer-bottom {
  margin-top: 25px;
  padding-top: 15px;
  font-size: 14px;
  border-top: 1px solid rgba(255,255,255,0.18);
  color: #e1f5fe;
  text-align: center; /* Căn trái */
}

/* Responsive */
@media (max-width:768px){
  .footer-ocean {
    text-align:left; /* Vẫn căn trái trên mobile */
  }
  .footer-social a {
    margin-bottom: 10px;
  }
}
</style>

<footer class="footer-ocean">
  <div class="container">

    <div class="row g-4">
      <!-- About -->
      <div class="col-md-4 fade-in" style="text-align:left;">
        <h4 class="footer-title">AquaShop 🌿</h4>
        <p>
          Thế giới thủy sinh ngay trong chính ngôi nhà của bạn.  
          Cung cấp cây – cá – phụ kiện chất lượng và dịch vụ tư vấn tận tâm.
        </p>
      </div>

      <!-- Links -->
      <div class="col-md-4 fade-in" style="text-align:left;">
        <h4 class="footer-title">Liên kết nhanh</h4>
        <p class="mb-1"><a href="{{ route('home') }}">Trang chủ</a></p>
        <p class="mb-1"><a href="{{ route('about') }}">Giới thiệu</a></p>
        <p class="mb-1"><a href="{{ route('contact') }}">Liên hệ</a></p>
        <p class="mb-1"><a href="{{ route('products.index') }}">Sản phẩm</a></p>
        <p class="mb-1"><a href="{{ route('products.sale') }}">Ưu đãi</a></p>
      </div>

      <!-- Social -->
      <div class="col-md-4 fade-in" style="text-align:left;">
        <h4 class="footer-title">Kết nối với chúng tôi</h4>
        <div class="footer-social">
          <a href="#"><i class="bi bi-facebook"></i></a>
          <a href="#"><i class="bi bi-instagram"></i></a>
          <a href="#"><i class="bi bi-youtube"></i></a>
          <a href="#"><i class="bi bi-envelope-fill"></i></a>
        </div>
      </div>
    </div>

    <!-- Bottom -->
    <div class="footer-bottom fade-in">
      © {{ date('Y') }} AquaShop. All rights reserved.  
      <br>
      Made with ❤️ for the aquarist community.
    </div>

  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
