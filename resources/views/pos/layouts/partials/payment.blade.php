  <!-- Dropdown Menu -->
  <div id="dropdownMenu" class="dropdown-content"
      style="display: none; background-color: #f8f9fa; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); padding: 15px; width: 250px;">
      <div class="user-info text-center" style="margin-bottom: 15px;">
          <img src="path/to/avatar.png" alt="User Avatar"
              style="width: 50px; height: 50px; border-radius: 50%; border: 2px solid #004a89;">
          <p style="font-weight: bold; color: #004a89; margin: 5px 0;">Thu Ngân</p>
          <span style="cursor: pointer; color: #dc3545;" onclick="hideDropdown()">X</span>
      </div>
      <div class="menu-options">
          <button class="btn btn-custom w-100 mb-2" style="background-color: #004a89; color: #fff;"><i
                  class="fas fa-cogs"></i> Quản lý</button>
          <button class="btn btn-custom w-100 mb-2" style="background-color: #004a89; color: #fff;"><i
                  class="fas fa-chart-bar"></i> Báo cáo cuối ngày</button>
          <button class="btn btn-custom w-100 mb-2" style="background-color: #004a89; color: #fff;"><i
                  class="fas fa-file-invoice"></i> Lập phiếu thu</button>
          <button class="btn btn-custom w-100 mb-2" style="background-color: #004a89; color: #fff;"><i
                  class="fas fa-clipboard-list"></i> Hoá đơn</button>
          <button class="btn" id="modalListReservation" data-toggle="modal" data-target="#reservationListModal"><i
                  class="fas fa-list"></i> Xem danh sách đặt bàn</button>
          <button class="btn btn-custom w-100 mb-2" style="background-color: #004a89; color: #fff;"><i
                  class="fas fa-cog"></i> Cài đặt chung</button>
          <button class="btn btn-custom w-100 mb-2" style="background-color: #004a89; color: #fff;"><i
                  class="fas fa-tag"></i> Thiết lập giá</button>
          <button class="btn btn-custom w-100 mb-2" style="background-color: #004a89; color: #fff;"><i
                  class="fas fa-box"></i> Món có sẵn trong đơn</button>
          <button class="btn btn-custom w-100 mb-2" style="background-color: #004a89; color: #fff;"><i
                  class="fas fa-keyboard"></i> Phím tắt</button>
          <form class="nav-link text-light" id="logout-form" action="{{ route('logout') }}" style="color: #f5cc00;"
              method="POST" style="display: none;">
              @csrf
              <button type="submit" class="btn btn-danger w-100" style="background-color: #004a89; color: #fff;"><i
                      class="fas fa-sign-out-alt">Đăng xuất</button>
          </form>
      </div>
  </div>


  <!-- Print Settings Dropdown Form -->
  <div id="printDropdownForm" class="print-dropdown" style="display: none;">
      <div class="print-option">
          <label for="autoPrint">Tự động in hóa đơn</label>
          <label class="switch">
              <input type="checkbox" id="autoPrint" checked>
              <span class="slider round"></span>
          </label>
      </div>

      <div class="print-option">
          <label for="printTemplate">Chọn mẫu in hóa đơn</label>
          <input type="number" id="printTemplate" value="1" min="1">
      </div>

      <div class="print-actions">
          <button class="btn btn-primary" id="confirmButton">Xong</button>
          <button class="btn btn-secondary" id="cancelButton">Bỏ qua</button>
      </div>
  </div>


  <script>
      document.addEventListener("DOMContentLoaded", function() {
          const notificationButton = document.getElementById('notificationButton');
          const printButton = document.getElementById('printButton');

          // Notification Badge Animation
          notificationButton.addEventListener('click', function() {
              alert("Bạn có 3 thông báo mới!");
          });

          // Print Button
          printButton.addEventListener('click', function() {
              alert("Đang in...");
          });

          // Search Bar
          const searchInput = document.getElementById('searchInput');
          const dishItems = document.querySelectorAll('.dish-item');

          searchInput.addEventListener('input', function() {
              const searchTerm = searchInput.value.toLowerCase();

              dishItems.forEach(function(dish) {
                  const dishName = dish.querySelector('.menu-item p').textContent.toLowerCase();

                  if (dishName.includes(searchTerm)) {
                      dish.style.display = 'block';
                  } else {
                      dish.style.display = 'none';
                  }
              });
          });
      });

      // Modal danh sách đặt bàn
      document.getElementById('modalListReservation').addEventListener('click', function() {
          $('#reservationListModal').modal('show');
      });
  </script>
