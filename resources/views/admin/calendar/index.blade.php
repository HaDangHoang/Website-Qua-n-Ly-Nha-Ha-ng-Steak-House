@extends('admin.master')

@section('title', 'Lịch Đặt Bàn')

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-xxl-12">
                <div class="card">
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Popup -->
    <div id="bookingForm"
        style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: white; padding: 25px; border-radius: 12px; border: 1px solid #ddd; box-shadow: 0px 10px 20px rgba(0,0,0,0.3); z-index: 1000; width: 600px; max-width: 100%; animation: fadeIn 0.3s;">
        <button type="button" onclick="document.getElementById('bookingForm').style.display='none';"
            style="position: absolute; top: 10px; right: 10px; background: none; border: none; font-size: 24px; color: #888; cursor: pointer;">&times;</button>
        <h3 style="margin-bottom: 20px; font-size: 20px; font-weight: bold; text-align: center; color: #007bff;">Thêm Mới
            Lịch Đặt Bàn</h3>
        <form id="bookingFormSubmit">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <!-- Tên khách hàng -->
                <div style="grid-column: span 2;">
                    <label for="customerName" style="font-size: 14px; font-weight: bold; color: #555;">Tên Khách
                        Hàng:</label>
                    <input type="text" id="customerName" placeholder="Nhập tên khách hàng"
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;"
                        required>
                </div>

                <!-- Số điện thoại -->
                <div style="grid-column: span 2;">
                    <label for="userPhone" style="font-size: 14px; font-weight: bold; color: #555;">Số Điện Thoại:</label>
                    <input type="number" id="userPhone" placeholder="Nhập số điện thoại"
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;"
                        required>
                </div>

                <!-- Ngày và giờ -->
                <div>
                    <label for="bookingDate" style="font-size: 14px; font-weight: bold; color: #555;">Ngày & Giờ
                        Đến:</label>
                    <input type="datetime-local" id="bookingDate"
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;"
                        required>
                </div>

                <!-- Số lượng khách -->
                <div>
                    <label for="guestCount" style="font-size: 14px; font-weight: bold; color: #555;">Số Lượng Khách:</label>
                    <input type="number" id="guestCount" value="1" min="1"
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;"
                        required>
                </div>

                <!-- Ghi chú -->
                <div style="grid-column: span 2;">
                    <label for="notes" style="font-size: 14px; font-weight: bold; color: #555;">Ghi Chú:</label>
                    <textarea id="notes" placeholder="Thêm ghi chú..." rows="3"
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; resize: none;"></textarea>
                </div>

                <!-- Nút hành động -->
                <div style="grid-column: span 2; display: flex; justify-content: space-between; margin-top: 20px;">
                    <button type="button" onclick="document.getElementById('bookingForm').style.display='none';"
                        style="padding: 10px 20px; font-size: 14px; background-color: #6c757d; color: white; border: none; border-radius: 6px; cursor: pointer;">Hủy</button>
                    <button type="submit"
                        style="padding: 10px 20px; font-size: 14px; background-color: #28a745; color: white; border: none; border-radius: 6px; cursor: pointer;">Lưu</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Thêm Font Awesome để sử dụng icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Popup Chi Tiết -->
    <div id="eventDetailPopup" class="popup-form"
        style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: white; padding: 25px; border-radius: 12px; border: 1px solid #ddd; box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.2); z-index: 1000; width: 600px; max-width: 100%; animation: fadeIn 0.3s;">

        <!-- Nút đóng -->
        <button type="button" onclick="document.getElementById('eventDetailPopup').style.display='none';" class="close-btn"
            style="position: absolute; top: 10px; right: 10px; background: none; border: none; font-size: 28px; color: #007bff; cursor: pointer; transition: all 0.3s;">
            &times;
        </button>

        <!-- Tiêu đề -->
        <h3 id="popupCustomerName"
            style="margin-bottom: 20px; font-size: 24px; font-weight: 600; text-align: center; color: #007bff; text-transform: uppercase;">
            Chi Tiết Đặt Bàn
        </h3>

        <!-- Nội dung chi tiết với icon -->
        <div style="font-size: 16px; color: #555; line-height: 1.6;">
            <p><i class="fas fa-phone-alt" style="color: #007bff; margin-right: 8px;"></i><strong>Số điện thoại:</strong>
                <span id="popupPhone">Chưa có</span>
            </p>
            <p><i class="fas fa-ticket-alt" style="color: #007bff; margin-right: 8px;"></i><strong>Mã đặt bàn:</strong>
                <span id="popupReservationCode">Chưa có</span>
            </p>
            <p><i class="fas fa-clock" style="color: #007bff; margin-right: 8px;"></i><strong>Thời gian:</strong> <span
                    id="popupTime">Chưa có</span></p>
            <p><i class="fas fa-users" style="color: #007bff; margin-right: 8px;"></i><strong>Số lượng khách:</strong> <span
                    id="popupGuestCount">Chưa có</span></p>
            <p><i class="fas fa-comment-alt" style="color: #007bff; margin-right: 8px;"></i><strong>Ghi chú:</strong> <span
                    id="popupNotes">Không có ghi chú</span></p>
        </div>

        <!-- Nút chức năng -->
        <div style="margin-top: 25px; display: flex; justify-content: space-between; gap: 20px;">
            <!-- Nút Hủy đặt với Icon -->
            <button class="btn cancel-btn"
                style="background-color: #e74c3c; padding: 12px 20px; border-radius: 30px; border: none; color: white; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                <i class="fas fa-times-circle"></i> Hủy đặt
            </button>

            <!-- Nút Sửa với Icon -->
            <button class="btn edit-btn"
                style="background-color: #f39c12; padding: 12px 20px; border-radius: 30px; border: none; color: white; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                <i class="fas fa-edit"></i> Sửa
            </button>

            <!-- Nút Nhận bàn với Icon -->
            <button class="btn accept-btn"
                style="background-color: #2ecc71; padding: 12px 20px; border-radius: 30px; border: none; color: white; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                <i class="fas fa-check-circle"></i> Nhận bàn
            </button>
        </div>
    </div>

    <!-- Form Popup Sửa -->
    <div id="editBookingForm"
        style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: white; padding: 25px; border-radius: 12px; border: 1px solid #ddd; box-shadow: 0px 10px 20px rgba(0,0,0,0.3); z-index: 1000; width: 600px; max-width: 100%; animation: fadeIn 0.3s;">
        <button type="button" onclick="document.getElementById('editBookingForm').style.display='none';"
            style="position: absolute; top: 10px; right: 10px; background: none; border: none; font-size: 24px; color: #888; cursor: pointer;">
            &times;
        </button>
        <h3 style="margin-bottom: 20px; font-size: 20px; font-weight: bold; text-align: center; color: #ffc107;">Sửa Thông
            Tin Đặt Bàn</h3>
        <form id="editBookingFormSubmit">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <!-- Tên khách hàng -->
                <div style="grid-column: span 2;">
                    <label for="editCustomerName" style="font-size: 14px; font-weight: bold; color: #555;">Tên Khách
                        Hàng:</label>
                    <input type="text" id="editCustomerName" placeholder="Nhập tên khách hàng"
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;"
                        required>
                </div>

                <!-- Số điện thoại -->
                <div style="grid-column: span 2;">
                    <label for="editUserPhone" style="font-size: 14px; font-weight: bold; color: #555;">Số Điện
                        Thoại:</label>
                    <input type="tel" id="editUserPhone" placeholder="Nhập số điện thoại"
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;"
                        required>
                </div>

                <div>
                    <label for="editBookingDate" style="font-size: 14px; font-weight: bold; color: #555;">Ngày & Giờ
                        Đến:</label>
                    <input type="datetime-local" id="editBookingDate"
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; background-color: #f9f9f9; color: #888;"
                        readonly>
                </div>


                <!-- Số lượng khách -->
                <div>
                    <label for="editGuestCount" style="font-size: 14px; font-weight: bold; color: #555;">Số Lượng
                        Khách:</label>
                    <input type="number" id="editGuestCount" value="1" min="1"
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;"
                        required>
                </div>

                <!-- Ghi chú -->
                <div style="grid-column: span 2;">
                    <label for="editNotes" style="font-size: 14px; font-weight: bold; color: #555;">Ghi Chú:</label>
                    <textarea id="editNotes" placeholder="Thêm ghi chú..." rows="3"
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; resize: none;"></textarea>
                </div>

                <!-- Nút hành động -->
                <div style="grid-column: span 2; display: flex; justify-content: space-between; margin-top: 20px;">
                    <button type="button" onclick="document.getElementById('editBookingForm').style.display='none';"
                        style="padding: 10px 20px; font-size: 14px; background-color: #6c757d; color: white; border: none; border-radius: 6px; cursor: pointer;">Hủy</button>
                    <button type="submit"
                        style="padding: 10px 20px; font-size: 14px; background-color: #ffc107; color: white; border: none; border-radius: 6px; cursor: pointer;">Cập
                        Nhật</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Thêm FullCalendar -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = '{{ csrf_token() }}';

            // Hàm gửi yêu cầu với CSRF
            function fetchWithCsrf(url, options = {}) {
                options.headers = {
                    ...options.headers,
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                };
                return fetch(url, options);
            }

            // Hiển thị lỗi
            function showError(message) {
                Swal.fire({
                    title: 'Lỗi',
                    text: message || 'Đã xảy ra lỗi, vui lòng thử lại!',
                    icon: 'error',
                    confirmButtonText: 'OK',
                });
            }

            // Hàm kiểm tra trạng thái (Cancelled nếu đã quá giờ)
            function determineEventStatus(startTime) {
                const now = new Date();
                return new Date(startTime) < now ? 'Cancelled' : 'Pending';
            }

            // Hàm xác định màu sắc dựa trên trạng thái
            function determineEventColor(status, guestCount) {
                if (status === 'Cancelled') return '#6c757d'; // Màu xám cho Cancelled
                return guestCount > 5 ? '#dc3545' : '#28a745'; // Màu đỏ hoặc xanh
            }

            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                height: 700,
                initialView: 'timeGridDay',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'timeGridDay,listDay',
                },
                views: {
                    timeGridDay: {
                        buttonText: 'Day'
                    },
                    listDay: {
                        buttonText: 'List'
                    },
                },
                locale: 'vi',
                navLinks: true,
                editable: true,
                selectable: true,

                // Load sự kiện từ cơ sở dữ liệu
                events: function(fetchInfo, successCallback, failureCallback) {
                    fetchWithCsrf('/api/reservations', {
                            method: 'GET'
                        })
                        .then((response) => {
                            if (!response.ok) {
                                throw new Error(
                                    `Lỗi server: ${response.status} ${response.statusText}`);
                            }
                            return response.json();
                        })
                        .then((data) => {
                            const events = data
                                .map((event) => {
                                    const eventStart =
                                        `${event.reservation_date}T${event.reservation_time}`;
                                    const startDate = new Date(eventStart);

                                    if (isNaN(startDate.getTime())) return null;

                                    const status = determineEventStatus(eventStart);
                                    const color = determineEventColor(status, event
                                        .guest_count);

                                    return {
                                        id: event.id,
                                        title: `${event.user_name} (${event.guest_count} người)`,
                                        start: startDate.toISOString(),
                                        end: new Date(startDate.getTime() + 30 * 60 * 1000)
                                            .toISOString(),
                                        color: color,
                                        extendedProps: {
                                            userPhone: event.user_phone,
                                            notes: event.note,
                                            guest_count: event.guest_count,
                                            status: status,
                                        },
                                    };
                                })
                                .filter(Boolean);
                            successCallback(events);
                        })
                        .catch((error) => {
                            console.error('Error fetching events:', error);
                            failureCallback(error);
                            showError('Không thể tải dữ liệu lịch đặt bàn. Vui lòng thử lại!');
                        });
                },

                // Xử lý khi kéo thả sự kiện
                eventDrop: function(info) {
                    const startDateTime = new Date(info.event.start);
                    const formattedDate = startDateTime.toISOString().split('T')[0];
                    const formattedTime = startDateTime.toTimeString().split(' ')[0];

                    const status = determineEventStatus(info.event.start);
                    const color = determineEventColor(status, info.event.extendedProps.guest_count);

                    const updatedEvent = {
                        id: info.event.id,
                        reservation_date: formattedDate,
                        reservation_time: formattedTime,
                        status: status,
                    };

                    fetchWithCsrf(`/api/reservations/${updatedEvent.id}`, {
                            method: 'PUT',
                            body: JSON.stringify(updatedEvent),
                        })
                        .then((response) => {
                            if (!response.ok) throw new Error(`Lỗi cập nhật: ${response.status}`);
                            return response.json();
                        })
                        .then(() => {
                            info.event.setExtendedProp('status', status);
                            info.event.setProp('backgroundColor', color);
                            Swal.fire({
                                title: 'Thành công!',
                                text: 'Cập nhật trạng thái sự kiện thành công!',
                                icon: 'success',
                                confirmButtonText: 'OK',
                            });
                        })
                        .catch((error) => {
                            console.error('Error updating event:', error);
                            showError('Không thể cập nhật sự kiện. Vui lòng thử lại!');
                            info.revert();
                        });
                },

                // Hiển thị popup chi tiết khi click vào sự kiện
                eventClick: function(info) {
                    const event = info.event;

                    // Hiển thị form chi tiết
                    document.getElementById('eventDetailPopup').style.display = 'block';

                    // Gán dữ liệu vào popup chi tiết
                    document.getElementById('popupCustomerName').innerText = event.title;
                    document.getElementById('popupPhone').innerText = event.extendedProps.userPhone ||
                        'Chưa có';
                    document.getElementById('popupReservationCode').innerText = event.id || 'Chưa có';
                    document.getElementById('popupTime').innerText = event.start.toLocaleString() ||
                        'Chưa có';
                    document.getElementById('popupGuestCount').innerText = event.extendedProps
                        .guest_count || 'Chưa có';
                    document.getElementById('popupNotes').innerText = event.extendedProps.notes ||
                        'Không có ghi chú';


                },

                // Xử lý thêm sự kiện mới
                dateClick: function(info) {
                    const date = new Date(info.dateStr);
                    const formattedDateTime = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(
                2,
                '0'
            )}-${String(date.getDate()).padStart(2, '0')}T${String(date.getHours()).padStart(2, '0')}:${String(
                date.getMinutes()
            ).padStart(2, '0')}`;
                    document.getElementById('bookingDate').value = formattedDateTime;
                    document.getElementById('bookingForm').style.display = 'block';
                },
            });

            calendar.render();

            // Xử lý gửi form thêm sự kiện mới
            document.getElementById('bookingFormSubmit').addEventListener('submit', function(e) {
                e.preventDefault();

                const eventData = {
                    customer_name: document.getElementById('customerName').value.trim(),
                    user_phone: document.getElementById('userPhone').value.trim(),
                    reservation_date: document.getElementById('bookingDate').value.split('T')[0],
                    reservation_time: document.getElementById('bookingDate').value.split('T')[1],
                    guest_count: parseInt(document.getElementById('guestCount').value, 10),
                    note: document.getElementById('notes').value.trim() || null,
                };

                if (!eventData.customer_name || !eventData.user_phone || isNaN(eventData.guest_count)) {
                    showError('Vui lòng nhập đầy đủ thông tin hợp lệ!');
                    return;
                }

                fetchWithCsrf('/api/reservations', {
                        method: 'POST',
                        body: JSON.stringify(eventData),
                    })

                    .then((response) => {
                        if (!response.ok) throw new Error('Thêm sự kiện thất bại!');
                        return response.json();
                    })
                    .then((data) => {
                        const status = determineEventStatus(
                            `${eventData.reservation_date}T${eventData.reservation_time}`);
                        const color = determineEventColor(status, eventData.guest_count);

                        calendar.addEvent({
                            id: data.id,
                            title: `${eventData.customer_name} (${eventData.guest_count} người)`,
                            start: `${eventData.reservation_date}T${eventData.reservation_time}`,
                            end: new Date(
                                new Date(
                                    `${eventData.reservation_date}T${eventData.reservation_time}`
                                ).getTime() +
                                30 * 60 * 1000
                            ).toISOString(),
                            color: color,
                            extendedProps: {
                                userPhone: eventData.user_phone,
                                notes: eventData.note,
                                status: status,
                            },
                        });

                        Swal.fire('Thành công!', 'Đã thêm lịch đặt bàn mới.', 'success').then(() => {
                            document.getElementById('bookingForm').style.display = 'none';
                            document.getElementById('bookingForm').reset();
                        });
                    })
                    .catch((error) => {
                        console.error('Error adding event:', error);
                        showError(error.message || 'Không thể thêm sự kiện!');
                    });
            });

        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hàm hiển thị lỗi
            function showError(message) {
                Swal.fire({
                    title: 'Lỗi',
                    text: message || 'Đã xảy ra lỗi, vui lòng thử lại!',
                    icon: 'error',
                    confirmButtonText: 'OK',
                });
            }

            // Hàm hiển thị thông báo thành công
            function showSuccess(message) {
                Swal.fire({
                    title: 'Thành công!',
                    text: message || 'Cập nhật đặt bàn thành công!',
                    icon: 'success',
                    confirmButtonText: 'OK',
                });
            }

            // Hàm gửi yêu cầu với CSRF
            function fetchWithCsrf(url, options = {}) {
                const csrfToken = '{{ csrf_token() }}'; // CSRF token từ server
                options.headers = {
                    ...options.headers,
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                };
                return fetch(url, options);
            }

            // Xử lý mở form sửa
            document.querySelector('.edit-btn').addEventListener('click', function() {
                const eventId = document.getElementById('popupReservationCode').innerText.trim();
                const eventTitle = document.getElementById('popupCustomerName').innerText.trim();
                const eventUserPhone = document.getElementById('popupPhone').innerText.trim();
                const eventStart = document.getElementById('popupTime').innerText.trim();
                const eventGuestCount = document.getElementById('popupGuestCount').innerText.trim();
                const eventNotes = document.getElementById('popupNotes').innerText.trim();

                document.getElementById('eventDetailPopup').style.display = 'none';
                document.getElementById('editBookingForm').style.display = 'block';
                document.getElementById('editCustomerName').value = eventTitle.split(' (')[0];
                document.getElementById('editUserPhone').value = eventUserPhone;

                try {
                    const formattedTime = new Date(eventStart).toISOString().slice(0, 16);
                    document.getElementById('editBookingDate').value = formattedTime;
                } catch (error) {
                    console.error('Lỗi định dạng thời gian:', error);
                    showError('Định dạng thời gian không hợp lệ.');
                    return;
                }

                document.getElementById('editGuestCount').value = eventGuestCount || 1;
                document.getElementById('editNotes').value = eventNotes || '';
            });

            // Xử lý cập nhật sự kiện
            document.getElementById('editBookingFormSubmit').addEventListener('submit', function(e) {
                e.preventDefault();

                const reservationDateTime = document.getElementById('editBookingDate').value.split('T');
                let reservationTime = reservationDateTime[1]?.trim();
                if (reservationTime && reservationTime.split(':').length === 2) {
                    reservationTime += ':00';
                }

                const updatedEvent = {
                    id: document.getElementById('popupReservationCode').innerText.trim(),
                    customer_name: document.getElementById('editCustomerName').value.trim(),
                    user_phone: document.getElementById('editUserPhone').value.trim(),
                    reservation_date: reservationDateTime[0],
                    reservation_time: reservationTime,
                    guest_count: parseInt(document.getElementById('editGuestCount').value, 10),
                    notes: document.getElementById('editNotes').value.trim() || null,
                };

                if (!updatedEvent.customer_name || !updatedEvent.user_phone || isNaN(updatedEvent
                        .guest_count)) {
                    showError('Vui lòng nhập đầy đủ thông tin hợp lệ!');
                    return;
                }

                fetchWithCsrf(`/api/reservations/calendar/${updatedEvent.id}`, {
                        method: 'PUT',
                        body: JSON.stringify(updatedEvent),
                    })
                    .then((response) => {
                        if (!response.ok) {
                            return response.text().then((errorText) => {
                                try {
                                    const error = JSON.parse(errorText);
                                    console.error('Chi tiết lỗi server:', error);
                                    throw new Error(error.message || 'Cập nhật thất bại!');
                                } catch (err) {
                                    throw new Error(
                                        'Lỗi không mong muốn xảy ra. Kiểm tra lại server!');
                                }
                            });
                        }
                        return response.json();
                    })
                    .then((data) => {
                        showSuccess('Cập nhật đặt bàn thành công!'); // Thông báo thành công
                        document.getElementById('editBookingForm').style.display = 'none';

                        // Kiểm tra nếu calendar tồn tại và có phương thức getEventById
                        if (typeof calendar !== 'undefined' && calendar.getEventById) {
                            const event = calendar.getEventById(updatedEvent.id);
                            if (event) {
                                event.setProp(
                                    'title',
                                    `${updatedEvent.customer_name} (${updatedEvent.guest_count} người)`
                                );
                                event.setStart(
                                    `${updatedEvent.reservation_date}T${updatedEvent.reservation_time}`
                                );
                                event.setExtendedProp('userPhone', updatedEvent.user_phone);
                                event.setExtendedProp('notes', updatedEvent.notes);
                                event.setExtendedProp('guest_count', updatedEvent.guest_count);
                            }
                        } else {
                            console.warn(
                                'Calendar không hỗ trợ hoặc không tìm thấy phương thức getEventById.'
                            );
                        }
                    })
                    .catch((error) => {
                        console.error('Error updating reservation:', error);
                        showError(error.message || 'Không thể cập nhật thông tin. Vui lòng thử lại!');
                    });
            });
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function showError(message) {
                Swal.fire({
                    title: 'Lỗi',
                    text: message || 'Đã xảy ra lỗi, vui lòng thử lại!',
                    icon: 'error',
                    confirmButtonText: 'OK',
                });
            }

            function showSuccess(message) {
                Swal.fire({
                    title: 'Thành công!',
                    text: message || 'Đặt bàn đã được hủy thành công!',
                    icon: 'success',
                    confirmButtonText: 'OK',
                });
            }

            function fetchWithCsrf(url, options = {}) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (!csrfToken) {
                    showError('Không tìm thấy CSRF token.');
                    console.error('CSRF token không tìm thấy');
                    throw new Error('CSRF token is missing');
                }

                options.headers = {
                    ...options.headers,
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                };

                return fetch(url, options).catch((err) => {
                    console.error('Lỗi mạng:', err);
                    showError('Không thể kết nối tới server. Vui lòng thử lại sau.');
                    throw err;
                });
            }

            const cancelButton = document.querySelector('.cancel-btn');
            if (!cancelButton) {
                console.error('Không tìm thấy nút Hủy Đặt!');
                return;
            }

            cancelButton.addEventListener('click', function() {
                const reservationId = document.getElementById('popupReservationCode')?.innerText.trim();
                const reservationStatus = document.getElementById('popupStatus')?.innerText.trim() ||
                    'Pending';

                if (!reservationId) {
                    showError('Không tìm thấy mã đặt bàn.');
                    return;
                }

                if (reservationStatus.toLowerCase() === 'canceled') {
                    showError('Đặt bàn đã bị hủy trước đó, không thể hủy lại.');
                    return;
                }

                if (reservationStatus.toLowerCase() !== 'pending') {
                    showError('Chỉ có thể hủy đặt bàn ở trạng thái "Pending".');
                    return;
                }

                Swal.fire({
                    title: 'Lý do hủy đặt',
                    input: 'textarea',
                    inputPlaceholder: 'Nhập lý do hủy đặt...',
                    inputAttributes: {
                        'aria-label': 'Nhập lý do hủy đặt',
                    },
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Hủy đặt',
                    cancelButtonText: 'Đóng',
                    preConfirm: (reason) => {
                        if (!reason) {
                            Swal.showValidationMessage('Lý do hủy không được để trống.');
                        }
                        return reason;
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        const cancelReason = result.value;

                        fetchWithCsrf(`/api/reservations/cancel/${reservationId}`, {
                                method: 'POST',
                                body: JSON.stringify({
                                    note: cancelReason, // Gửi lý do hủy
                                }),
                            })
                            .then((response) => {
                                if (!response.ok) {
                                    return response.json().then((error) => {
                                        const errorMessage = error?.message ||
                                            'Không thể hủy đặt bàn.';
                                        throw new Error(errorMessage);
                                    });
                                }
                                return response.json();
                            })
                            .then((data) => {
                                showSuccess(data.message || 'Đặt bàn đã được hủy thành công!');

                                // Ẩn popup
                                const popup = document.getElementById('eventDetailPopup');
                                if (popup) popup.style.display = 'none';

                                // Cập nhật trạng thái và đổi màu trực tiếp
                                const statusElement = document.getElementById('popupStatus');
                                if (statusElement) {
                                    statusElement.innerText = 'Canceled'; // Cập nhật trạng thái
                                    statusElement.style.backgroundColor = 'gray'; // Đổi màu nền
                                    statusElement.style.color = 'white'; // Đổi màu chữ
                                    statusElement.style.padding = '5px'; // Thêm padding
                                    statusElement.style.borderRadius = '4px'; // Bo góc
                                }

                                // Đổi màu sự kiện trong FullCalendar
                                if (typeof calendar !== 'undefined' && calendar.getEventById) {
                                    const event = calendar.getEventById(reservationId);
                                    if (event) {
                                        event.setProp('backgroundColor',
                                            'gray'); // Đổi màu sự kiện
                                        event.setExtendedProp('status',
                                            'canceled'); // Cập nhật trạng thái
                                    }
                                } else {
                                    console.warn(
                                        'Đối tượng calendar không tồn tại hoặc không hỗ trợ getEventById.'
                                    );
                                }
                            })
                            .catch((error) => {
                                console.error('Chi tiết lỗi hủy đặt bàn:', error);
                                showError(error.message ||
                                    'Không thể hủy đặt bàn. Vui lòng thử lại!');
                            });
                    }
                });
            });
        });
    </script>




@endsection

<style>
    /* Card Styling */
    .card {
        margin: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        background-color: #ffffff;
    }

    /* Calendar Header */
    .fc-toolbar {
        background-color: #007bff;
        color: white;
        padding: 10px;
        border-radius: 4px;
    }

    .fc-toolbar .fc-button {
        background-color: #0056b3;
        color: white;
        border: none;
        border-radius: 4px;
        margin: 0 5px;
    }

    .fc-toolbar .fc-button:hover {
        background-color: #004094;
    }

    /* Event Styling */
    .fc-event {

        border: none;
        border-radius: 4px;
        padding: 4px;
        font-size: 14px;
        font-weight: bold;
    }

    /* Popup Form */
    #bookingForm {
        animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translate(-50%, -40%);
        }

        to {
            opacity: 1;
            transform: translate(-50%, -50%);
        }
    }

    button {
        cursor: pointer;
    }

    #bookingForm input:focus,
    #bookingForm textarea:focus {
        border-color: #007bff;
        outline: none;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }

    .popup-form {
        background-color: #ffffff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1);
        z-index: 1000;
    }

    /* Hiệu ứng nút khi hover */
    .btn {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn:hover {
        transform: translateY(-4px);
        box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1);
    }

    .btn:active {
        transform: translateY(2px);
    }

    .btn i {
        font-size: 18px;
        transition: transform 0.3s ease;
    }

    .btn:hover i {
        transform: translateX(5px);
    }

    /* Nút đóng */
    .close-btn:hover {
        color: #f44336;
        font-size: 32px;
    }

    /* Hiệu ứng fadeIn */
    @keyframes fadeIn {
        0% {
            opacity: 0;
            transform: translate(-50%, -40%);
        }

        100% {
            opacity: 1;
            transform: translate(-50%, -50%);
        }
    }
</style>
