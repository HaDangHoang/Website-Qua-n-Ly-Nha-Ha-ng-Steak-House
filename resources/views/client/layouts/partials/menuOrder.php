<style>
        .menu-item {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            text-align: center;
            position: relative;
            height: 320px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .menu-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-bottom: 1px solid #ddd;
        }

        .item-info {
            padding: 10px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .item-info h6 {
            font-size: 14px;
            margin: 0;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
            color: black;
        }

        /* Giá sản phẩm */
        .item-info .price {
            font-size: 14px;
            color: #666;
            margin: 5px 0;
        }

        /* Nút thêm */
        .btn-add {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 5px 10px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 5px;
        }

        /* Khi sản phẩm hết hàng */
        .menu-item.disabled {
            pointer-events: none;
            opacity: 0.6;
            /* Làm mờ sản phẩm hết hàng */
        }

        /* Responsive */
        @media (max-width: 576px) {
            .col-6 {
                width: 50%;
                /* 2 item mỗi hàng */
            }
        }

        .menu-item {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            text-align: center;
            margin-bottom: 1rem;
        }

        .menu-item img {
            width: 100%;
            height: auto;
        }

        .menu-item .item-info {
            padding: 10px;
        }

        .menu-item .item-info h6 {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
        }

        .menu-item .item-info .price {
            font-size: 14px;
            color: #888;
        }

        .menu-item .btn-add {
            background-color: #f5a623;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .menu-item .btn-add:hover {
            background-color: #e5941f;
        }

        .fixed-bottom-bar {
            cursor: pointer;
            position: fixed;
            border: none;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #ff8c00;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            z-index: 1000;
        }

        .fixed-bottom-bar button {
            background-color: #fff;
            color: #ff8c00;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }

        .fixed-bottom-bar button:hover {
            background-color: #ffae42;
            color: #fff;
        }

        .top-buttons {
            display: flex;
            justify-content: center;
            margin-bottom: 15px;
        }

        .top-buttons button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            margin: 0 5px;
            font-size: 14px;
            cursor: pointer;
        }

        .top-buttons .btn-combo {
            background-color: #ff8c00;
            color: white;
        }

        .top-buttons .btn-combo:hover {
            background-color: #e57a00;
        }

        .top-buttons .btn-other {
            background-color: #fff;
            color: #ff8c00;
            border: 1px solid #ff8c00;
        }

        .top-buttons .btn-other:hover {
            background-color: #ff8c00;
            color: white;
        }
    </style>