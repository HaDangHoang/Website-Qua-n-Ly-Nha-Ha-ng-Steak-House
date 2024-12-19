@extends('admin.master')

@section('title', 'Cập Nhật Nguyên Liệu')

@section('content')
    @include('admin.layouts.messages')
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-10">
                <!-- Container chứa hai trang -->
                <div class="flip-container">
                    <!-- Trang 1: Form Cập Nhật Nguyên Liệu -->
                    <div class="flip-page front-page" style="margin-top: 35%">
                        <div class="card border-0 shadow-lg rounded-4 mb-5 overflow-hidden">
                            <div class="card-header bg-primary-gradient text-white py-4">
                                <h5 class="card-title mb-0 d-flex align-items-center">
                                    <i class="bi bi-pencil-square me-2"></i> Cập Nhật Nguyên Liệu cho Món:
                                    <strong class="ms-2 text-warning">{{ $dish->name }}</strong>
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <form id="updateIngredientsForm" method="POST"
                                    action="{{ route('admin.dishes.updateIngredients', $dish->id) }}">
                                    @csrf
                                    <!-- Form cập nhật nguyên liệu -->
                                    <div class="row g-3">
                                        @foreach ($dish->recipes as $recipe)
                                            <!-- Nguyên liệu -->
                                            <div class="col-12 bg-light p-3 rounded-3 shadow-sm d-flex align-items-center">
                                                <div class="col-md-5">
                                                    <label class="form-label text-secondary fw-bold">
                                                        <i class="bi bi-box2 me-1"></i> Nguyên liệu
                                                    </label>
                                                    <select class="form-select border-0 shadow-sm" name="ingredients[]"
                                                        required>
                                                        @foreach ($ingredients as $ingredient)
                                                            <option value="{{ $ingredient->id }}"
                                                                {{ $recipe->ingredient->id == $ingredient->id ? 'selected' : '' }}>
                                                                {{ $ingredient->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="recipe_ids[]" value="{{ $recipe->id }}">
                                                </div>
                                                <!-- Số lượng -->
                                                <div class="col-md-4">
                                                    <label class="form-label text-secondary fw-bold">
                                                        <i class="bi bi-arrow-up-right-circle me-1"></i> Số lượng
                                                    </label>
                                                    <input type="number" class="form-control border-0 shadow-sm"
                                                        name="quantities[]" value="{{ $recipe->quantity_need }}"
                                                        min="0" step="0.01" placeholder="Nhập số lượng" required>
                                                </div>
                                                <!-- Đơn vị -->
                                                <div class="col-md-3">
                                                    <label class="form-label text-secondary fw-bold">
                                                        <i class="bi bi-rulers me-1"></i> Đơn vị
                                                    </label>
                                                    <input type="text" class="form-control border-0 shadow-sm bg-white"
                                                        value="Kg" readonly>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="text-end mt-4">
                                        <button type="submit" class="btn btn-success btn-lg rounded-pill shadow-lg px-4">
                                            <i class="bi bi-check-circle me-2"></i> Lưu Thay Đổi
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-end">
                                <button class="btn btn-link text-primary fw-bold flip-button">Chuyển sang Xóa Nguyên
                                    Liệu</button>
                            </div>
                        </div>
                    </div>

                    <!-- Trang 2: Form Xóa Nguyên Liệu -->
                    <div class="flip-page back-page" style="margin-top: 35%">
                        <div class="card border-0 shadow rounded-lg mb-5">
                            <div class="card-header bg-gradient-danger text-white d-flex align-items-center py-3">
                                <h5 class="card-title mb-0">Xoá Nguyên Liệu cho Món: <strong>{{ $dish->name }}</strong>
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <ul class="list-group list-group-flush" id="ingredientListDelete">
                                    @foreach ($dish->recipes as $recipe)
                                        <li
                                            class="list-group-item d-flex flex-wrap align-items-center justify-content-between border-0 bg-light rounded-lg mb-3">
                                            <div class="col-md-5">
                                                <input type="text"
                                                    class="form-control-plaintext form-control-lg text-secondary fw-bold"
                                                    value="{{ $recipe->ingredient->name ?? 'N/A' }}" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="number"
                                                    class="form-control-plaintext form-control-lg text-secondary fw-bold"
                                                    value="{{ $recipe->quantity_need }}" readonly>
                                            </div>
                                            <div class="col-md-2 d-flex justify-content-end">
                                                <span
                                                    class="badge  text-secondary px-3 py-2 shadow-sm">{{ $recipe->unit ?? '' }}</span>
                                                <form action="{{ route('admin.dishes.deleteIngredient', $recipe->id) }}"
                                                    method="POST" class="ms-3">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-outline-danger btn-sm rounded-circle shadow"
                                                        onclick="return confirm('Bạn có chắc chắn muốn xóa nguyên liệu này?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="card-footer text-end">
                                <button class="btn btn-link text-danger fw-bold flip-button">Quay lại Cập Nhật Nguyên
                                    Liệu</button>
                            </div>
                        </div>
                    </div>
                </div>
                <style>
                    .flip-container {
                        perspective: 1000px;
                        width: 100%;
                        height: auto;
                    }

                    .flip-page {
                        width: 100%;
                        backface-visibility: hidden;
                        transition: transform 0.6s ease-in-out;
                    }

                    .front-page {
                        position: absolute;
                        transform: rotateY(0deg);
                    }

                    .back-page {
                        position: absolute;
                        transform: rotateY(180deg);
                    }

                    .flip-container.flipped .front-page {
                        transform: rotateY(-180deg);
                    }

                    .flip-container.flipped .back-page {
                        transform: rotateY(0deg);
                    }
                </style>
                <script>
                    document.querySelectorAll('.flip-button').forEach(button => {
                        button.addEventListener('click', () => {
                            document.querySelector('.flip-container').classList.toggle('flipped');
                        });
                    });
                </script>

                <!-- Card thêm nguyên liệu mới -->
                <div class="card border-0 shadow rounded-lg mb-5">
                    <div class="card-header bg-gradient-success text-white d-flex align-items-center py-3">
                        <h5 class="card-title mb-0">Thêm Nguyên Liệu Mới cho Món: <strong>{{ $dish->name }}</strong></h5>
                    </div>
                    <div class="card-body p-4">
                        <form id="addIngredientForm" method="POST"
                            action="{{ route('admin.dishes.addIngredient', $dish->id) }}">
                            @csrf
                            <div id="newIngredientsContainer">
                                <div class="row g-3 align-items-center mb-4 ingredient-row">
                                    <div class="col-md-5">
                                        <select class="form-select form-select-lg border-0 bg-white shadow-sm"
                                            name="new_ingredient[]" required>
                                            @foreach ($ingredients as $ingredient)
                                                <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="number"
                                            class="form-control form-control-lg border-0 bg-white shadow-sm"
                                            name="new_quantity[]" min="0" placeholder="Định lượng" required>
                                    </div>
                                    {{-- <div class="col-md-3">
                                    <select class="form-select form-select-lg border-0 bg-white shadow-sm" name="new_unit[]" required>
                                        <option value="" disabled selected>Đơn vị</option>
                                        <option value="kg">Kg</option>
                                        <option value="g">G</option>
                                        <option value="l">L</option>
                                        <option value="ml">Ml</option>
                                        <option value="cái">Cái</option>
                                        <option value="muỗng">Muỗng</option>
                                    </select>
                                </div> --}}
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-outline-danger btn-sm rounded-circle"
                                            onclick="removeIngredient(this)">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end mb-4">
                                <button type="button" class="btn btn-secondary" id="addMoreIngredients">
                                    <i class="bi bi-plus me-1"></i> Thêm Nguyên Liệu Khác
                                </button>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-success btn-lg rounded-pill shadow">
                                    <i class="bi bi-plus me-1"></i> Thêm Nguyên Liệu
                                </button>
                            </div>
                        </form>
                    </div>
                </div>


            </div>
        </div>
    </div>


    <script>
        @php
            $ingredientOptions = '';
            foreach ($ingredients as $ingredient) {
                $ingredientOptions .= "<option value='{$ingredient->id}'>{$ingredient->name}</option>";
            }
        @endphp

        document.getElementById('addMoreIngredients').addEventListener('click', function() {
            let container = document.getElementById('newIngredientsContainer');
            let newRow = document.createElement('div');
            newRow.className = 'row g-3 align-items-center mb-4 ingredient-row';
            newRow.innerHTML = `
            <div class="col-md-5">
                <select class="form-select form-select-lg border-0 bg-white shadow-sm" name="new_ingredient[]" required>
                    {!! $ingredientOptions !!}
                </select>
            </div>
            <div class="col-md-4">
                <input type="number" class="form-control form-control-lg border-0 bg-white shadow-sm" name="new_quantity[]" min="0" placeholder="Số lượng" required>
            </div>
            <div class="col-md-3">
                <select class="form-select form-select-lg border-0 bg-white shadow-sm" name="new_unit[]" required>
                    <option value="" disabled selected>Đơn vị</option>
                    <option value="kg">Kg</option>
                    <option value="g">G</option>
                    <option value="l">L</option>
                    <option value="ml">Ml</option>
                    <option value="cái">Cái</option>
                    <option value="muỗng">Muỗng</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-outline-danger btn-sm rounded-circle" onclick="removeIngredient(this)">
                    <i class="bi bi-x"></i>
                </button>
            </div>
        `;
            container.appendChild(newRow);
        });

        function removeIngredient(button) {
            // Xóa hàng nguyên liệu khi nhấn nút xóa
            let row = button.closest('.ingredient-row');
            row.parentNode.removeChild(row);
        }
    </script>
@endsection
