window.Echo.channel('dish-status-channel')
    .listen('.dish-status-updated', (e) => {
        const dishElement = document.querySelector(`.dish-item[data-dish-id="${e.dish.id}"]`);
        if (dishElement) {
            if (e.dish.is_active === 0 || e.dish.status === 'out_of_stock') {
                dishElement.classList.add('disabled');
                dishElement.querySelector('img').style.filter = 'grayscale(100%)';
                dishElement.querySelector('img').style.opacity = '0.6';
                const statusText = dishElement.querySelector('.text-danger');
                if (!statusText) {
                    const status = document.createElement('p');
                    status.className = 'text-danger';
                    status.textContent = 'Không khả dụng';
                    dishElement.querySelector('.card-body').appendChild(status);
                }
            } else {
                dishElement.classList.remove('disabled');
                dishElement.querySelector('img').style.filter = '';
                dishElement.querySelector('img').style.opacity = '';
                const statusText = dishElement.querySelector('.text-danger');
                if (statusText) {
                    statusText.remove();
                }
            }
        }
    });
window.Echo.channel('combo-status-channel')
    .listen('.combo-status-updated', (e) => {
        const comboElement = document.querySelector(`.dish-combo[data-combo-id="${e.combo.id}"]`);
        if (comboElement) {
            if (e.combo.is_active === 0 || e.combo.status === 'out_of_stock') {
                comboElement.classList.add('disabled');
                comboElement.querySelector('img').style.filter = 'grayscale(100%)';
                comboElement.querySelector('img').style.opacity = '0.6';
                const statusText = comboElement.querySelector('.text-danger');
                if (!statusText) {
                    const status = document.createElement('p');
                    status.className = 'text-danger';
                    status.textContent = 'Không khả dụng';
                    comboElement.querySelector('.card-body').appendChild(status);
                }
            } else {
                comboElement.classList.remove('disabled');
                comboElement.querySelector('img').style.filter = '';
                comboElement.querySelector('img').style.opacity = '';
                const statusText = comboElement.querySelector('.text-danger');
                if (statusText) {
                    statusText.remove();
                }
            }
        }
    });
