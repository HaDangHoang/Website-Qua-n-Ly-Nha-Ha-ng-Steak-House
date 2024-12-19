<!-- Nơi Chứa Thông Báo - Hoàng -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<style>
    @keyframes gradientMove {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    .swal2-timer-progress-bar {
        background: linear-gradient(90deg, #34eb4f, #00bcd4, #ffa726, #ffeb3b, #f44336);

        background-size: 300% 300%;

        animation: gradientMove 2s ease infinite;

    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Hiển thị thông báo lỗi
        @if ($errors->any())
            Swal.fire({
                position: "top-end",
                icon: "error",
                toast: true,
                title: "{{ $errors->first() }}",
                showConfirmButton: false,
                timerProgressBar: true,
                timer: 2500
            });
        @endif

        // Hiển thị thông báo thành công
        @if (session('success'))
            Swal.fire({
                position: "top-end",
                icon: "success",
                toast: true,
                title: "{{ session('success') }}",
                showConfirmButton: false,
                timerProgressBar: true,
                timer: 2500
            });
        @endif
    });
</script>
