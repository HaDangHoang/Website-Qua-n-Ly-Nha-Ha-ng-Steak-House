@extends('client.layouts.master')
@section('title', 'Menu')

@section('content')
    @include('client.layouts.component.subheader', [
        'backgroundImage' => 'client/03_images/background/bg-1.jpg',
        'subtitle' => 'Thực Đơn Nhà Hàng',
        'title' => 'Thực Đơn',
        'currentPage' => 'Thực Đơn',
    ])

    <div id="content" class="no-bottom no-top" style="background-color: #090909;">
        <!-- Tab Navigation Section -->
        <section class="menu-section py-5" style="background-color: #1e1e1e;">
            <div class="container text-center">
                <h5 class="menu-title text-uppercase text-warning">Danh Mục</h5>
                <h2 class="menu-subtitle text-light">THỰC ĐƠN THEO NHÀ HÀNG</h2>
                <div class="menu-tabs mt-5 d-flex justify-content-center flex-wrap">
                    <div class="tab-item active" onclick="showCategory('combo')">
                        <div class="tab-icon-wrapper">
                            <img src="https://corporate.pia.jp/news/files/piaarena200626-4.jpg" alt="Combo"
                                class="tab-icon">
                        </div>
                        <span class="tab-label">Combo</span>
                    </div>
                    @foreach ($categories as $category)
                    <div class="tab-item" onclick="showCategory('{{ $category->id }}')" style="background-size: cover;">
                        <div class="tab-icon-wrapper" style="background-size: cover;">
                            @php
                                // Lấy ảnh từ mảng categoryImages
                                $image = isset($categoryImages[$category->id]) ? $categoryImages[$category->id] : null;
                            @endphp

                            @if ($image)
                                <img src="{{ asset('images/' . $image) }}" alt="{{ $category->name }}" class="tab-icon">
                            @else
                                <img src="{{ asset('images/default-placeholder.jpg') }}" alt="{{ $category->name }}" class="tab-icon">
                            @endif
                        </div>
                        <span class="tab-label">{{ $category->name }}</span>
                    </div>
                @endforeach

                </div>
            </div>
        </section>






        <!-- Combo Section (Displayed First) -->
        <div class="wrapper">
            <section id="category-combo" class="category-section py-5" style="background: #2e2c2b;">
                <div class="container">
                    <div class="row">
                        @if ($combos->count() > 0)
                            @foreach ($combos as $combo)
                                <div class="col-md-6 mb-5">
                                    <div class="card combo-card border-0 shadow-lg overflow-hidden">
                                        <!-- Combo Image -->
                                        <div class="combo-image position-relative">
                                            <img src="{{ asset($combo->image ? 'storage/' . $combo->image : 'images/placeholder.jpg') }}"
                                                alt="{{ $combo->name }}" class="img-fluid w-100 h-100">
                                            <span
                                                class="badge bg-warning text-dark position-absolute top-0 start-0 m-3 p-2 shadow-sm fs-6">
                                                {{ number_format($combo->price, 0, ',', '.') }} VND
                                            </span>
                                        </div>

                                        <!-- Combo Info -->
                                        <div class="card-body text-light p-4 bg-gradient">
                                            <h4 class="card-title fw-bold text-uppercase text-warning">{{ $combo->name }}
                                            </h4>
                                            <p class="card-text text-muted mt-3">{!! Str::limit(strip_tags($combo->description), 100) !!}</p>
                                            <a href="{{ route('booking.client') }}" style="justify-content: center"
                                                class="btn-line">Đặt bàn ngay</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-center text-muted">Không có combo nào.</p>
                        @endif
                    </div>
                </div>
            </section>




            <!-- Dishes Section for Categories -->
            @foreach ($categories as $category)
                <section id="category-{{ $category->id }}" class="category-section py-5" style="display: none;">
                    <div class="container">
                        <div class="row">
                            @if ($category->dishes->count() > 0)
                                @foreach ($category->dishes as $dish)
                                    <div class="col-md-6 mb-4">
                                        <div class="dish-item d-flex border rounded overflow-hidden"
                                            style="background: #473529;">
                                            <!-- Dish Image -->
                                            <div class="dish-image" style="width: 30%;">
                                                <img src="{{ asset($dish->image ? 'storage/' . $dish->image : 'images/placeholder.jpg') }}"
                                                    alt="{{ $dish->name }}" class="img-fluid h-100 w-100" />
                                            </div>

                                            <!-- Dish Info -->
                                            <div class="dish-info p-3" style="width: 60%;">
                                                <h4 class="dish-title mb-2" style="color: #fff;">{{ $dish->name }}</h4>
                                                <p class="dish-price text-danger mb-3" style="color: yellow;">
                                                    {{ number_format($dish->price, 0, ',', '.') }} VND</p>
                                                <div class="order-btn-wrapper">
                                                    <a href="{{ route('booking.client') }}" class="fancy-btn">
                                                        <span class="btn-text">Đặt bàn ngay</span>
                                                    </a>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p>Không có món ăn nào.</p>
                            @endif
                        </div>
                    </div>
                </section>
            @endforeach
        </div>
    @endsection

    <script>
        function showCategory(categoryId) {
            // Hide all sections
            document.querySelectorAll('.category-section').forEach(section => {
                section.style.display = 'none';
            });

            // Show the selected category
            document.getElementById('category-' + categoryId).style.display = 'block';

            // Update active tab
            document.querySelectorAll('.nav-link').forEach(tab => {
                tab.classList.remove('active');
            });

            // Highlight the clicked tab
            if (categoryId === 'combo') {
                document.getElementById('combo-tab').classList.add('active');
            } else {
                document.getElementById('category-tab-' + categoryId).classList.add('active');
            }
        }
    </script>

