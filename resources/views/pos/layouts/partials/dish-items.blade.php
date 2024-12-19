@foreach ($dishes as $dish)
<div class="col-md-3 dish-item" data-category="{{ strtolower(str_replace(' ', '-', $dish->category)) }}" data-dish-id="{{ $dish->id }}" data-dish-price="{{ $dish->price }}">
    <div class="card menu-item">
        <img src="{{ asset($dish->image ? 'storage/' . $dish->image : 'images/placeholder.jpg') }}" alt="{{ $dish->name }}" class="img-fluid rounded" style="height: 200px; object-fit: cover;" />
        <div class="card-body text-center">
            <h5 class="card-price">{{ number_format($dish->price, 0, ',', '.') }} VND</h5>
            <p class="card-title">{{ \Str::limit($dish->name, 20, '...') }}</p>
            <button class="btn btn-primary btn-add-dish" data-dish-id="{{ $dish->id }}" data-dish-price="{{ $dish->price }}" data-dish-name="{{ $dish->name }}">Thêm món</button>
        </div>
    </div>
</div>
@endforeach
