<!-- CSS Files
    ================================================== -->
<link rel="stylesheet" href="client/css/bootstrap.css" type="text/css">
<link rel="stylesheet" href="client/css/plugins.css" type="text/css">
<link rel="stylesheet" href="client/css/style.css" type="text/css">
<link rel="stylesheet" href="client/css/coloring.css" type="text/css">

<!-- css for scheme color -->
<link rel="stylesheet" href="client/css/colors/cream.css" type="text/css" id="colors">

<!-- custom css -->
<link rel="stylesheet" href="client/css/03_custom.css" type="text/css">

<!-- Slider Revolution Stylesheet -->
<link rel="stylesheet" type="text/css" href="client/revolution/css/settings.css">
<link rel="stylesheet" type="text/css" href="client/revolution/css/layers.css">
<link rel="stylesheet" type="text/css" href="client/revolution/css/navigation.css">
<link rel="stylesheet" type="text/css" href="client/revolution/css/rev-settings.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="{{ asset('adminn/assets/fonts/bootstrap/bootstrap-icons.css') }}">


<style>
       /* paginate */
       .my-pagination {
    display: flex;
    justify-content: center;
    list-style: none;
    padding: 0;
    margin: 20px 0;
}

    .status-pending {
        color: #ffffff; /* Chữ màu trắng */
        background-color: #87ceeb; /* Nền xanh da trời */
        padding: 5px 10px; /* Khoảng cách bên trong */
        border-radius: 5px; /* Bo góc cho ô vuông */
        font-weight: bold; /* Chữ in đậm */
        border: none; /* Loại bỏ viền */
        cursor: pointer; /* Con trỏ dạng nút bấm */
        display: inline-block; /* Hiển thị dạng inline-block */
    }

  

    .status-pending-refund {
        color: #ffffff; 
        background-color: #87ceeb; 
        padding: 5px 10px; 
        border-radius: 5px; 
        display: inline-block; 
        font-weight: bold; 
    }

    input {
        background-color: white !important;
    }

    .my-pagination li {
        margin: 0 5px;
    }

.my-pagination a {
    padding: 10px 15px;
    color: #f1c40f;
    background-color: #1b1b1b;
    border: 1px solid #f1c40f;
    text-decoration: none;
    border-radius: 5px;
    transition: all 0.3s ease;
}

.my-pagination a:hover {
    background-color: #f1c40f;
    color: #1b1b1b;
}

.my-pagination .active span {
    background-color: #f1c40f;
    color: #1b1b1b;
    padding: 10px 15px;
    border-radius: 5px;
}

    /* Trạng thái của đơn đặt bàn  */
    .status-confirmed {
    background-color: #28a745; /* Xanh lá */
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    display: inline-block;
}

.status-pending {
    background-color: #ffc107; /* Vàng */
    color: black;
    padding: 5px 10px;
    border-radius: 5px;
    display: inline-block;
}

.status-cancelled {
    background-color: #dc3545; /* Đỏ */
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    display: inline-block;
}

.status-refund {
    background-color: #17a2b8; /* Xanh dương nhạt */
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    display: inline-block;
}

