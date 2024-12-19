import './bootstrap';

window.Echo.channel('notificationUsers-channel')
    .listen('NotifyUsers', (e) => {
        if (e.talbeId == tableId) {
            Swal.fire({
                title: 'Thông báo!',
                text: e.message,
                icon: 'warning',
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                timer: 3000,
                timerProgressBar: true,
            }).then(() => {
                window.location.href = '/menu-order?data=' + e.talbeId;
            });
        }
    });
