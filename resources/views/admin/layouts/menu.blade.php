<!-- Sidebar wrapper start -->
<nav class="sidebar-wrapper">
    <!-- Sidebar brand starts -->
    <div class="sidebar-brand">
        <a href="{{ url('admin') }}" class="logo">
            <img src="{{ asset('./client/03_images/logo.png') }}" alt="Admin Dashboards" />
        </a>
    </div>
    <!-- Sidebar brand ends -->

    <!-- Sidebar menu starts -->
    <div class="sidebar-menu">
        <div class="sidebarMenuScroll">
            <ul >
                @can('Xem thống kê')

                <li class="sidebar-dropdown">
                    <a href="#">
                        <i class="bi bi-house"></i>
                        <span class="menu-text">Thống kê</span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li>
                                <a href="{{ route('admin.dashboard.index') }}">Phân tích</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.report.index') }}">Báo Cáo</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.inventoryDashboard.index') }}">Thống Kê Kho</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endcan


                @can('Xem bàn')
                    <li class="sidebar-dropdown">
                        <a href="#">
                            <i class="bi bi-window-split"></i>
                            <span class="menu-text">Quản Lý Bàn</span>
                        </a>
                        <div class="sidebar-submenu">
                            <ul>
                                <li>
                                    <a href="{{ route('admin.table.index') }}">Danh sách bàn</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endcan
                @can('Xem đặt bàn')
                    <li class="sidebar-dropdown">
                        <a href="#">
                            <i class="bi bi-layers-half"></i>
                            <span class="menu-text">Quản Lý Đặt Bàn</span>
                        </a>
                        <div class="sidebar-submenu">
                            <ul>
                                @can('Xem đặt bàn')
                                    <li>
                                        <a href="{{ route('admin.reservation.index') }}">Danh sách đặt bàn</a>
                                    </li>
                                @endcan
                                <li>

                                    {{-- <a href="{{ route('admin.calendar.index') }}">Lịch đặt bàn</a> --}}

                                    {{-- <a href="{{ route('admin.reservationTable.index') }}">Bàn đặt trước</a> --}}
                                </li>
                                {{-- @can('Xem lịch sử đặt bàn')
                                    <li>
                                        <a href="{{ route('admin.reservationHistory.index') }}">Lịch sử đặt bàn</a>
                                    </li>
                                @endcan --}}
                                @can('Xem quản lý hoàn tiền')
                                    <li>
                                        <a href="{{ route('admin.refunds.index') }}">Quản lý hoàn tiền</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan

                {{-- Quản lý danh mục --}}
                @can('Xem danh mục')

                    <li class="sidebar-dropdown">
                        <a href="#">
                            <i class="bi bi-folder"></i>
                            <span class="menu-text">Quản lý Thực Đơn</span>
                        </a>
                        <div class="sidebar-submenu">
                            <ul>
                                <li>
                                    <a href="{{ route('admin.category.index') }}">Danh mục thực đơn</a>
                                </li>
                                @can('Xem món ăn')
                                    <li>
                                        <a href="{{ route('admin.dishes.index') }}">Danh sách món</a>
                                    </li>
                                @endcan

                                @can('Xem combo')
                                    <li>
                                        <a href="{{ route('admin.combo.index') }}">Danh sách combo</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan



                {{-- Quản lý hóa đơn --}}
                @can('Xem order')
                    <li class="sidebar-dropdown">
                        <a href="#">
                            <i class="bi bi-clipboard-data"></i>
                            <span class="menu-text">Quản lý Hoá đơn</span>
                        </a>
                        <div class="sidebar-submenu">
                            <ul>
                                <li>
                                    <a href="{{ route('admin.order.index') }}">Danh sách Hoá Đơn</a>
                                </li>

                                {{-- @can('Xem mã giảm giá')
                                    <li>
                                        <a href="{{ route('admin.coupon.index') }}">Phiếu giảm giá</a>
                                    </li>
                                @endcan --}}
                                {{-- @can('Xem thanh toán')
                                    <li>
                                        <a href="{{ route('admin.payment.index') }}">Phương thức thanh toán</a>
                                    </li>
                                @endcan --}}
                            </ul>
                        </div>
                    </li>
                @endcan



                <!-- Menu Quản lý Người Dùng -->
                @can('Xem người dùng')
                    <li class="sidebar-dropdown">
                        <a href="#">
                            <i class="bi bi-person-lines-fill"></i>
                            <span class="menu-text">Quản lý Người Dùng</span>
                        </a>
                        <div class="sidebar-submenu">
                            <ul>
                                <li>
                                    <a href="{{ route('admin.user.index') }}">Danh sách Người Dùng</a>
                                </li>
                                @can('Xem feedback')
                                    <li>
                                        <a href="{{ route('admin.feedback.index') }}">Danh sách Phản Hồi</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan

                <!-- Menu Quản lý Nhân Viên -->
                @can('Xem người dùng')
                    <li class="sidebar-dropdown">
                        <a href="#">
                            <i class="bi bi-people-fill"></i>
                            <span class="menu-text">Quản lý Nhân Viên</span>
                        </a>
                        <div class="sidebar-submenu">
                            <ul>
                                <li>
                                    <a href="{{ route('admin.user.employees') }}">Danh sách Nhân Viên</a>
                                </li>
                                @can('Xem vai trò')
                                    <li>
                                        <a href="{{ route('admin.role.index') }}">Danh sách Vai Trò</a>
                                    </li>
                                @endcan
                                @can('Xem quyền hạn')
                                    <li>
                                        <a href="{{ route('admin.permissions.index') }}">Danh sách Quyền Hạn</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan




                @can('Xem nguyên liệu')
                    <li class="sidebar-dropdown">
                        <a href="#">
                            <i class="bi bi-pci-card"></i>
                            <span class="menu-text">Quản lý kho</span>
                        </a>
                        <div class="sidebar-submenu">
                            <ul>
                                @can('Xem nhà cung cấp')
                                    <li>
                                        <a href="{{ route('admin.supplier.index') }}">Nhà cung cấp</a>
                                    <li>
                                    @endcan

                                    @can('Xem nguyên liệu')
                                    <li>
                                        <a href="{{ route('admin.ingredient.index') }}"> Nguyên Liệu</a>
                                    </li>
                                @endcan
                                @can('Xem nhập kho')
                                    <li>
                                        <a href="{{ route('transactions.index') }}">
                                            <span class="menu-text">Phiếu nhập kho</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('Xem tồn kho')
                                    <li>
                                        <a href="{{ route('admin.inventory.index') }}">Hàng tồn kho</a>
                                    </li>
                                @endcan

                                {{-- <li>
                                    <a href="#">Xuất kho</a>
                                </li> --}}
                            </ul>
                        </div>
                    </li>
                @endcan
            </ul>
        </div>
    </div>
    <!-- Sidebar menu ends -->
</nav>
<!-- Sidebar wrapper end -->
