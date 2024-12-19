import './bootstrap';

window.Echo.channel('notification-channel')
    .listen('NotifyUserEvent', (e) => {
        Swal.fire({
            title: 'Thông báo!',
            text: e.message,
            icon: 'success',
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            timer: 3000,
            timerProgressBar: true,
        }).then(() => {
            window.location.href = '/';
        });
    });
