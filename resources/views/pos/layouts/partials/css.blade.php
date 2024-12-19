<link rel="stylesheet" href="{{ asset('poss/assets/css/backend.min.css') }}') }}">

<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('poss/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}">

<!-- Line Awesome -->
<link rel="stylesheet" href="{{ asset('poss/assets/vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css') }}">

<!-- Remixicon -->
<link rel="stylesheet" href="{{ asset('poss/assets/vendor/remixicon/fonts/remixicon.css') }}">

<link rel="stylesheet" href="{{ asset('poss/assets/css/backend.min.css') }}">

<!-- Dripicons -->
<link rel="stylesheet" href="{{ asset('poss/assets/vendor/@icon/dripicons/dripicons.css') }}">

<!-- FullCalendar -->
<link rel="stylesheet" href="{{ asset('poss/assets/vendor/fullcalendar/core/main.css') }}">
<link rel="stylesheet" href="{{ asset('poss/assets/vendor/fullcalendar/daygrid/main.css') }}">
<link rel="stylesheet" href="{{ asset('poss/assets/vendor/fullcalendar/timegrid/main.css') }}">
<link rel="stylesheet" href="{{ asset('poss/assets/vendor/fullcalendar/list/main.css') }}">

<!-- Mapbox -->
<link rel="stylesheet" href="{{ asset('poss/assets/vendor/mapbox/mapbox-gl.css') }}">



<link rel="stylesheet" href="{{ asset('poss/assets/css/backend.min.css') }}">
<link rel="stylesheet" href="{{ asset('poss/assets/vendor/%40fortawesome/fontawesome-free/css/all.min.css') }}">
<link rel="stylesheet"
    href="{{ asset('poss/assets/vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('poss/assets/vendor/remixicon/fonts/remixicon.css') }}">
<link rel="stylesheet" href="{{ asset('poss/assets/vendor/%40icon/dripicons/dripicons.css') }}">

<link rel='stylesheet' href="{{ asset('poss/assets/vendor/fullcalendar/core/main.css') }}">
<link rel='stylesheet' href="{{ asset('poss/assets/vendor/fullcalendar/daygrid/main.css') }}">
<link rel='stylesheet' href="{{ asset('poss/assets/vendor/fullcalendar/timegrid/main.css') }}">
<link rel='stylesheet' href="{{ asset('poss/assets/vendor/fullcalendar/list/main.css') }}">
<link rel="stylesheet" href="{{ asset('poss/assets/vendor/mapbox/mapbox-gl.css') }}">
<link rel="stylesheet" href="{{ asset('poss/assets/css/backend.min.css') }}">





<style>
    /* Print Dropdown Form Styles */
    .print-dropdown {
        position: absolute;
        right: 10px;
        top: 60px;
        width: 300px;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 15px;
        z-index: 1000;
    }

    /* Notification Dropdown Styles */
    .notification-dropdown {
        position: absolute;
        right: 10px;
        top: 100px;
        width: 300px;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 15px;
        z-index: 1000;
        font-family: 'Roboto', sans-serif;
    }

    .notification-header {
        font-size: 16px;
        font-weight: bold;
        color: #007bff;
        margin-bottom: 10px;
    }

    .notification-body {
        display: flex;
        flex-direction: column;
        align-items: center;
        color: #666;
        text-align: center;
    }

    .notification-body p {
        margin-top: 10px;
        font-size: 14px;
    }

    .notification-icon {
        font-size: 50px;
        color: #cccccc;
        margin-bottom: 10px;
    }

    /* Switch Toggle */
    .switch {
        position: relative;
        display: inline-block;
        width: 40px;
        height: 20px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: 0.4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 14px;
        width: 14px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.4s;
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:checked+.slider:before {
        transform: translateX(20px);
    }

    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    /* Print Form Actions */
    .print-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 15px;
    }

    .print-option {
        margin-bottom: 10px;
    }

    #confirmButton,
    #cancelButton {
        width: 48%;
    }

    /* Dropdown menu styles */
    #dropdownMenu {
        position: absolute;
        right: 10px;
        top: 60px;
        width: 300px;
        height: auto;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        padding: 15px;
        display: none;
    }

    /* User info styling */
    .user-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: bold;
        padding-bottom: 10px;
        border-bottom: 1px solid #ddd;
        margin-bottom: 15px;
    }

    .user-info p {
        margin-left: 10px;
        font-size: 16px;
        font-weight: 500;
    }

    /* Avatar styling */
    .user-info img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }

    /* Menu options styling */
    .menu-options {
        margin-top: 10px;
    }


    .menu-row button {
        width: 48%;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        padding: 10px;
        font-size: 14px;
        background-color: #f8f9fa;
        border: none;
        text-align: left;
        transition: none;
    }

    /* Button styling */
    .btn {
        display: block;
        padding: 12px 15px;
        margin-bottom: 12px;
        cursor: pointer;
        transition: none;
    }

    #dropdownMenu .btn:hover {
        background-color: #f8f9fa;
    }

    .dropdown-content .btn {
        background-color: #004a89;
        color: white;
        margin-bottom: 10px;
    }

    .dropdown-content .btn:hover {
        background-color: #004a89;
    }

    .dropdown-content .btn-primary:hover {
        background-color: #0056b3;
    }
