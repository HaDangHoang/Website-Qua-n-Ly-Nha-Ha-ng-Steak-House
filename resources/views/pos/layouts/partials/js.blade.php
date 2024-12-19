<!-- Tải jQuery trước -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS nếu sử dụng -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<!-- Các thư viện và tệp JavaScript khác -->
<script src="{{ asset('poss/assets/js/backend-bundle.min.js') }}"></script>
<script src="{{ asset('poss/assets/js/flex-tree.min.js') }}"></script>
<script src="{{ asset('poss/assets/js/tree.js') }}"></script>
<script src="{{ asset('poss/assets/js/table-treeview.js') }}"></script>
<script src="{{ asset('poss/assets/js/masonry.pkgd.min.js') }}"></script>
<script src="{{ asset('poss/assets/js/imagesloaded.pkgd.min.js') }}"></script>
<script src="{{ asset('poss/assets/js/mapbox-gl.js') }}"></script>
<script src="{{ asset('poss/assets/js/mapbox.js') }}"></script>
<script src="{{ asset('poss/assets/vendor/fullcalendar/core/main.js') }}"></script>
<script src="{{ asset('poss/assets/vendor/fullcalendar/daygrid/main.js') }}"></script>
<script src="{{ asset('poss/assets/vendor/fullcalendar/timegrid/main.js') }}"></script>
<script src="{{ asset('poss/assets/vendor/fullcalendar/list/main.js') }}"></script>
<script src="{{ asset('poss/assets/js/sweetalert.js') }}"></script>
<script src="{{ asset('poss/assets/js/vector-map-custom.js') }}"></script>
<script src="{{ asset('poss/assets/js/customizer.js') }}"></script>
<script src="{{ asset('poss/assets/js/chart-custom.js') }}"></script>
<script src="{{ asset('poss/assets/js/slider.js') }}"></script>
<script src="{{ asset('poss/assets/js/app.js') }}"></script>



<script>
    document.addEventListener("DOMContentLoaded", function() {
        const printButton = document.getElementById("printButton");
        const printDropdownForm = document.getElementById("printDropdownForm");
        const hamburgerMenu = document.getElementById("hamburgerMenu");
        const dropdownMenu = document.getElementById("dropdownMenu");
        const bellButton = document.querySelector('.fa-bell');
        const notificationDropdown = document.createElement('div');

        document.body.appendChild(notificationDropdown);

        // Điều khiển hiển thị dropdown của chuông thông báo
        bellButton.addEventListener("click", function(event) {
            event.stopPropagation();
            toggleDropdown(notificationDropdown);
            hideDropdown(printDropdownForm);
            hideDropdown(dropdownMenu);
        });

        // Toggle print form
        printButton.addEventListener("click", function(event) {
            event.stopPropagation();
            toggleDropdown(printDropdownForm);
            hideDropdown(dropdownMenu);
            hideDropdown(notificationDropdown);
        });

        // Toggle hamburger menu
        hamburgerMenu.addEventListener("click", function(event) {
            event.stopPropagation();
            toggleDropdown(dropdownMenu);
            hideDropdown(printDropdownForm);
            hideDropdown(notificationDropdown);
        });

        // Close all menus when clicking outside
        window.addEventListener("click", function() {
            hideDropdown(printDropdownForm);
            hideDropdown(dropdownMenu);
            hideDropdown(notificationDropdown);
        });

        // Add functionality to Confirm and Cancel buttons
        document.getElementById("confirmButton").addEventListener("click", function() {
            hideDropdown(printDropdownForm);
            alert('Hóa đơn đã được chọn!');
        });

        document.getElementById("cancelButton").addEventListener("click", function() {
            hideDropdown(printDropdownForm);
        });

        // Hàm chung để hiển thị/ẩn dropdown
        function toggleDropdown(element) {
            if (element.style.display === "none" || element.style.display === "") {
                element.style.display = "block";
            } else {
                element.style.display = "none";
            }
        }

        // Hàm để ẩn dropdown
        function hideDropdown(element) {
            element.style.display = "none";
        }

        // Ngăn chặn đóng khi nhấp vào bên trong dropdown
        printDropdownForm.addEventListener("click", function(event) {
            event.stopPropagation();
        });

        dropdownMenu.addEventListener("click", function(event) {
            event.stopPropagation();
        });

        notificationDropdown.addEventListener("click", function(event) {
            event.stopPropagation();
        });
    });
</script>

<script>
    $(document).ready(function() {
        const tableViewButton = $('#table-view-button');
        const menuViewButton = $('#menu-view-button');
        const tableSection = $('#table-section');
        const menuSection = $('#menu-section');
        const loadingElement = $('#loading');
        const orderDetails = $('#order-details');
        const totalPriceElement = $('#totalAmount');
        const searchTable = $('#searchTable');
        const searchInput = $('#searchInput');

        let orderId = null;
        let currentOrder = {};
        let totalAmount = 0;

        function showLoading(isLoading) {
            loadingElement.css('display', isLoading ? 'block' : 'none');
        }

        function showNotification(message, type = 'success') {
            Swal.fire({
                icon: type,
                title: 'Thông báo',
                text: message,
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }

        tableViewButton.click(function() {
            menuSection.hide();
            tableSection.show();
            tableViewButton.addClass('active');
            menuViewButton.removeClass('active');
            searchInput.hide();
            searchTable.show();
        });

        menuViewButton.click(function() {
            tableSection.hide();
            menuSection.show();
            menuViewButton.addClass('active');
            tableViewButton.removeClass('active');
            searchInput.show();
            searchTable.hide();
        });
        $(document).ready(function() {
            $('#searchInput').on('keyup', function() {
                var searchValue = $(this).val().toLowerCase()
                    .trim();
                $('#dish-list .dish-item').filter(function() {
                    var dishName = $(this).find('.card-title').text()
                        .toLowerCase();
                    var dishPrice = $(this).data('dish-price')
                        .toString();
                    $(this).toggle(
                        dishName.indexOf(searchValue) > -1 || dishPrice.indexOf(
                            searchValue) > -1
                    );
                });
            });
        });
        $(document).ready(function() {
            $('#searchTable').on('keyup', function() {
                var searchValue = $(this).val()
                    .toLowerCase();
                $('.table-container .table-card').filter(function() {
                    var tableNumber = $(this).find('.table-number').text()
                .toLowerCase();
                    var orders = $(this).find('span:not(.table-number)').text()
                        .toLowerCase();
                    var dataOrderId = $(this).attr('data-order-id')?.toLowerCase() ||
                    '';

                    $(this).toggle(
                        tableNumber.indexOf(searchValue) > -1 ||
                        orders.indexOf(searchValue) > -1 ||
                        dataOrderId.indexOf(searchValue) > -1
                    );
                });

            });
        });
        $('.filter-btn').click(function() {
            const status = $(this).data('status');
            $('.table-card').each(function() {
                $(this).toggle(status === 'all' || $(this).hasClass(status));
            });
            $('.filter-btn').removeClass('active');
            $(this).addClass('active');
        });
        $(document).ready(function() {
            $(".filter-btnn").on("click", function() {
                const selectedCategory = $(this).data("category");
                $(".filter-btnn").removeClass("active");
                $(this).addClass("active");
                if (selectedCategory === "all") {
                    $(".dish-item").show();
                } else {
                    $(".dish-item").hide();
                    $(`.dish-item[data-category="${selectedCategory}"]`).show();
                }
            });
        });

    });
</script>
