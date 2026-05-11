</main>
<!-- MAIN CONTENT END -->

<!-- ===== FOOTER ===== -->
<footer class="site-footer">
    <div class="footer-main">
        <div class="container">
            <div class="footer-grid">
                <!-- About -->
                <div class="footer-col footer-about">
                    <div class="footer-logo">
                        <div class="logo-mark">L</div>
                        <div class="logo-text">
                            <span class="logo-name">LUXE</span>
                            <span class="logo-sub">Beauty</span>
                        </div>
                    </div>
                    <p>Chúng tôi cam kết mang đến những sản phẩm mỹ phẩm cao cấp, chính hãng với giá tốt nhất cho phái đẹp Việt Nam.</p>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-tiktok"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="footer-col">
                    <h4 class="footer-heading">Danh Mục</h4>
                    <ul class="footer-links">
                        <li><a href="<?= url('category/cham-soc-da-mat') ?>">Chăm Sóc Da Mặt</a></li>
                        <li><a href="<?= url('category/son-moi') ?>">Son Môi</a></li>
                        <li><a href="<?= url('category/mat') ?>">Mắt</a></li>
                        <li><a href="<?= url('category/nen-che-khuyet-diem') ?>">Nền & Che Khuyết Điểm</a></li>
                        <li><a href="<?= url('category/chong-nang') ?>">Chống Nắng</a></li>
                    </ul>
                </div>

                <!-- Support -->
                <div class="footer-col">
                    <h4 class="footer-heading">Hỗ Trợ</h4>
                    <ul class="footer-links">
                        <li><a href="#">Hướng Dẫn Mua Hàng</a></li>
                        <li><a href="#">Chính Sách Đổi Trả</a></li>
                        <li><a href="#">Chính Sách Bảo Mật</a></li>
                        <li><a href="#">Câu Hỏi Thường Gặp</a></li>
                        <li><a href="#">Liên Hệ</a></li>
                    </ul>
                </div>

                <!-- Contact & Newsletter -->
                <div class="footer-col">
                    <h4 class="footer-heading">Liên Hệ</h4>
                    <div class="footer-contact">
                        <p><i class="fas fa-map-marker-alt"></i> 123 Nguyễn Huệ, Q.1, TP.HCM</p>
                        <p><i class="fas fa-phone-alt"></i> 1800 6868</p>
                        <p><i class="fas fa-envelope"></i> contact@beautyshop.vn</p>
                        <p><i class="fas fa-clock"></i> 8:00 - 22:00 (Tất cả các ngày)</p>
                    </div>
                    <h4 class="footer-heading" style="margin-top:1.5rem">Nhận Ưu Đãi</h4>
                    <form class="newsletter-form" id="newsletterForm">
                        <input type="email" placeholder="Email của bạn..." required>
                        <button type="submit"><i class="fas fa-paper-plane"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment logos -->
    <div class="footer-payment">
        <div class="container">
            <span>Thanh toán:</span>
            <div class="payment-logos">
                <span class="payment-badge">COD</span>
                <span class="payment-badge">MoMo</span>
                <span class="payment-badge">Bank Transfer</span>
                <span class="payment-badge">Visa</span>
                <span class="payment-badge">Mastercard</span>
            </div>
        </div>
    </div>

    <!-- Copyright -->
    <div class="footer-bottom">
        <div class="container">
            <p>&copy; <?= date('Y') ?> LUXE Beauty. All rights reserved. Designed with ❤️</p>
        </div>
    </div>
</footer>

<!-- ===== BACK TO TOP ===== -->
<button class="back-to-top" id="backToTop" title="Về đầu trang">
    <i class="fas fa-chevron-up"></i>
</button>


<!-- JS -->
<script>
    const APP_URL      = '<?= APP_URL ?>';
    const CART_URL     = '<?= url('api/cart') ?>';
    const CART_PAGE_URL = '<?= url('cart') ?>';
    const SEARCH_URL   = '<?= url('api/search') ?>';
    const WISHLIST_URL = '<?= url('api/wishlist') ?>';
</script>
<script src="<?= asset('js/main.js') ?>?v=<?= time() ?>"></script>
</body>
</html>
