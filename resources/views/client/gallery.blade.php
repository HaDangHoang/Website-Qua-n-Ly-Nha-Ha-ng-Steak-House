@extends('client.layouts.master')
@section('title', 'Gallery')

@section('content')
    @include('client.layouts.component.subheader', [
        'backgroundImage' => 'client/03_images/background/bg-5.jpg',
        'subtitle' => 'Discover',
        'title' => 'Gallery',
        'currentPage' => 'Gallery',
    ])
    <div id="content" class="no-bottom no-top">
        <section aria-label="section">
            <div class="container">
                <div id="gallery" class="row g-4 zoom-gallery">

                    <div class="col-lg-4 item">
                        <figure class="hover-zoom position-relative overflow-hidden">
                            <a href="client/images/gallery/1.jpg">
                                <span class="d-hover">
                                    <span class="d-text">
                                        <span class="d-cap">View</span>
                                    </span>
                                </span>
                                <img src="client/03_images/gallery/1.jpg" alt="">
                            </a>
                        </figure>
                    </div>

                    <div class="col-lg-4 item">
                        <figure class="hover-zoom position-relative overflow-hidden">
                            <a href="client/images/gallery/2.jpg">
                                <span class="d-hover">
                                    <span class="d-text">
                                        <span class="d-cap">View</span>
                                    </span>
                                </span>
                                <img src="client/03_images/gallery/2.jpg" alt="">
                            </a>
                        </figure>
                    </div>

                    <div class="col-lg-4 item">
                        <figure class="hover-zoom position-relative overflow-hidden">
                            <a href="client/images/gallery/3.jpg">
                                <span class="d-hover">
                                    <span class="d-text">
                                        <span class="d-cap">View</span>
                                    </span>
                                </span>
                                <img src="client/03_images/gallery/3.jpg" alt="">
                            </a>
                        </figure>
                    </div>

                    <div class="col-lg-4 item">
                        <figure class="hover-zoom position-relative overflow-hidden">
                            <a href="client/images/gallery/4.jpg">
                                <span class="d-hover">
                                    <span class="d-text">
                                        <span class="d-cap">View</span>
                                    </span>
                                </span>
                                <img src="client/03_images/gallery/4.jpg" alt="">
                            </a>
                        </figure>
                    </div>

                    <div class="col-lg-4 item">
                        <figure class="hover-zoom position-relative overflow-hidden">
                            <a href="client/images/gallery/5.jpg">
                                <span class="d-hover">
                                    <span class="d-text">
                                        <span class="d-cap">View</span>
                                    </span>
                                </span>
                                <img src="client/03_images/gallery/5.jpg" alt="">
                            </a>
                        </figure>
                    </div>

                    <div class="col-lg-4 item">
                        <figure class="hover-zoom position-relative overflow-hidden">
                            <a href="client/images/gallery/6.jpg">
                                <span class="d-hover">
                                    <span class="d-text">
                                        <span class="d-cap">View</span>
                                    </span>
                                </span>
                                <img src="client/03_images/gallery/6.jpg" alt="">
                            </a>
                        </figure>
                    </div>

                </div>

            </div>

        </section>
    </div>

@endsection
