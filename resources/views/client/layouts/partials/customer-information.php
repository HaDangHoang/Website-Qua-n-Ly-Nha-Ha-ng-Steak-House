<script>

    document.getElementById('booking-form').addEventListener('submit', function (event) {
        const guestCount = parseInt(document.getElementById('guest_count').value);
        if (guestCount > 6) {
            event.preventDefault(); 
            Swal.fire({
                title: "Vui lòng xác nhận!",
                text: "Cần phải đặt cọc cho những đơn đặt bàn lớn hơn 6!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                cancelButtonText: "Hủy",
                confirmButtonText: "Đồng ý"
            }).then((result) => {
                if (result.isConfirmed) {

                    document.getElementById('booking-form').submit(); 

                }
            });
        }
    });
</script>
