@extends('client.layouts.master')
@section('title', 'Blog')

@section('content')
    @include('client.layouts.component.subheader', [
        'backgroundImage' => 'client/03_images/background/bg-6.jpg',
        'subtitle' => 'Lates',
        'title' => 'Blog',
        'currentPage' => 'Blog',
    ])
    <div id="content" class="no-bottom no-top">
        <section id="section-book-form">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 blog-list">

                        <div class="blog-item row align-items-center g-3 gx-5">
                            <div class="col-md-5">
                                <div class="post-content">
                                    <div class="date-box">
                                        <div class="day">28</div>
                                        <div class="month">MAY</div>
                                    </div>

                                    <div class="post-text">
                                        <h3><a href="{{ route('blog-single.client') }}">10 Reasons To Drink Coffee Every
                                                Day</a>
                                        </h3>
                                        <p>Coffee is a brewed drink prepared from roasted coffee beans, which are the seeds
                                            of berries from the Coffea plant. The genus Coffea is native to tropical Africa
                                            (specifically having its origin in Ethiopia and Sudan) and Madagascar, the
                                            Comoros, Mauritius, and Réunion in the Indian Ocean.</p>
                                        <a href="{{ route('blog-single.client') }}" class="btn-line">Read More</a>
                                    </div>


                                </div>
                            </div>

                            <div class="col-md-7">
                                <img src="client/03_images/blog/pic-blog-1.jpg" class="img-fluid" alt="">
                            </div>
                        </div>

                        <div class="blog-item row align-items-center g-3 gx-5">
                            <div class="col-md-7">
                                <img src="client/03_images/blog/pic-blog-2.jpg" class="img-fluid" alt="">
                            </div>

                            <div class="col-md-5">
                                <div class="post-content">
                                    <div class="date-box">
                                        <div class="day">26</div>
                                        <div class="month">MAY</div>
                                    </div>

                                    <div class="post-text">
                                        <h3><a href="client/03_luxury-blog-single.html">10 Reasons To Drink Coffee Every
                                                Day</a>
                                        </h3>
                                        <p>Coffee is a brewed drink prepared from roasted coffee beans, which are the seeds
                                            of berries from the Coffea plant. The genus Coffea is native to tropical Africa
                                            (specifically having its origin in Ethiopia and Sudan) and Madagascar, the
                                            Comoros, Mauritius, and Réunion in the Indian Ocean.</p>
                                        <a href="{{ route('blog-single.client') }}" class="btn-line">Read More</a>
                                    </div>


                                </div>
                            </div>


                        </div>

                        <div class="blog-item row align-items-center g-3 gx-5">
                            <div class="col-md-5">
                                <div class="post-content">
                                    <div class="date-box">
                                        <div class="day">24</div>
                                        <div class="month">MAY</div>
                                    </div>

                                    <div class="post-text">
                                        <h3><a href="{{ route('blog-single.client') }}">10 Reasons To Drink Coffee Every
                                                Day</a>
                                        </h3>
                                        <p>Coffee is a brewed drink prepared from roasted coffee beans, which are the seeds
                                            of berries from the Coffea plant. The genus Coffea is native to tropical Africa
                                            (specifically having its origin in Ethiopia and Sudan) and Madagascar, the
                                            Comoros, Mauritius, and Réunion in the Indian Ocean.</p>
                                        <a href="{{ route('blog-single.client') }}" class="btn-line">Read More</a>
                                    </div>


                                </div>
                            </div>

                            <div class="col-md-7">
                                <img src="client/03_images/blog/pic-blog-3.jpg" class="img-fluid" alt="">
                            </div>
                        </div>

                        <div class="blog-item row align-items-center g-3 gx-5">
                            <div class="col-md-7">
                                <img src="client/03_images/blog/pic-blog-4.jpg" class="img-fluid" alt="">
                            </div>

                            <div class="col-md-5">
                                <div class="post-content">
                                    <div class="date-box">
                                        <div class="day">22</div>
                                        <div class="month">MAY</div>
                                    </div>

                                    <div class="post-text">
                                        <h3><a href="{{ route('blog-single.client') }}">10 Reasons To Drink Coffee Every
                                                Day</a>
                                        </h3>
                                        <p>Coffee is a brewed drink prepared from roasted coffee beans, which are the seeds
                                            of berries from the Coffea plant. The genus Coffea is native to tropical Africa
                                            (specifically having its origin in Ethiopia and Sudan) and Madagascar, the
                                            Comoros, Mauritius, and Réunion in the Indian Ocean.</p>
                                        <a href="{{ route('blog-single.client') }}" class="btn-line">Read More</a>
                                    </div>


                                </div>
                            </div>


                        </div>



                    </div>
                </div>
            </div>
        </section>



    </div>
@endsection
