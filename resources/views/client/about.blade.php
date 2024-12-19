@extends('client.layouts.master')
@section('title', 'About')

@section('content')
    @include('client.layouts.component.subheader', [
        'backgroundImage' => 'client/03_images/background/bg-3.jpg',
        // 'subtitle' => 'Discover',
        'title' => 'Về chúng tôi',
        'currentPage' => 'Về chúng tôi',
    ])
    <div id="content" class="no-bottom no-top">
        <!-- section begin -->
        <section>
            <div class="container">
                <div class="row gx-5 align-items-center">
                    <div class="col-lg-6">
                        <h2>Steak House</h2>
                        <div class="spacer-half"></div>
                        <p class="lead">Chào mừng bạn đến với nhà hàng Steak House, nơi mang đến cho bạn những trải nghiệm ẩm độc đáo nhất. Tại đây, chúng tôi không chỉ làm món ăn; chúng tôi tạo ra những kỷ niệm âm thực đặc biệt dành riêng cho bạn.</p>

                        <p>Steak House tự hào mang đến những phân cắt thịt bò chất lượng nhất, được chọn lọc và chế biến bởi đội ngũ đầu bếp tài hoa. Từ món bò bít tết đá nóng cho đến bò nướng sốt vang đỏ, chúng tôi cam kết mang đến hương vị tự nhiên và đậm đà lên từng miếng thịt.

                            Ngoài ra, nhà hàng còn phục vụ các món khai vị tươi ngon, salad thanh đạm, và tráng miệng ngọt ngào để hoàn thiện bữa ăn của bạn.</p>

                        <p>Nhà hàng Steak House ra đời với sứ mệnh mang đến một không gian âm thực đổi mới, nơi bạn có thể tận hưởng những món ăn cao cấp nhưng vẫn đậm chất ấm áp thoải mái. Lấy cảm hứng từ tình yêu âm thực và mong muốn mang đến những bữa ăn đáng nhớ, chúng tôi đã xây dựng Beefsteak trở thành một biểu tượng của sự tinh tế trong đánh giá về chất lượng và dịch vụ.</p>
                        <p>Chúng tôi mong muốn không chỉ là một địa điểm ăn uống, mà còn là nơi bạn tạo nên những kỷ niệm đẹp bên bạn bè và gia đình. Hãy để Beefsteak đồng hành cùng những khoảnh khắc đáng nhớ của bạn!</p>
                    </div>

                    <div class="col-lg-6">
                        <div class="row g-4">
                            <div class="col-6">
                                <img src="client/03_images/misc/4.jpg" class="img-fluid mb-4 wow zoomIn" alt="">
                                <div class="de_count wow fadeInUp">
                                    <h3><span class="timer" data-to="750" data-speed="3000"></span>+</h3>
                                    <h4>Positive feedbacks</h4>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="de_count wow fadeInUp">
                                    <h3><span class="timer" data-to="50" data-speed="3000"></span>%</h3>
                                    <h4>Turnover increased</h4>
                                </div>
                                <div class="spacer-10"></div>
                                <img src="client/03_images/misc/7.jpg" class="img-fluid mt-4 wow zoomIn" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- section close -->

        @include('client.layouts.component.chefs')

    </div>
@endsection