</style>


<style>
    /* Active Tab Styling */
    .nav-link.active {

        font-weight: bold;
        padding: 12px 30px;
        box-shadow: 0 4px 10px #0f2462;
        transition: background-color 0.3s ease, color 0.3s ease;
        z-index: 2;
    }

    /* Inactive Tab Styling */
    .nav-link:not(.active) {
        margin-left: 10px;
        color: #ffffff;
        transition: all 0.3s ease;
        z-index: 0;
    }

    /* Hover effect for tabs */
    .nav-link:not(.active):hover {
        background-color: rgba(255, 255, 255, 0.2);
        color: #fff;
        transition: all 0.3s ease-in-out;
    }

    /* Icon hover effect */
    .nav-link i {
        margin-right: 8px;
        font-size: 18px;
        transition: transform 0.3s ease;
    }

    .nav-link:hover i {
        transform: scale(1.1);
    }

    /* Search Bar Styling */
    .form-control1 {
        background-color: transparent;
        border: none;
        border-bottom: 1px solid #eecece;
        color: #fff;
        width: 250px;
        margin-left: 40px;
        font-size: 16px;
        transition: border-color 0.3s ease;
    }

    .form-control1:focus {
        border-bottom: 1px solid #f1f1f1;
        outline: none;
    }

    .form-control1::placeholder {
        color: rgba(255, 255, 255, 0.6);
        font-size: 14px;
    }

    /* Wrapper styling */
    .wrapper {
        min-height: calc(100vh - 50px);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    /* Container flex layout */
    .container-fluid {
        display: flex;
        justify-content: space-between;
        flex-grow: 1;
        padding: 0 10px;
    }

    /* Table and Order sections */
    .table-section {
        flex: 1;

        flex-direction: column;
        justify-content: flex-start;
        /* padding: 20px; */
    }

    .order-section {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        background-color: #ffffff;
        height: 675px;
        padding: 20px;
        margin-top: 0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }


    #orderTabs {
        overflow-x: auto;
        display: flex;
        flex-wrap: nowrap;
    }

    #orderContent {
        flex-grow: 1;
        overflow-y: auto;
        max-height: calc(100vh - 200px);
    }

    .total {
        padding: 10px;
        text-align: right;
        border-top: 1px solid #ddd;
    }

    .empty-order {
        text-align: center;
        margin-top: 50px;
        color: #777;
    }

    .empty-order p {
        font-size: 16px;

    }


    /* Search and icon button styles */
    .search-container {
        display: flex;
        align-items: center;
        border: 1px solid #ddd;
        border-radius: 50px;
        padding: 5px 15px;
        background-color: #f7f7f7;
        max-width: 300px;
    }

    .icon-buttons {
        display: flex;
        gap: 15px;
    }

    .icon-buttons .btn-icon {
        background: none;
        border: none;
        color: #007bff;
        padding: 5px;
        font-size: 22px;
        border-radius: 50%;
        min-width: 40px;
        min-height: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: background-color 0.2s ease;
    }

    .icon-buttons .btn-icon:hover {
        background-color: #f0f0f0;
    }

    /* Table grid layout */
    .table-container {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        grid-gap: 20px;
        margin-top: 20px;
    }

    .table-box {
        background-color: white;
        width: 135px;
        height: 135px;
        display: flex;
        justify-content: center;
        text-align: center;
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        border: 1px solid #ccc;
        transition: transform 0.2s, box-shadow 0.2s;
        padding: 10px;
    }

    .table-box:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }

    .table-name {
        font-weight: bold;
        font-size: 16px;
    }

    .table-details {
        font-size: 14px;
        margin-top: 5px;
        color: #888;
    }

    .table-details i {
        margin-right: 5px;
    }

    /* Table location button */
    .table-location .btn {
        background-color: #f7f7f7;
        border: 1px solid #ddd;
        border-radius: 50px;
        color: #007bff;
        padding: 10px 20px;
        font-size: 14px;
    }

    .table-location .btn i {
        margin-right: 8px;
    }


    /* --------------------css Ben phía INDEX---------------------- */
    .table-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-start;
    }

    .table-box {
        background-color: white;
        border-radius: 10px;
        padding: 20px;
        margin: 10px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        width: calc(20% - 20px);
        height: 150px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        cursor: pointer;
    }

    .table-name {
        font-size: 18px;
        font-weight: bold;
    }

    .table-details {
        margin: 10px 0;
    }

    .table-status {
        font-size: 14px;
        color: gray;
    }

    .table-price {
        font-size: 16px;
        color: #28a745;
        font-weight: bold;
    }

    .filter-section {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .filter-btn {
        padding: 10px 20px;
        border-radius: 50px;
        border: none;
        font-size: 14px;
        cursor: pointer;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s, color 0.3s, box-shadow 0.3s;
    }

    .filter-btn.active {
        background-color: #007bff;
        color: white;
        box-shadow: 0px 6px 8px rgba(0, 0, 0, 0.15);
    }

    .filter-btn:hover {
        background-color: #0056b3;
        color: white;
        box-shadow: 0px 6px 8px rgba(0, 0, 0, 0.2);
    }

    .order-item {
        margin-bottom: 10px;
    }

    .order-item span:nth-child(2) {
        color: #28a745;
        font-weight: bold;
    }

    .quantity-input {
        margin-left: 10px;
    }

    #notification {
        display: none;
        position: fixed;
        bottom: 10px;
        right: 10px;
        z-index: 9999;
        background-color: #28a745;
        color: white;
        padding: 15px;
        border-radius: 5px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    }

    #notification strong {
        margin-right: 10px;
    }























    /* CSS giữ nguyên như hiện tại */
    .table-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        grid-gap: 10px;
        justify-content: center;

    }

    .table-box {
        border-radius: 20px;
        padding: 25px;
        text-align: center;
        background-color: white;
        box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;

        overflow: hidden;
        cursor: pointer;
        width: 100%;
        max-width: 200px;
    }

    .table-box:hover {
        transform: translateY(-10px);
        box-shadow: 0px 12px 30px rgba(0, 0, 0, 0.2);
    }

    /* Hình ảnh bàn */
    .table-image img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        margin-bottom: 15px;
    }

    /* Tên bàn */
    .table-name {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 15px;
        color: #333;
    }

    /* Chi tiết bàn */
    .table-details {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-bottom: 10px;
        color: #666;
    }

    /* Trạng thái bàn */
    .table-box.available {
        background: linear-gradient(135deg, #a8e063, #56ab2f);
        border: none;
        color: white;
    }

    .table-box.occupied {
        background: linear-gradient(135deg, #e74c3c, #c0392b);
        border: none;
        color: white;
    }

    .table-box.reserved {
        background: linear-gradient(135deg, #f39c12, #e67e22);
        border: none;
        color: white;
    }

    /* Biểu tượng trạng thái */
    .table-box .status-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: rgba(255, 255, 255, 0.8);
        padding: 5px 15px;
        font-size: 14px;
        font-weight: 600;
        color: #555;
        border-radius: 30px;
    }

    .table-box.available .status-badge {
        background-color: rgba(255, 255, 255, 0.9);
        color: #28a745;
    }

    .table-box.occupied .status-badge {
        background-color: rgba(255, 255, 255, 0.9);
        color: #dc3545;
    }

    .table-box.reserved .status-badge {
        background-color: rgba(255, 255, 255, 0.9);
        color: #ffc107;
    }

    /* Hiệu ứng hover cho các trạng thái */
    .table-box.available:hover {
        background: linear-gradient(135deg, #8ccf58, #449d28);
    }

    .table-box.occupied:hover {
        background: linear-gradient(135deg, #d34d3c, #a5281a);
    }

    .table-box.reserved:hover {
        background: linear-gradient(135deg, #e57c0a, #c06b1d);
    }

    /* Chi tiết đơn hàng */
    .order-details {
        background-color: #fff;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
        overflow-y: auto;
        max-height: 800px;
    }

    #order-list {
        list-style: none;
        padding: 0;
    }

    #order-list li {
        display: flex;
        justify-content: space-between;
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }

    #order-list li:last-child {
        border-bottom: none;
    }

    .empty-cart {
        display: none;
        text-align: center;
    }

    .empty-cart.active {
        display: block;
    }

    .payment-actions {
        margin-top: 20px;
    }

    .payment-actions .total-price {
        font-size: 18px;
        font-weight: bold;
    }



    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        /* padding: 10px; */
    }

    .tabs {
        display: flex;
        overflow-x: auto;
        white-space: nowrap;
        align-items: center;
        max-width: 50vw;
        margin-left: 10px;
        /* Khoảng cách bên trái cho tabs */
    }

    .tab {
        display: inline-block;
        padding: 5px 8px;
        /* Giữ nguyên padding để tab nhìn đẹp */
        margin: 0 2px;
        /* Giảm khoảng cách giữa các tab */
        background: white;
        border: 1px solid #ddd;
        border-radius: 5px 5px 0 0;
        cursor: pointer;
        font-size: 14px;
        position: relative;
        min-width: 60px;
    }

    .tab.active {
        background: #4285f4;
        color: white;
        border-bottom: 1px solid transparent;
    }

    .tab .close {
        margin-left: 3px;
        color: red;
        cursor: pointer;
        font-size: 12px;
    }

    .icons {
        display: flex;
        align-items: center;
        margin-left: 10px;
    }

    .icon {
        background: white;
        border-radius: 50%;
        padding: 10px;
        margin: 0 5px;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        transition: background 0.3s;
    }

    .icon:hover {
        background: #e0e0e0;
    }

    .tab-indicator {
        margin-left: 10px;
        color: white;
        font-weight: bold;
    }

    .container {
        margin-top: 20px;
        text-align: center;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    .add-tab-btn {
        background: #28a745;
        border: none;
        color: white;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        font-size: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        margin-right: 10px;
    }

    .add-tab-btn:hover {
        background-color: #218838;
    }

    .footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        background-color: white;
        box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
    }

    .footer-icons {
        display: flex;
        align-items: center;
    }

    .footer-icons .icon {
        margin: 0 5px;
        font-size: 20px;
    }

    .pay-button {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background 0.3s;
    }

    .pay-button:hover {
        background-color: #0056b3;
    }

    .total {
        font-size: 18px;
        font-weight: bold;
    }

    .empty-state {
        display: none;
    }

    .empty-state.active {
        display: block;
    }

    .table-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 10px;
        padding: 10px;
    }

    .table-box {
        flex-basis: calc(19%);
        max-width: calc(19%);
        height: 150px;
        display: flex;
        flex-direction: column;
        align-items: center;

        text-align: center;
        border-radius: 10px;
        box-sizing: border-box;
    }




    .btn-group {
        display: flex;
        gap: 10px;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        font-size: 16px;
        border-radius: 50px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn i {
        margin-right: 5px;
    }

    .btn-secondary {
        background-color: #f0f0f0;
        color: #888888;
    }

    .btn-primary {
        background-color: #007bff;
        color: white;
    }

    .btn-info {
        background-color: #17a2b8;
        color: white;
    }

    .btn-warning {
        background-color: #ffc107;
        color: white;
    }

    .btn-secondary:hover,
    .btn-primary:hover,
    .btn-info:hover,
    .btn-warning:hover {
        opacity: 0.9;
    }

    .modal {
        display: flex;
        justify-content: center;
        align-items: center;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background: white;
        padding: 20px;
        border-radius: 10px;
        width: 400px;
    }

    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .dropdown {
        position: absolute;
        right: 10px;
        top: 60px;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 15px;
        width: 300px;
    }

    .dropdown h4 {
        margin-bottom: 10px;
    }

    #notificationList {
        list-style: none;
        padding: 0;
    }

    #notificationList li {
        padding: 5px 0;
        border-bottom: 1px solid #ddd;
    }


    .table-card {
        position: relative;
        width: 150px;
        height: 150px;
        margin-left: 10px;
        background-color: #ffffff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        padding: 20px;
        box-sizing: border-box;
        border-radius: 10px;
        margin-bottom: 10px;
        transition: border-color 0.3s ease;
    }

    .table-number {
        font-size: 1.0rem;
        font-weight: bold;
        color: #333;
        position: absolute;
        top: 10px;
        left: 10px;
    }

    .border-decoration {
        position: absolute;
        background-color: #ced4da;
    }

    .border-decoration.left {
        width: 3px;
        height: 120px;
        left: -5px;
        top: 50%;
        transform: translateY(-50%);
    }

    .border-decoration.right {
        width: 3px;
        height: 120px;
        right: -5px;
        top: 50%;
        transform: translateY(-50%);
    }

    .border-decoration.top {
        height: 3px;
        width: 120px;
        top: -5px;
        left: 50%;
        transform: translateX(-50%);
    }

    .border-decoration.bottom {
        height: 3px;
        width: 120px;
        bottom: -5px;
        left: 50%;
        transform: translateX(-50%);
    }

    .table-card.available .border-decoration.left {
        background-color: rgb(255, 255, 255);
        /* Trống */
    }

    .table-card.reserved .border-decoration.left {
        background-color: rgb(212, 210, 210);
        /* Đã đặt */
    }

    .table-card.occupied .border-decoration.left {
        background-color:rgb(212, 210, 210);
        /* Đang sử dụng */
    }
