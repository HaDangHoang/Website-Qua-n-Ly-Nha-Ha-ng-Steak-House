import './bootstrap';

window.Echo.channel('dish-channel')
    .listen('UpdateDishStatus', (e) => {
        const dishElement = document.querySelector(`.menu-item[data-dish-id="${e.dish.id}"]`);
        if (dishElement) {
            if (e.dish.is_active === 0 || e.dish.status === 'out_of_stock') {
                dishElement.classList.add('disabled');
                dishElement.querySelector('img').style.filter = 'grayscale(100%)';
                dishElement.querySelector('img').style.opacity = '0.6';
                dishElement.querySelector('.item-info .price').innerText = 'Hết hàng';
                const button = dishElement.querySelector('.btn-add');
                if (button) button.remove();
            } else {
                dishElement.classList.remove('disabled');
                dishElement.querySelector('img').style.filter = 'none';
                dishElement.querySelector('img').style.opacity = '1';
                dishElement.querySelector('.item-info .price').innerText = `${new Intl.NumberFormat().format(e.dish.price)}đ`;
                if (!dishElement.querySelector('.btn-add')) {
                    const newButton = document.createElement('button');
                    newButton.className = 'btn-add';
                    newButton.innerText = 'Gọi món';
                    dishElement.querySelector('.item-info').appendChild(newButton);
                }
            }
        }
    });
window.Echo.channel('combo-channel')
    .listen('UpdateComboStatus', (e) => {
        const comboElement = document.querySelector(`.menu-item[data-combo-id="${e.combo.id}"]`);
        if (comboElement) {
            if (e.combo.is_active === 0 || e.combo.status === 'out_of_stock') {
                comboElement.classList.add('disabled');
                comboElement.querySelector('img').style.filter = 'grayscale(100%)';
                comboElement.querySelector('img').style.opacity = '0.6';
                comboElement.querySelector('.item-info .price').innerText = 'Hết hàng';
                const button = comboElement.querySelector('.btn-add');
                if (button) button.remove();
            } else {
                comboElement.classList.remove('disabled');
                comboElement.querySelector('img').style.filter = 'none';
                comboElement.querySelector('img').style.opacity = '1';
                comboElement.querySelector('.item-info .price').innerText = `${new Intl.NumberFormat().format(e.dish.price)}đ`;
                if (!comboElement.querySelector('.btn-add')) {
                    const newButton = document.createElement('button');
                    newButton.className = 'btn-add';
                    newButton.innerText = 'Gọi món';
                    comboElement.querySelector('.item-info').appendChild(newButton);
                }
            }
        }
    });
