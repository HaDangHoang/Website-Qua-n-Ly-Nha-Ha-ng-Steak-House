<!-- Back to Top Button -->
<a class="js-page-scrolling back-to-top" href="#top" style="display: inline;">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-chevron-up"
        viewBox="0 0 16 16">
        <path fill-rule="evenodd"
            d="M7.646 4.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1-.708.708L8 5.707l-5.646 5.647a.5.5 0 0 1-.708-.708l6-6z">
        </path>
    </svg>
</a>
<script>
    $(window).scroll(function() {
        // Kiểm tra khi cuộn xuống dưới 200px so với đầu trang hoặc chiều cao trang
        if ($(this).scrollTop() > 200 || $(document).height() > $(window).height()) {
            $('.js-page-scrolling').fadeIn();
        } else {
            $('.js-page-scrolling').fadeOut();
        }
    });

    // Smooth scroll to top when clicking the "back to top" button
    $('.js-page-scrolling').click(function() {
        $('html, body').animate({
            scrollTop: 0
        }, 300);
        return false;
    });
</script>
<style>
   .js-page-scrolling {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 50%;
    cursor: pointer;
    display: none;
    z-index: 1000;
    transition: all 0.3s ease;
}

.js-page-scrolling:hover {
    background-color: #0056b3;
}

/* Đảm bảo nút luôn hiển thị ở vị trí bên dưới */
body {
    position: relative;
    padding-bottom: 50px;  /* Điều chỉnh cho phần cuộn xuống */
}

</style>