</style>


<style>
    .order-content-container {
        max-height: 500px;
        overflow-y: auto;
    }

    .btn-success {
        background-color: #28a745;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
    }


    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        width: 500px;
        max-width: 90%;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .action-buttons .btn {
        margin-right: 10px;
    }

    .badge.bg-warning {
        background-color: orange;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
    }

    h4 {
        padding-bottom: 10px
    }

    .reservation-details {
        max-height: 400px;
        /* Chiều cao tối đa */
        overflow-y: auto;
        /* Kích hoạt thanh cuộn dọc */
    }



    .table-card.selected {
        border-color: #8ef4a5;
        /* Đổi màu viền khi bàn được chọn */
        box-shadow: 0 0 15px #3108ff;
        /* Hiệu ứng đổ bóng */
        transform: scale(1.05);
        /* Tăng kích thước nhẹ */
    }
</style>

<style>
    /* Hiệu ứng khi di chuột qua Món ăn */
    .dish-item .menu-item {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
    }

    .dish-item:hover .menu-item {
        transform: scale(1.05);
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
    }
</style>


<style>
    .reservation-details {
        padding: 10px;
        max-height: 400px;
        overflow-y: auto;
        border: 1px solid #ddd;
        background-color: #f9f9f9;
        border-radius: 5px;
    }

    .order-info {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        background-color: #ffffff;
        margin-bottom: 10px;
    }

    .order-info p {
        margin: 5px 0;
    }

    .action-buttons {
        margin-top: 10px;
    }

    .action-buttons button {
        width: 100px;
    }


        .order-btn i {
            font-size: 24px;
            /* Tăng kích thước icon */
        }


