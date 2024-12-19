/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// import Echo from 'laravel-echo';
import Echo from "laravel-echo";

import Pusher from "pusher-js";
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
});

window.Echo.channel("reservations-channel").listen(
    ".upcoming-reservation",
    (e) => {
        alert(`Đơn đặt bàn ID ${e.reservation.id} sắp đến hạn!`);
    }
);

// Lắng nghe sự kiện sắp hết hạn mã giảm giá
window.Echo.channel("coupon-channel").listen("UpcomingCouponEvent", (event) => {
    console.log(event); // Kiểm tra sự kiện

    // Thông báo khi mã giảm giá sắp hết hạn
    alert("Mã giảm giá " + event.coupon.code + " sẽ hết hạn trong 24 giờ!");
});

// Lắng nghe sự kiện mã giảm giá đã hết hạn
window.Echo.channel("coupon-channel").listen("OverdueCouponEvent", (event) => {
    console.log(event); // Kiểm tra sự kiện

    // Thông báo khi mã giảm giá đã hết hạn
    alert("Mã giảm giá " + event.coupon.code + " đã hết hạn!");
});

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
//     wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });
