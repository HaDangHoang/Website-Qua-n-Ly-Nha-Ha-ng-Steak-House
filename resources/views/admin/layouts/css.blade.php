
<style>
    html,
    body {
        font-family: Arial, sans-serif;
        font-size: 15px;
    }


    /* paginate */
    .my-pagination {
        display: flex;
        justify-content: center;
        list-style: none;
        padding: 0;
        margin: 20px 0;
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
        background-color: #ffffff;
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
    .heart-btn {
        width: 70px;
        height: 40px;
        background-color: #4267cd;
        border-radius: 6px;
        border: 2px solid #4267cd;
        display: flex;
        justify-content: center;
        align-items: center;
        box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
    }

    .heart-btn i {
        font-size: 25px;
        color: #ffffff;
        transition: color 0.3s ease;
    }

    /* Hover Effect */
    .heart-btn:hover {
        background-color: #62cd42;
        border-color: #62cd42;
        transform: translateY(-5px);
        box-shadow: 0px 10px 15px rgba(0, 0, 0, 0.15);
    }

    .heart-btn:hover i {
        color: #fff;
    }

    .heart-btn.clicked {
        background-color: #9370DB;
        border-color: #9370DB;
    }

    .heart-btn.clicked i {
        color: #4267cd;
    }

    .bi-trash0::before {
        content: "\f5de";
        padding-top: 16px;
        padding-left: 2px;
    }

    /* Scrollbar styling */
    nav .sidebar-wrapper {
        max-height: 600px;
        /* Chiều cao tối đa, có thể điều chỉnh theo ý muốn */
        overflow-y: auto;
        /* Kích hoạt thanh cuộn dọc */
    }

    nav.sidebar-wrapper::-webkit-scrollbar {
        width: 8px;
    }

    nav.sidebar-wrapper::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.2);
        border-radius: 4px;
    }

    .required {
        font-weight: bold;
        font-size: 1.2em;
        color: #dc3545;
        /* Màu đỏ */
    }

    #feedbackDetailsModal .modal-content {
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    #feedbackDetailsModal .modal-header {
        border-bottom: none;
    }

    #feedbackDetailsModal .modal-body {
        font-size: 16px;
        line-height: 1.6;
    }

    #feedbackDetailsModal .modal-footer {
        border-top: none;
    }

    #feedbackDetailsModal h6 {
        font-size: 14px;
        color: #6c757d;
        font-weight: 500;
    }

    #feedbackDetailsModal p {
        margin-bottom: 0;
    }

    #feedbackRating i {
        font-size: 20px;
        margin-right: 3px;
    }
</style>