</style>


<style>
    /* Styles for the table and menu sections */
    .table-section, .menu-section {
        transition: opacity 0.3s ease-in-out;
    }

    .active {
        font-weight: bold;
        color: #ffffff;
    }

    .border-decoration {
        position: absolute;
        border: 2px solid transparent;
        transition: border-color 0.3s ease;
    }

    .table-card:hover .border-decoration {
        border-color: #007bff;
    }

    .table-card {
        position: relative;
        margin: 10px;
        padding: 20px;
        border: 2px solid #007bff;
        border-radius: 10px;
        text-align: center;
        cursor: pointer;
        transition: transform 0.2s ease;
    }

    .table-card:hover {
        transform: scale(1.05);
    }

    .dish-item {
        margin-bottom: 15px;
    }

    .order-item {
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }

    .btn-delete {
        margin-left: 10px;
    }

    .empty-order {
        text-align: center;
        margin-top: 20px;
        color: #888;
    }

    .total {
        font-weight: bold;
        margin-top: 10px;
    }

    .table-number {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 10px;
    }
</style>


<style>


    .table-card.reserved {


        background-color: #acd8ef;
    }

    .table-card.occupied {


        background-color: #f8c1a5;
    }

    .table-card:hover {

        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

 /* Style cho container chứa các input */
.search-filter {
    display: flex; /* Sử dụng Flexbox */
    flex-wrap: nowrap; /* Không cho phép gói, luôn trên cùng một dòng */
    gap: 20px; /* Khoảng cách giữa các nhóm */
    margin-bottom: 15px; /* Khoảng cách giữa các phần tử */
}

/* Style cho mỗi nhóm input */
.input-group {
    display: flex; /* Sử dụng Flexbox cho input và label */
    flex-direction: row;
    align-items: flex-start; /* Căn trái cho label */
    min-width: 120px; /* Đặt chiều rộng tối thiểu */
    margin-bottom: 15px;
}

/* Điều chỉnh kích thước ô input và select */
.modal-body input[type="text"],
.modal-body select {
    width: 100%;
    padding: 5px;
    border: none;
    border-bottom: 1px solid #ccc;
    border-radius: 0;
    font-size: 14px;
    color: #333;
}

/* Điều chỉnh kích thước ô input cho thời gian */
.modal-body .time-group input[type="text"] {
    width: 150px;
}

/* Hiệu ứng khi focus vào ô input */
.modal-body input[type="text"]:focus,
.modal-body select:focus {
    border-bottom-color: #007bff;
    outline: none;
}

.transparent-button {
    background-color: transparent;
    color: #007bff;
    cursor: pointer;
    font-size: 14px;
    padding: 5px 10px;
    text-decoration: none;
    border: none;
}

.transparent-button:hover {
    color: #0056b3;
    border: none;
}

/* Đảm bảo modal hiển thị ở trung tâm và có hiệu ứng mờ */

#reservationListModal, #orderDetailModal {
    display: none;
}