.status-completed {
    background-color: #007bff; /* Xanh dương đậm */
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    display: inline-block;
}


    /* login  */
    .login-container {
        max-width: 400px;
        margin: 100px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }

    .btn-option {
        display: inline-block;
        width: 48%;
        text-align: center;
        cursor: pointer;
    }

    .btn-option.active {
        background-color: #FFB347;
        color: white;
    }
    .hover-text:hover{
        color: #f1c40f  !important;
        
    }
    .otp-input {
        width: 50px;
        height: 50px;
        text-align: center;
        margin: 5px;
        font-size: 24px;
    }

    .profile-section {
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 8px;
        margin: 20px;
    }

    .upcoming-reservations .reservation-item {
        margin-bottom: 20px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
    }

    .reservation-details {
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        margin: 20px;
    }

    .reservation-details .actions {
        margin-top: 20px;
    }

    .reservation-details .actions .btn {
        margin-right: 10px;
    }

    .additional-info {
        margin-top: 20px;
    }


    /* General container styling */
    .container-fluid {
        background-color: #18191B;
        /* padding: 20px; */
    }

    /* Side Menu Styling */
    .side-menu {
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }

    .side-menu h5 {
        font-weight: 600;
    }

    .side-menu .nav-link {
        padding: 10px;
        font-size: 16px;
        color: #333;
        border-radius: 4px;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .nav-link {
        color: #ffffff;
        /* Màu mặc định */
        text-decoration: none;
        /* Bỏ gạch chân */
    }

    .nav-link.active {
        font-weight: bold;
        /* In đậm */
        /* color: #007bff; Màu khi đang hoạt động */
    }

    /* Profile Circle Styling */
    .profile-circle {
        background-color: #007bff;
        color: black;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: bold;
    }

    /* edit */
    .popup {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        /* Nền mờ */
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        /* Đảm bảo nó nằm trên cùng */
    }

    .popup-content {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        width: 300px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .close {
        cursor: pointer;
        float: right;
        font-size: 20px;
    }

    .edit-icon {
        cursor: pointer;
        margin-left: 10px;
        color: #007bff;
        /* Màu cho icon */
    }

    .edit-icon:hover {
        color: #0056b3;
        /* Màu khi hover */
    }

    /* edit thông tin bàn  */
    .reservation-details,
    .reservation-edit-form {
        transition: all 0.3s ease-in-out;
    }

    .modal-dialog-centered {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* rating */
    .rating-stars {
        display: flex !important;
        /* Đảm bảo các ngôi sao nằm trên một hàng ngang */
        gap: 1px !important;
        /* Điều chỉnh khoảng cách giữa các ngôi sao */
        align-items: center !important;
        /* Căn giữa các ngôi sao theo chiều dọc nếu cần */
    }

    .rating-stars i {
        font-size: 24px !important;
        /* Điều chỉnh kích thước của ngôi sao */
        color: gold !important;
        /* Màu mặc định của ngôi sao */
        cursor: pointer !important;
        /* Làm cho ngôi sao tương tác được */
        margin: 0 !important;
        /* Xóa khoảng cách mặc định */
        padding: 0 !important;
        /* Xóa phần lề trong nếu có */
    }

    /* giao diện đồng hồ edit  */

    /* Cải thiện trải nghiệm người dùng trên thiết bị di động */
    @media (max-width: 767px) {
        input[type="time"] {
            font-size: 14px;
            /* Giảm kích thước font trên màn hình nhỏ */
            padding: 8px;
            /* Giảm padding */
        }
    }



    /* Profile Info Section */
    .profile-info {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .profile-info p {
        font-size: 16px;
        margin-bottom: 10px;
    }

    /* Reservation Card Styling */
    .reservation-card {
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        padding: 15px;
        align-items: center;
    }

    .reservation-card h5 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .reservation-card p {
        font-size: 14px;
        color: #666;
    }

    .reservation-card .bi-people {
        margin-right: 5px;
        color: #007bff;
    }

    /* Phân trang  */
    .pagination {
        justify-content: center;
        /* Căn giữa các nút phân trang */
        margin-top: 20px;
        /* Khoảng cách phía trên */
    }

    .page-item {
        margin: 0 3px;
        /* Khoảng cách giữa các nút */
    }

    .page-link {
        padding: 3px 3px;
        /* Thu nhỏ kích thước nút */
        font-size: 14px;
        /* Giảm kích thước font chữ */
    }

    .page-item.disabled .page-link {
        pointer-events: none;
        /* Vô hiệu hóa nút khi không thể nhấp */
        color: #6c757d;
        /* Màu cho nút không khả dụng */
    }

    .page-item.active .page-link {
        background-color: #007bff;
        /* Màu nền cho nút đang chọn */
        color: white;
        /* Màu chữ cho nút đang chọn */
    }

    .page-link:hover {
        background-color: #f1f1f1;
        /* Màu nền khi hover */
        border-radius: 5px;
        /* Bo góc cho nút */
    }

    .page-link:focus {
        outline: none;
        /* Bỏ viền focus */
    }

    .no-background {
        background-color: transparent;
        border: none;
        /* Hoặc border: 0; */
        color: #007bff;
        /* Màu chữ mặc định của Bootstrap */
        cursor: pointer;
    }

    .form-control {
        /* color: black; */
    }


    /* Responsive Styles */
    @media (max-width: 768px) {
        .side-menu {
            margin-bottom: 20px;
        }

        .reservation-card {
            flex-direction: column;
            align-items: flex-start;
        }

        .reservation-card img {
            width: 100%;
            height: auto;
            margin-bottom: 10px;
        }
    }
</style>
<style>
    /* Bao bọc cho phần */
    .wrapper {
        width: 80%;
        /* Điều chỉnh chiều rộng tổng thể của form */
        max-width: 1200px;
        /* Đặt chiều rộng tối đa */
        margin: 0 auto;
        /* Căn giữa container */
        padding: 0 20px;
        /* Thêm khoảng cách hai bên trái, phải */
    }

    /* Flexbox cho các phần tử trong form */
    .d-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        /* gap: 20px; Khoảng cách giữa các phần tử */
    }

    /* Các cột */
    .col-md-4,
    .col-md-8 {
        flex: 1;
        /* Cân đối chiều rộng cho hình ảnh và nội dung */
        padding-left: 20px;
        padding-right: 20px;
    }

    /* Đảm bảo hình ảnh nằm gọn trong cột của nó */
    .col-md-4 img {
        width: 100%;
        /* Đảm bảo hình ảnh không bị tràn ra ngoài */
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        border: 1px solid #444;
        /* Đường viền cho hình ảnh */
    }

    /* Thêm khoảng cách cho nút bấm */
    .btn-danger {
        display: inline-block;
        margin-top: 15px;
        padding: 10px 20px;
        background-color: #ff5252;
        border-radius: 5px;
        border: none;
        color: #fff;
        font-weight: 600;
        font-size: 16px;
    }

    /* Điều chỉnh bố cục văn bản */
    .col-md-8 {
        padding-left: 15px;
        color: #f0f0f0;
    }

    /* Kiểu dáng giá */
    .text-danger {
        font-size: 16px;
        font-weight: 600;
        color: #ff5252;
    }

    /* Điều chỉnh container */
    .container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Khoảng cách giữa các section */
    .category-section {
        margin-bottom: 30px;
    }
</style>

{{-- menu --}}
<style>
    /* Card món ăn */
    .dish-item {
        display: flex;
        align-items: stretch;
        background: #2e2c2b;
        border-radius: 10px;
        overflow: hidden;
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        height: 200px;
        /* Tăng chiều cao để cân đối */
    }

    .dish-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    /* Hình ảnh món ăn */
    .dish-image img {
        object-fit: cover;
        width: 100%;
        height: 100%;
        border: none;
        /* Loại bỏ viền */
    }

    /* Thông tin món ăn */
    .dish-info {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 15px;
        background: #473529;
        color: #fff;

    }

    .dish-title {
        font-size: 18px;
        font-weight: bold;
        margin: 0;
        color: #ffc107;
        /* Màu vàng nổi bật */
    }

    .dish-price {
        font-size: 16px;
        font-weight: 600;
        color: #fff;
    }

    /* Wrapper cho nút */
    .order-btn-wrapper {
        display: flex;
        justify-content: flex-end;
        margin-top: 10px;
    }

    /* Nút fancy */
    .fancy-btn {
        display: inline-block;
        padding: 8px 15px;
        font-size: 14px;
        font-weight: bold;
        text-transform: uppercase;
        background: #ff5722;
        color: #fff;
        border-radius: 5px;
        transition: all 0.3s;
    }

    .fancy-btn:hover {
        background: #e64a19;
        color: #fff;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }


    /* Nút Fancy */
    .fancy-btn {
        position: relative;
        display: inline-block;
        padding: 5px 15px;
        color: #fff;
        border-radius: 10px;
        background: linear-gradient(135deg, #ff7e5f, #feb47b);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        overflow: hidden;
        z-index: 0;
    }

    .fancy-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.3);
        transform: skewX(-45deg);
        transition: all 0.5s ease;
        z-index: 1;
    }

    .fancy-btn:hover::before {
        left: 100%;
    }

    .fancy-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
    }

    .fancy-btn:active {
        transform: translateY(1px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    /* Văn bản trong nút */
    .fancy-btn .btn-text {
        position: relative;
        z-index: 2;
    }


    /* Combo Card */
    .combo-card {
        display: flex;
        flex-direction: column;
        height: 100%;
        background-color: #292929;
        border-radius: 0;
        /* Đảm bảo không có góc bo */
    }

    /* Combo Image */
    .combo-image {
        width: 100%;
        height: 300px;
        /* Chiều cao cố định cho ảnh */
        overflow: hidden;
        position: relative;
    }

    .combo-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        /* Đảm bảo ảnh phủ kín mà không bị méo */
        transition: transform 0.3s ease-in-out;
    }

    .combo-image:hover img {
        transform: scale(1.1);
        /* Hiệu ứng zoom nhẹ khi hover */
    }

    /* Badge Giá */
    .combo-image .badge {
        font-weight: bold;
        font-size: 14px;
        border-radius: 0;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.3);
    }

    /* Card Body */
    .card-body {
        padding: 20px;
        background: #292929;
        color: #fff;
        flex-grow: 1;
    }

    /* Title và Description */
    .card-title {
        font-size: 20px;
        font-weight: bold;
        color: #ffc107;
        text-transform: uppercase;
    }

    .card-text {
        color: #ccc;
        font-size: 14px;
        line-height: 1.5;
    }

    /* Nút Đặt Ngay */
    .btn-warning {
        background-color: #ffc107;
        border: none;
        font-weight: bold;
        text-transform: uppercase;
        transition: all 0.3s ease;
    }

    .btn-warning:hover {
        background-color: #e0a800;
        transform: scale(1.05);
    }

    .menu-section {
        padding: 60px 0;
    }

    .menu-title {
        font-size: 16px;
        letter-spacing: 2px;
        margin-bottom: 10px;
    }

    .menu-subtitle {
        font-size: 30px;
        font-weight: 700;
        margin-bottom: 40px;
        color: #FFC300;
    }

    .menu-tabs {
        display: flex;
        gap: 30px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .tab-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        cursor: pointer;
        transition: transform 0.3s, box-shadow 0.3s, background-color 0.3s;
        /* padding: 15px;
        border-radius: 15px;
        border: 2px solid transparent;
        max-width: 150px;
        min-width: 120px; */
    }



    .tab-icon-wrapper {
        width: 100px;
        height: 100px;
        background: #1e1e1e;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 10px;
        transition: all 0.3s ease-in-out;
        border: 3px solid #FFC300;
    }

    .tab-icon-wrapper:hover {
        transform: scale(1.1);
        border-color: #fff;
    }

    .tab-icon {
        object-fit: cover;
        width: 80px;
        height: 80px;
        border-radius: 50%;
    }

    .tab-label {
        font-size: 16px;
        color: #FFF;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 5px;
    }
</style>


