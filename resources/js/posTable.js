import './bootstrap';

window.Echo.channel('table')
    .listen('MessageSent', (e) => {
        const layoutTable = document.getElementById('layoutTable');
        layoutTable.innerHTML = e.tables.map(item => {
            if (selectedTableId != item.id) {
                return `
            <div class="table-card ${item.status}"
            data-table-id="${item.id}" data-status="${item.status}"
            data-order-id="${item.orders.map(order => order.reservation?.user_name ?? order.customer?.name).join('')}">
            <span class="table-number">Bàn ${item.table_number}</span>
                <div class="table-o" style="width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;">
                    <span>
                        ${item.orders && item.orders.length > 0 ?
                        '<i class="fa-solid fa-id-card"></i> ' + item.orders.map(order => order.reservation?.id ?? order.id).join(", ") :
                        ''}
                    </span>
                </div>
            </div>
            `;
            } else {
                return `
                <div class="table-card ${item.status}" style="background-color: rgb(0, 123, 255);"
     data-table-id="${item.id}" data-status="${item.status}"
     data-order-id="${item.orders.map(order => order.reservation?.user_name ?? order.customer?.name).join('')}">
    <span class="table-number" style="color:white">Bàn ${item.table_number}</span>
    <div class="table-o" style="color:white" style="width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;">
    <span>
         ${item.orders && item.orders.length > 0 ?
                        '<i class="fa-solid fa-id-card"></i> ' + item.orders.map(order => order.reservation?.id ?? order.id).join(", ") :
                        ''}
    </span>

    </div>
</div>

                `;
            }
        }).join('');
    });

window.Echo.channel('tablee')
    .listen('MessageSentt', (e) => {
        const layoutTable = document.getElementById('layoutTable');
        const orderDetails = document.getElementById('order-details');
        const tableSection = document.getElementById('table-section');
        const menuSection = document.getElementById('menu-section');
        const tableViewButton = document.getElementById('table-view-button');
        const menuViewButton = document.getElementById('menu-view-button');
        menuSection.style.display = 'none';
        tableSection.style.display = 'block';
        tableViewButton.classList.add('active');
        menuViewButton.classList.remove('active');

        layoutTable.innerHTML = e.tables.map(item => {
            return `
            <div class="table-card ${item.status}"
            data-table-id="${item.id}" data-status="${item.status}"
            data-order-id="${item.orders.map(order => order.reservation?.user_name ?? order.customer?.name).join('')}">
            <span class="table-number">Bàn ${item.table_number}</span>
            <div class="table-o" style="width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;">
            <span>
                 ${item.orders && item.orders.length > 0 ?
                    '<i class="fa-solid fa-id-card"></i> ' + item.orders.map(order => order.reservation?.id ?? order.id).join(", ") :
                    ''}
            </span>
            </div>
            </div>
            `;
        }).join('');
        orderDetails.innerHTML = `
  <div class="empty-order">
    <p>Chưa chọn bàn</p>
    <p>Vui lòng chọn bàn tại phía bên trái màn hình</p>
  </div>
`;
    });
