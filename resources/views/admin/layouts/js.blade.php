<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  {{-- import file --}}
  <script>
    document.getElementById('import-button').addEventListener('click', function() {
        // Kích hoạt input file khi nhấn nút
        document.getElementById('import-file').click();
    });

    // Khi file được chọn, tự động submit form
    document.getElementById('import-file').addEventListener('change', function() {
        this.closest('form').submit();
    });
</script>

  <!-- jQuery 3 -->
  <script src="../assets/js/jquery.min.js"></script>
  <script src="../assets/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/js/modernizr.js"></script>
  <script src="../assets/js/moment.js"></script>

  <!-- Vendor Js Files -->
  <script src="../assets/vendor/overlay-scroll/jquery.overlayScrollbars.min.js"></script>
  <script src="../assets/vendor/overlay-scroll/custom-scrollbar.js"></script>

  <!-- Apex Charts -->
  <script src="../assets/vendor/apex/apexcharts.min.js"></script>
  <script src="../assets/vendor/apex/custom/sales/salesGraph.js"></script>
  <script src="../assets/vendor/apex/custom/sales/revenueGraph.js"></script>
  <script src="../assets/vendor/apex/custom/sales/taskGraph.js"></script>

  <!-- Main Js Required -->
  <script src="../assets/js/main.js"></script>
  <script>
    const button = document.getElementById('heartButton');

button.addEventListener('click', function () {
    this.classList.toggle('clicked');
});

</script>

{{-- Thông báo --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Hàm hiển thị thông báo
        function showNotification(message, type = "success") {
            const notification = document.getElementById("notification");
            const notificationIcon = notification.querySelector(".notification-icon i");
            const notificationTitle = document.getElementById("notification-title");
            const notificationMessage = document.getElementById("notification-message");

            // Cập nhật nội dung thông báo
            notificationMessage.textContent = message;

            // Đặt kiểu thông báo
            if (type === "success") {
                notification.style.background = "linear-gradient(90deg, #58ade8, #48d1cc)";
                notification.style.color = "#ffffff";
                notificationIcon.className = "bi bi-check-circle-fill icon-animate";
                notificationTitle.textContent = "Thành công!";
            } else if (type === "error") {
                notification.style.background = "linear-gradient(90deg, #f44336, #ff6347)";
                notification.style.color = "#ffffff";
                notificationIcon.className = "bi bi-x-circle-fill icon-animate";
                notificationTitle.textContent = "Lỗi!";
            }

            // Hiển thị thông báo
            notification.classList.remove("d-none");
            notification.classList.add("show");

            // Ẩn thông báo sau 3 giây
            setTimeout(() => {
                notification.classList.remove("show");
                notification.classList.add("hide");

                // Reset sau khi ẩn
                setTimeout(() => {
                    notification.classList.add("d-none");
                    notification.classList.remove("hide");
                    notificationIcon.classList.remove("icon-animate");
                }, 300);
            }, 3000);
        }

        // Hiển thị thông báo từ session
        @if (session('success'))
            showNotification("{{ session('success') }}", "success");
        @endif

        @if (session('error'))
            showNotification("{{ session('error') }}", "error");
        @endif
    });
</script>