.modal {
    display: none;
    position: fixed;
    z-index: 1050;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    background-color: rgba(0, 0, 0, 0.5); /* Nền mờ */
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Nội dung modal */
.modal-dialog {
    width: 1000px;
    margin: 0 auto;
}

.modal-content {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    width: 100%;
}

.modal-header {
    border-bottom: none;
}

.modal-title {
    font-size: 18px;
    font-weight: bold;
}

.modal-body {
    padding: 15px 0;
}

/* Định dạng các nút trong modal */
.modal-footer .btn {
    min-width: 100px;
}

.modal-footer {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
}

/* Định dạng nút đóng modal */
.close {
    color: #000;
    font-size: 24px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover, .close:focus {
    color: red;
    text-decoration: none;
    cursor: pointer;
}

/* Tùy chỉnh cho các input, form control */
.form-control {
    margin-bottom: 10px;
    padding: 10px;
    font-size: 14px;
    border-radius: 4px;
}

/* Định dạng layout 2 cột đối xứng */
.modal-body .d-flex {
    display: flex;
    justify-content: space-between;
}

.modal-body .d-flex > div {
    width: 48%; /* Mỗi cột chiếm 48% chiều rộng */
}

/* Nút "Bỏ qua" màu trung tính */
.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
}

/* Các nút khác có thể tùy chỉnh thêm */
.btnGroup{
    display: flex;
    justify-content: flex-end;
}

.btnEdit{
    width: 100px;
    margin-right: 15px
}

    </style>






