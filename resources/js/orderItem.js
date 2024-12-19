import './bootstrap';
import '../css/orderItem.css';

window.Echo.channel('order')
    .listen('PosTableUpdated', (e) => {
        const layoutTable = document.getElementById('order-details');
        const notification = document.getElementById('notification-button');
        const checkoutBtn = document.getElementById('payment-button');
        if (e.checkoutBtn == false) {
            checkoutBtn.disabled = true;
        } else {
            checkoutBtn.disabled = false;
        };
        if (e.notiBtn == false) {
            notification.disabled = true;
        } else {
            notification.disabled = false;
        };
        console.log(selectedTableId + '3');
        if (selectedTableId == e.tableId.id) {
            layoutTable.innerHTML = `
            <div style="display: flex; flex-direction: column; overflow-x: hidden;">
    <div style="display: flex; justify-content: center;">
        <h3>Đơn đặt bàn: ${e.orderItems?.reservation?.id ?? e.orderItems.id}</h3>
    </div>

    <div class="row">
        <div class="col">
            <p><strong>Bàn:</strong> ${e.tableId.table_number}</p>
        </div>
        <div class="col">
            <p><strong>Số người:</strong> ${e.orderItems?.reservation?.guest_count ?? e.orderItems?.guest_count ?? 'Khách lẻ'
                }</p >
        </div >
    </div>

    <div class="row">
        <div class="col">
            <p><strong>Khách Hàng:</strong> ${e.orderItems?.reservation?.user_name ?? e.orderItems?.customer?.name ?? 'Khách lẻ'}</p>
        </div>
        <div class="col">
            <p><strong>Giờ vào:</strong> ${e.tableId.orders['0'].pivot.start_time}</p>
        </div>
    </div>
    <div class="row">
        <div class="col"><button class="btn btn-primary" id="editInformation">Sửa thông tin</button> </div>
    </div >
</div >
            <h4>Danh sách món</h4>
        `;
            e.orderItems.order_items.forEach(item => {
                item.total_price = new Intl.NumberFormat('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                }).format(item.total_price)
                if (item.status == 'chờ xử lý') {
                    layoutTable.innerHTML += `
                    <div class="item-list" data-dish-id="${item.item_id}" data-dish-order="${item.order_id}" data-dish-status="${item.status}" data-dish-type="${item.item_type}">
                        <div class="item-name d-flex" style="justify-content: space-between;">
                            <span class="text-dark" title="${item.status}">${item.item_type == 1 ? item.dish.name : item.combo.name}</span>
                            <div>
                                <span>${item.informed}</span>
                                <i class="fa-regular fa-hourglass-half text-dark" title="${item.status}"></i>
                            </div>
                        </div>
                        <div class="item-action">
                            <div class="item-quantity">
                                <span class="text-dark" >Số Lượng:</span>  
                                <div class="quantity-control">
                                    <button class="quantity-btn minus-item" title="Giảm số lượng món">-</button>
                                    <span class="quantity">${item.quantity}</span>
                                    <button class="quantity-btn plus-item" title="Tăng số lượng món">+</button>
                                </div>
                            </div>
                            <div class="item-price">
                                Giá: ${item.total_price}
                            </div>
                            <div class="item-cancel">
                                <button class="delette-item" title="Hủy món">Hủy</button>
                            </div>
                        </div>
                        
                    </div >

            `;
                } else if (item.status == 'đang xử lý') {
                    layoutTable.innerHTML += `
                    <div class="item-list" data-dish-id="${item.item_id}" data-dish-order="${item.order_id}" data-dish-status="${item.status}" data-dish-informed="${item.informed}" data-dish-processing="${item.processing}" data-dish-quantity="${item.quantity}" data-dish-type="${item.item_type}">
                        <div class="item-name">
                            <div class="item-name d-flex justify-content-around">
                                <span class="text-danger" title="${item.status}">${item.item_type == 1 ? item.dish.name : item.combo.name}</span>
                                <div>
                                    <span class="text-danger">${item.processing}</span>
                                    <i class="fa-solid fa-fire-burner text-danger" title="${item.status}"></i> 
                                </div> 
                            </div>
                        </div>
                        <div class="item-action">
                            <div class="item-quantity">
                                <span class="text-dark">Số Lượng:</span>  
                                <div class="quantity-control">
                                    <button class="quantity-btn minus-item" title="Giảm số lượng món">-</button>
                                    <span class="quantity">${item.quantity}</span>
                                    <button class="quantity-btn plus-item" title="Tăng số lượng món">+</button>
                                </div>
                            </div>
                            <div class="item-price">
                                Giá: ${item.total_price}
                            </div>
                            <div class="item-cancel">
                                <button class="delete-item" title="Hủy món">Hủy</button>
                            </div>
                        </div>
                        
                    </div >
            `;
                } else if (item.status == 'hoàn thành') {
                    layoutTable.innerHTML += `
                    <div class="item-list" data-dish-id="${item.item_id}" data-dish-order="${item.order_id}" data-dish-status="${item.status}" data-dish-type="${item.item_type}">
                        <div class="item-name">
                            <div class="item-name d-flex justify-content-around">
                                <span class="text-success" title="${item.status}">${item.item_type == 1 ? item.dish.name : item.combo.name}</span>  
                                <div>
                                    <span class="text-success">${item.completed}</span>
                                    <i class="fa-solid fa-square-check text-success" title="${item.status}"></i>
                                </div>
                            </div>
                        </div>
                        <div class="item-action">
                            <div class="item-quantity">
                                <span class="text-dark">Số Lượng:</span>  
                                <div class="quantity-control">
                                    <button class="quantity-btn minus-item" title="Giảm số lượng món">-</button>
                                    <span class="quantity">${item.quantity}</span>
                                    <button class="quantity-btn plus-item" title="Tăng số lượng món">+</button>
                                </div>
                            </div>
                            <div class="item-price">
                                Giá: ${item.total_price}
                            </div>
                            <div class="item-cancel">
                                <button class="delete-item" title="Hủy món">Hủy</button>
                            </div>
                        </div>
                        
                    </div >
            `;
                }
            });

            document.getElementById('totalAmount').innerHTML = new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(e.orderItems.total_amount);
        }
    });
window.Echo.channel('orders')
    .listen('PosTableUpdatedWithNoti', (e) => {
        const layoutTable = document.getElementById('order-details');
        const notification = document.getElementById('notification-button');
        if (e.noti !== null) {
            Swal.fire({
                icon: 'info',
                title: 'Thông báo',
                text: e.noti,
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }
        if (e.notiBtn == false) {
            notification.disabled = true;
        } else {
            notification.disabled = false;
        };
        if (selectedTableId == e.tableId.id) {
            layoutTable.innerHTML = `
            <div style="display: flex; flex-direction: column; overflow-x: hidden;">
    <div style="display: flex; justify-content: center;">
        <h3>Đơn đặt bàn: ${e.orderItems?.reservation?.id ?? e.orderItems.id}</h3>
    </div>

    <div class="row">
        <div class="col">
            <p><strong>Bàn:</strong> ${e.tableId.table_number}</p>
        </div>
        <div class="col">
            <p><strong>Số người:</strong> ${e.orderItems?.reservation?.guest_count ?? e.orderItems?.guest_count ?? 'Khách lẻ'
                }</p >
        </div >
    </div>

    <div class="row">
        <div class="col">
            <p><strong>Khách Hàng:</strong> ${e.orderItems?.reservation?.user_name ?? e.orderItems?.customer?.name ?? 'Khách lẻ'}</p>
        </div>
        <div class="col">
            <p><strong>Giờ vào:</strong> ${e.tableId.orders['0'].pivot.start_time}</p>
        </div>
    </div>
    <div class="row">
        <div class="col"><button class="btn btn-primary" id="editInformation">Sửa thông tin</button> </div>
    </div >
</div >
            <h4>Danh sách món</h4>
        `;
            e.orderItems.order_items.forEach(item => {
                item.total_price = new Intl.NumberFormat('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                }).format(item.total_price)
                if (item.status == 'chờ xử lý') {
                    layoutTable.innerHTML += `
                    <div class="item-list" data-dish-id="${item.item_id}" data-dish-order="${item.order_id}" data-dish-status="${item.status}" data-dish-type="${item.item_type}">
                        <div class="item-name d-flex" style="justify-content: space-between;">
                            <span class="text-dark" title="${item.status}">${item.item_type == 1 ? item.dish.name : item.combo.name}</span>
                            <div>
                                <span>${item.informed}</span>
                                <i class="fa-regular fa-hourglass-half text-dark" title="${item.status}"></i>
                            </div>
                        </div>
                        <div class="item-action">
                            <div class="item-quantity">
                                <span class="text-dark" >Số Lượng:</span>  
                                <div class="quantity-control">
                                    <button class="quantity-btn minus-item" title="Giảm số lượng món">-</button>
                                    <span class="quantity">${item.quantity}</span>
                                    <button class="quantity-btn plus-item" title="Tăng số lượng món">+</button>
                                </div>
                            </div>
                            <div class="item-price">
                                Giá: ${item.total_price}
                            </div>
                            <div class="item-cancel">
                                <button class="delette-item" title="Hủy món">Hủy</button>
                            </div>
                        </div>
                        
                    </div >

            `;
                } else if (item.status == 'đang xử lý') {
                    layoutTable.innerHTML += `
                    <div class="item-list" data-dish-id="${item.item_id}" data-dish-order="${item.order_id}" data-dish-status="${item.status}" data-dish-informed="${item.informed}" data-dish-processing="${item.processing}" data-dish-quantity="${item.quantity}" data-dish-type="${item.item_type}">
                        <div class="item-name">
                            <div class="item-name d-flex justify-content-around">
                                <span class="text-danger" title="${item.status}">${item.item_type == 1 ? item.dish.name : item.combo.name}</span>
                                <div>
                                    <span class="text-danger">${item.processing}</span>
                                    <i class="fa-solid fa-fire-burner text-danger" title="${item.status}"></i> 
                                </div> 
                            </div>
                        </div>
                        <div class="item-action">
                            <div class="item-quantity">
                                <span class="text-dark">Số Lượng:</span>  
                                <div class="quantity-control">
                                    <button class="quantity-btn minus-item" title="Giảm số lượng món">-</button>
                                    <span class="quantity">${item.quantity}</span>
                                    <button class="quantity-btn plus-item" title="Tăng số lượng món">+</button>
                                </div>
                            </div>
                            <div class="item-price">
                                Giá: ${item.total_price}
                            </div>
                            <div class="item-cancel">
                                <button class="delete-item" title="Hủy món">Hủy</button>
                            </div>
                        </div>
                        
                    </div >
            `;
                } else if (item.status == 'hoàn thành') {
                    layoutTable.innerHTML += `
                    <div class="item-list" data-dish-id="${item.item_id}" data-dish-order="${item.order_id}" data-dish-status="${item.status}" data-dish-type="${item.item_type}">
                        <div class="item-name">
                            <div class="item-name d-flex justify-content-around">
                                <span class="text-success" title="${item.status}">${item.item_type == 1 ? item.dish.name : item.combo.name}</span>  
                                <div>
                                    <span class="text-success">${item.completed}</span>
                                    <i class="fa-solid fa-square-check text-success" title="${item.status}"></i>
                                </div>
                            </div>
                        </div>
                        <div class="item-action">
                            <div class="item-quantity">
                                <span class="text-dark">Số Lượng:</span>  
                                <div class="quantity-control">
                                    <button class="quantity-btn minus-item" title="Giảm số lượng món">-</button>
                                    <span class="quantity">${item.quantity}</span>
                                    <button class="quantity-btn plus-item" title="Tăng số lượng món">+</button>
                                </div>
                            </div>
                            <div class="item-price">
                                Giá: ${item.total_price}
                            </div>
                            <div class="item-cancel">
                                <button class="delete-item" title="Hủy món">Hủy</button>
                            </div>
                        </div>
                        
                    </div >
            `;
                }
            });

            document.getElementById('totalAmount').innerHTML = new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(e.orderItems.total_amount);
        }
    });
