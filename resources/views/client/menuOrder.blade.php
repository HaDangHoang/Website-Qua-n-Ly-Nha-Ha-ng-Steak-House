@extends('client.layouts.master')
@section('title', 'Menu')
@section('content')
    @include('client.layouts.partials.menuOrder')
    @include('client.layouts.component.subheader', [
        'backgroundImage' => 'client/03_images/background/bg-1.jpg',
        'subtitle' => 'Steak House',
        'title' => 'Bàn ' . $table->table_number,
        'currentPage' => 'Menu Order',
    ])
    @if (session('error'))
        <script>
            Swal.fire({
                title: "Bạn chưa gọi món",
                text: "{{ session('error') }}",
                icon: "warning",
                timer: 4000,
                timerProgressBar: true,
                showCloseButton: true
            });
        </script>
    @endif
    <div class="container mt-4">
        <div class="mx-2 my-3">
            <div class="row ">
                <div class="col">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Tìm món ăn" aria-label="Username"
                            aria-describedby="basic-addon1">
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                    </div>
                </div>
                <div class="col">
                    <select name="" id="" class="form-select">
                        <option value="all" selected>Tất cả</option>
                        <option value="combo">Combo({{ $combo->count() }})</option>
                        @foreach ($cate as $cate)
                            <option value="{{ $cate->id }}">{{ $cate->name }} ({{ $cate->dishes->count() }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row" id="dish-list">
            @foreach ($dishes as $dish)
                <div class="col-6">
                    <div class="menu-item {{ $dish->is_active == 0 || $dish->status == 'out_of_stock' ? 'disabled' : '' }}"
                        data-category="{{ $dish->category->id }}" style="padding: 10px;"
                        @if ($dish->is_active && $dish->status != 'out_of_stock') data-dish-id="{{ $dish->id }}" 
                                data-dish-price="{{ $dish->price }}" @endif>
                        <img src="storage/{{ $dish->image }}"
                            style="object-fit: cover; height: 200px; width: 100%; max-height: 200px; {{ $dish->is_active == 0 || $dish->status == 'out_of_stock' ? 'filter: grayscale(100%); opacity: 0.6;' : '' }}">
                        <div class="item-info">
                            <h6>{{ $dish->name }}</h6>
                            @if ($dish->is_active == 0 || $dish->status == 'out_of_stock')
                                <p class="price">Hết hàng</p>
                            @else
                                <p class="price">{{ number_format($dish->price) }}đ</p>
                                <button class="btn-add">Thêm món</button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
            @foreach ($combo as $combo)
                <div class="col-6">
                    <div class="menu-item {{ $combo->is_active == 0 ? 'disabled' : '' }}" data-category="combo"
                        style="padding: 10px;"
                        @if ($combo->is_active != 'out_of_stock') data-combo-id="{{ $combo->id }}" 
                            data-combo-price="{{ $combo->price }}" @endif>
                        <img src="storage/{{ $combo->image }}"
                            style="object-fit: cover; height: 200px; width: 100%; max-height: 200px; {{ $combo->is_active == 0 ? 'filter: grayscale(100%); opacity: 0.6;' : '' }}">
                        <div class="item-info">
                            <h6>{{ $combo->name }}</h6>
                            @if ($combo->is_active == 0)
                                <p class="price">Hết hàng</p>
                            @else
                                <p class="price">{{ number_format($combo->price) }}đ</p>
                                <button class="btn-add">Thêm Combo</button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Fixed Bottom Bar -->
    <a href="{{ route('menuSelected', ['table_number' => $table->id]) }}" class="fixed-bottom-bar mx-4 mb-1 rounded">
        <span id="cart-total">Tổng số lượng ({{ $item->sum('quantity') }})</span>
        <span id="cart-quantity">{{ number_format($total, 0, ',', '.') }}đ</span>
    </a>
    @vite(['resources/js/menuOrder.js', 'resources/js/DishStatus.js', 'resources/js/notiRedirect.js', 'resources/js/notiRedirectUsers.js'])

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        let tableId = "{{ $table->id }}";
        document.addEventListener("DOMContentLoaded", () => {
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

            document.body.addEventListener("click", (e) => {
                if (e.target.classList.contains("btn-add")) {
                    const parent = e.target.closest(".menu-item");
                    if (!parent || parent.classList.contains("disabled")) return;
                    const dishId = parent.getAttribute("data-dish-id");
                    const comboId = parent.getAttribute("data-combo-id");

                    let url = "";
                    let bodyData = {
                        table_id: tableId
                    };

                    if (dishId) {
                        url = "/add-dish-waiting";
                        bodyData.dish_id = dishId;
                    } else if (comboId) {
                        url = "/add-combo-waiting";
                        bodyData.combo_id = comboId;
                    }

                    if (url) {
                        fetch(url, {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute("content"),
                                },
                                body: JSON.stringify(bodyData),
                            })
                            .then((response) => response.json())
                            .then((data) => {
                                showNotification(data.message || "Thêm món thành công!");
                            })
                            .catch(() => {
                                showNotification("Đã xảy ra lỗi, vui lòng thử lại.", "warning");
                            });
                    }
                }
            });
            document.body.addEventListener("click", (e) => {
                if (
                    e.target.matches(".menu-item img, .menu-item h6, .menu-item .price")
                ) {
                    const parent = e.target.closest(".menu-item");
                    if (!parent) return;

                    const dishName = parent.querySelector("h6").innerText;
                    const price = parent.querySelector(".price").innerText;
                    const imageSrc = parent.querySelector("img").getAttribute("src");
                    const isDisabled = parent.classList.contains("disabled");

                    Swal.fire({
                        title: dishName,
                        text: isDisabled ? "Món này hiện không có sẵn." : `Giá: ${price}`,
                        imageUrl: imageSrc,
                        imageWidth: 400,
                        imageHeight: 200,
                        showCloseButton: true,
                        confirmButtonText: isDisabled ? "OK" : "Gọi món",
                    }).then((result) => {
                        if (result.isConfirmed && !isDisabled) {
                            parent.querySelector(".btn-add").click();
                        }
                    });
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $(".form-control").on("input", function() {
                let searchValue = $(this).val().toLowerCase();
                let selectedCategory = $(".form-select").val();
                $("#dish-list .menu-item").each(function() {
                    let dishName = $(this).find("h6").text().toLowerCase();
                    let dishPrice = $(this).data("dish-price") || $(this).data("combo-price") || 0;
                    let dishCategory = $(this).data("category");
                    let matchesName = dishName.includes(searchValue);
                    let matchesPrice = dishPrice.toString().includes(searchValue);
                    let matchesCategory = selectedCategory === "all" || dishCategory ===
                        selectedCategory;
                    if ((matchesName || matchesPrice) && matchesCategory) {
                        $(this).closest(".col-6").show();
                    } else {
                        $(this).closest(".col-6").hide();
                    }
                });
            });
            $(".form-select").on("change", function() {
                $(".form-control").trigger("input");
            });
        });

        $(document).ready(function() {
            $("select").on("change", function() {
                let selectedCategory = $(this).val();
                $("#dish-list .menu-item").each(function() {
                    let dishCategory = $(this).attr("data-category");
                    if (selectedCategory === "all") {
                        $(this).closest(".col-6").show();
                    } else {
                        if (dishCategory === selectedCategory) {
                            $(this).closest(".col-6").show();
                        } else {
                            $(this).closest(".col-6").hide();
                        }
                    }
                });
            });
        });
    </script>
@endsection
