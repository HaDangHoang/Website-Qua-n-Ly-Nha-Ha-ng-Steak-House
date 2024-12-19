@extends('client.layouts.master')
@section('title', 'Booking')

@section('content')
    @include('client.layouts.component.subheader', [
        'backgroundImage' => 'client/03_images/background/bg-2.jpg',
        'title' => 'Đặt bàn',
        'currentPage' => 'Đặt bàn',
    ])

    @if (session('err'))
        <script>
            Swal.fire({
                title: "Yêu cầu bị trùng lặp",
                text: "{{ session('err') }}",
                icon: "warning",
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
            });
        </script>
    @endif

    <div id="content" class="no-bottom no-top">
        <section id="section-book-form">
            <div class="container border">
                <div class="row text-center">
                    <div class="col-12 col-md-6 bg-warning p-2 text-uppercase">
                        <h5>Bước 1 - Chọn giờ ăn</h5>
                    </div>
                    <div class="col-12 col-md-6 bg-secondary p-2 text-uppercase">
                        <h5><a href="">Bước 2 - Thông tin khách hàng</a></h5>
                    </div>
                </div>
                <div class="row d-flex justify-content-center align-items-center mt-3">
                    <div class="row text-center justify-content-center">
                        @foreach ($days as $index => $day)
                            <a href="javascript:void(0)" class="col-12 col-md-3 border m-1 p-2 day-selector"
                                data-index="{{ $index }}">
                                <div class="fw-bold {{ $index === 0 ? 'text-warning' : 'text-light' }}">
                                    {{ $index === 0 ? 'Hôm nay' : ($index === 1 ? 'Ngày mai' : 'Ngày kia') }}
                                </div>
                                <div class="fw-bold text-capitalize {{ $index === 0 ? 'text-warning' : 'text-light' }}">
                                    {{ $day->isoFormat('dddd, DD/MM/YYYY') }}
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                @foreach ($days as $index => $day)
                    <div class="day-section time-slots" id="day-{{ $index }}"
                        style="{{ $index === 0 ? '' : 'display: none;' }}">
                        <div class="row mt-2 d-flex justify-content-center align-items-center row-cols-3 row-cols-md-3">
                            @foreach ($timeSlots as $timeSlot)
                                @php
                                    $timeSlotWithDate = Carbon\Carbon::today('Asia/Ho_Chi_Minh')->setTimeFromTimeString($timeSlot);
                                    $isDisabled = isset($disabledSlots[$day->format('Y-m-d')]) && in_array($timeSlot, $disabledSlots[$day->format('Y-m-d')]);
                                @endphp

                                @if ($day->isToday() && $now->greaterThan($timeSlotWithDate))
                                    <div class="text-muted col p-2 rounded border d-flex justify-content-center mb-2">
                                        <p class="py-2 m-0 timeText">{{ $timeSlot }}</p>
                                    </div>
                                    @elseif ($isDisabled)
                                    <div class="text-muted col p-2 rounded border d-flex justify-content-center align-items-center mb-2">
                                        <p class="py-2 m-0 timeText">{{ $timeSlot }} 
                                            <span class="text-danger small">(Đã đầy)</span>
                                        </p>
                                    </div>
                                
                                @else
                                    <div class="col p-2 rounded border d-flex justify-content-center time-slot mb-2"
                                        style="cursor: pointer;" data-time="{{ $timeSlot }}"
                                        data-date="{{ $day->format('Y-m-d') }}">
                                        <p class="text-warning py-2 m-0 timeText">{{ $timeSlot }}</p>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <div class="text-center my-4">
                    <button id="confirm-button" class="btn-line">Xác nhận</button>
                </div>
            </div>
        </section>
    </div>

@endsection

<style>

.timeText{
    font-size:30px;
}

@media (max-width: 426px) {
    html {
        font-size: 15px;
    }

    .timeText{
        font-size:15px;
    }

    .time-slot {
        font-size: 1.8rem; 
        padding: 0.8rem;
    }

    .time-slot {
        text-align: center;
        margin-bottom: 1rem;
    }
    
}

</style>
