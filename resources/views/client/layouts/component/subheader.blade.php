<section id="subheader" class="jarallax text-light">
    <img src="{{ $backgroundImage }}" class="jarallax-img" alt="">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="text-center wow fadeInUp">
                    <h5 class="uptitle">{{ $subtitle ?? null }}</h5>
                    <h2>{{ $title ?? null }}</h2>
                    <ol class="breadcrumb">

                        <li class="breadcrumb-item"><a href="{{ route('client.index') }}">Home</a></li>
                        <li class="breadcrumb-item">{{ $currentPage ?? null }}</li>

                        <li class="breadcrumb-item active"aria-current="page">{{ $blog ?? null }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>
