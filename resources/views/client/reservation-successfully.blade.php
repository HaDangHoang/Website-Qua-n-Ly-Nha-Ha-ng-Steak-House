@extends('client.layouts.master')
@section('title', 'Reservation Successfully')

@section('content')
    @include('client.layouts.component.subheader', [
        'backgroundImage' => 'client/03_images/background/bg-2.jpg',
        'subtitle' => 'Đặt bàn',
        'title' => 'Đặt bàn thành công',
        'currentPage' => 'Booking',
    ])
    <div id="content" class="no-bottom no-top">
        <section id="section-book-form bg-light">
            <div class="container border rounded bg-light ">
                <div class="row d-flex justify-content-center align-items-center m-4">
                    <p class="text-center m-4 text-dark bold">
                        Cảm ơn quý khách đã đăng ký đặt bàn. Nhà hàng đã nhận được thông tin đặt bàn của quý
                        khách. Quý khách vui lòng kiểm tra tin nhắn hoặc đăng nhập để theo dõi trạng thái của đơn đặt bàn.
                    </p>
                    <div class="row bg-light">
                        <table class="table table-border">
                            <tr>
                                <td>Tên khách hàng : <b>{{ $reservation['user_name'] }}</b></td>
                                <td>Số người: <b>{{ $reservation['guest_count'] }}</b></td>
                            </tr>
                            <tr>
                                <td>SĐT: <b>{{ $reservation['user_phone'] }}</b></td>
                                <td>Thời gian đặt bàn: <b>{{ $reservation['reservation_time'] }} ngày
                                        {{ date('d-m-Y', strtotime($reservation['reservation_date'])) }}</b>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">Ghi chú thêm: {{ $reservation['note'] ?? null }}</td>
                            </tr>
                            @if (isset($reservation['deposit']) || isset($reservation['deposit_amount']))
                                <tr>
                                    <td colspan="2">Tiền cọc:
                                        {{ number_format($reservation['deposit'] ?? $reservation['deposit_amount']) }} đ
                                    </td>
                                </tr>
                            @endif

                        </table>
                        <p class="text-center m-4 text-warning bold">
                            Lưu ý: Chỉ sau khi nhận được tin nhắn xác nhận của bộ phận chăm sóc khách hàng thì yêu
                            cầu đặt bàn của quý khách mới được thực hiện. Cảm ơn quý khách và chúc quý khách có trải nghiệm
                            tuyệt vời tại Nhà Hàng.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (isset($msg))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Thông báo',
                text: "{{ $msg }}",
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        </script>
    @endif
@endsection
