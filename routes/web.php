<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ComboController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DishesController;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Client\OnlineCheckoutController;
use App\Http\Controllers\KitchenController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\IngredientController;
use App\Http\Controllers\Admin\IngredientTypeController;
use App\Http\Controllers\Admin\InventoryTransactionController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RefundController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\ReservationHistoryController;

use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Client\CustomerAuthController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\MemberController;
use App\Http\Controllers\Client\MenuController;
use App\Http\Controllers\Pos\PosController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\InventoryDBController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//route tạo phiếu nhập kho
Route::resource('transactions', InventoryTransactionController::class);
Route::post('/transactions', [InventoryTransactionController::class, 'storeTransaction'])->name('transactions.store');
Route::get('/transactions/{id}/add-items', [InventoryTransactionController::class, 'addItemForm'])->name('transactions.add_items');
Route::post('/transactions/{id}/add-items', [InventoryTransactionController::class, 'storeItem'])->name('transactions.store_item');
Route::post('/transactions/{id}/update-status', [InventoryTransactionController::class, 'updateStatus'])->name('transactions.update_status');
Route::post('/transactions/{id}/finalize', [InventoryTransactionController::class, 'finalizeTransaction'])->name('transactions.finalize');
Route::get('transactions/{id}', [InventoryTransactionController::class, 'show'])->name('transactions.show');
Route::get('transactions/{id}/edit', [InventoryTransactionController::class, 'edit'])->name('transactions.edit');
Route::put('transactions/{id}', [InventoryTransactionController::class, 'update'])->name('transactions.update');
Route::patch('transactions/{id}/status', [InventoryTransactionController::class, 'updateStatus'])->name('transactions.update.status');
Route::get('admin/transactions/create', [InventoryTransactionController::class, 'createTransaction'])->name('transactions.create');
Route::delete('transactions/{id}', [InventoryTransactionController::class, 'destroy'])->name('transactions.destroy');
Route::post('/transactions/import', [InventoryTransactionController::class, 'import'])->name('transactions.import');
Route::post('/import-ingredients', [InventoryTransactionController::class, 'importIngredients'])->name('import.ingredients');


// review
Route::post('/submit-feedback', [ReservationController::class, 'submitFeedback']);



// import
Route::get('admin/supplier/import', [SupplierController::class, 'showImportForm'])->name('admin.supplier.import');
Route::post('admin/supplier/import', [SupplierController::class, 'import'])->name('admin.supplier.import');

Route::get('admin/supplier/download-template', [SupplierController::class, 'downloadTemplate'])->name('admin.supplier.download-template');
Route::get('admin/ingredient/download-template', [IngredientController::class, 'downloadTemplate'])->name('admin.ingredient.download-template');



Route::get('admin/ingredient/import', [IngredientController::class, 'showImportForm'])->name('admin.ingredient.import');
Route::post('admin/ingredient/import', [IngredientController::class, 'import'])->name('admin.ingredient.import.post');

Route::get('/', [HomeController::class, 'index'])->name('client.index');
Route::get('/menu', [MenuController::class, 'index'])->name('menu');

Route::get('/api/menu/filter', [MenuController::class, 'filterByCategory']);

Route::get('/menu/category/{category}', [MenuController::class, 'filterByCategory'])->name('menu.category');

//member
Route::get('/member', [MemberController::class, 'show'])->name('client.member');
Route::post('/update-member', [MemberController::class, 'update'])->name('member.update');
Route::post('/change-password', [MemberController::class, 'changePassword'])->name('member.changePassword');
Route::post('/member/update-booking', [MemberController::class, 'updateBooking'])->name('member.updateBooking');
Route::post('/member/reservation/update', [MemberController::class, 'updateReservation'])->name('member.reservation.update');

// web.php
// web.php
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('user', [UserController::class, 'index'])->name('user.index'); // Danh sách người dùng
    Route::get('user/employees', [UserController::class, 'employeeList'])->name('user.employees'); // Danh sách nhân viên
    Route::get('admin/user/{user}/edit', [UserController::class, 'edit'])->name('admin.user.edit');

    Route::delete('/admin/user/{id}', [UserController::class, 'destroy'])->name('admin.user.destroy');

});



// login

Route::get('/home', [HomeController::class, 'index'])->middleware('auth');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.client');






// account
// Route::get('/account', [AccountController::class, 'show'])->name('account.show');
// Route::post('/update-account', [AccountController::class, 'update'])->name('account.update');
// Route::post('/change-password', [AccountController::class, 'changePassword'])->name('account.changePassword');



// account
// Route::get('/account', [AccountController::class, 'show'])->name('account.show');
// Route::post('/update-account', [AccountController::class, 'update'])->name('account.update');
// Route::post('/change-password', [AccountController::class, 'changePassword'])->name('account.changePassword');

// route::get("/menu", function () {
//     return view("client.menu");
// })->name("menu.client");

route::get(
    "/booking",
    [ReservationController::class, 'showTime']
)->name("booking.client");
route::get(
    "/customerInformation",
    [ReservationController::class, "showInformation"]
)->name("customerInformation.client");
route::post(
    "/createReservation",
    [ReservationController::class, "createReservation"]
)->name("createReservation.client");
route::get(
    "deposit",
    [ReservationController::class, "showDeposit"]
)->name("deposit.client");
route::post(
    "MOMOCheckout",
    action: [OnlineCheckoutController::class, "onlineCheckout"]
)->name("MOMOCheckout.client");
Route::post('/check-order-status', [ReservationController::class, 'checkOrderStatus'])
    ->name('checkOrderStatus');




route::get("/about", function () {
    return view("client.about");
})->name("about.client");
route::get("/gallery", function () {
    return view("client.gallery");
})->name("gallery.client");
route::get("/blog", function () {
    return view("client.blog");
})->name("blog.client");
route::get("/contact", function () {
    return view("client.contact");
})->name("contact.client");
route::get("/blog-single", function () {
    return view("client.blog-single");
})->name("blog-single.client");
route::get(
    "reservationSuccessfully",
    [ReservationController::class, "reservationSuccessfully"]
)->name("reservationSuccessfully.client");
route::get(
    "createReservationWithMomo",
    [ReservationController::class, "createReservationWithMomo"]
)->name("createReservationWithMomo.client");


Route::get('/customerInformation', [ReservationController::class, 'showInformation'])->name('customer.information');



Route::get('/kitchen', [KitchenController::class, 'index'])->name('kitchen.index');
Route::post('/kitchen/{id}/cook-all', [KitchenController::class, 'cookAll'])->name('order-item.cook-all');
Route::post('/kitchen/{id}/done-all', [KitchenController::class, 'doneAll'])->name('order-item.cook-all');
Route::post('/kitchen/{id}/delete', [KitchenController::class, 'delete'])->name('order-item.cook-all');



// hoàn cọc
Route::get('/refunds/create/{reservation_id}', [RefundController::class, 'create'])->name('refunds.create');
Route::post('/refunds', [RefundController::class, 'store'])->name('refunds.store');
Route::get('/reservation', [ReservationController::class, 'index'])->name('reservation.index');
Route::post('/refunds/cancel', [RefundController::class, 'storeCancellation'])->name('refunds.cancel');
Route::patch('/refunds/{id}/updateStatus', [RefundController::class, 'updateStatus'])->name('refunds.updateStatus');

Route::get('/admin/refunds', [RefundController::class, 'index'])->name('admin.refunds.index');
Route::post('client/cancel-reservationpopup', [ReservationController::class, 'cancelReservationPopUp'])->name('client.cancel.reservationpopup');
Route::post('/reservation/cancel', [ReservationController::class, 'cancel'])->name('client.cancel.reservation');

Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
Route::post('/create-order', [PosController::class, 'createOrder']);
Route::post('/edit-order/{tableId}', [PosController::class, 'editOrder']);
Route::post('/mergeTables/{tableId}', [PosController::class, 'mergeTables']);
Route::post('/order-details/{tableId}', [PosController::class, 'orderDetails'])->name('order-details');
Route::post('/add-dish-to-order', [PosController::class, 'addDishToOrder']);
Route::post('/add-combo-to-order', [PosController::class, 'addComboToOrder']);
Route::post('/add-dish-waiting', [PosController::class, 'addDishWaiting']);
Route::post('/add-combo-waiting', [PosController::class, 'addComboWaiting']);
Route::post('/deleteItem', [PosController::class, 'deleteItem']);
Route::post('/deleteItemm', [PosController::class, 'deleteItemm']);
Route::post('/increaseQuantity', [PosController::class, 'increaseQuantity']);
Route::post('/increaseQuantityy', [PosController::class, 'increaseQuantityy']);
Route::post('/decreaseQuantity', [PosController::class, 'decreaseQuantity']);
Route::post('/decreaseQuantityy', [PosController::class, 'decreaseQuantityy']);
Route::post('/notification-button/{tableId}', [PosController::class, 'notificatioButton']);
Route::post('/canelItem', [PosController::class, 'canelItem']);
Route::post('/canelItemm', [PosController::class, 'canelItemm']);
Route::get('/Ppayment/{table_number}', [PosController::class, 'Ppayment'])->name('Ppayment');
Route::get('/viewCheckOut/{table_number}', [PosController::class, 'viewCheckOut'])->name('viewCheckOut');
Route::post('/check-payment-condition', [PosController::class, 'checkPaymentPondition'])->name('checkPaymentPondition');
Route::get('/checkAvailableTables', [PosController::class, 'checkAvailableTables']);
Route::post('/checkTables', [PosController::class, 'checkTables']);
Route::post('/checkOrders', [PosController::class, 'checkOrders']);

Route::middleware(['checkTableAndUser'])->group(function () {
    Route::get('/menu-order', [OrderController::class, 'menuOrder'])->name('menuOrder');
});
Route::middleware(['checkUser'])->group(function () {
    Route::get('/menu-selected/{table_number}', [OrderController::class, 'menuSelected'])->name('menuSelected');
    Route::get('/menu-history/{table_number}', [OrderController::class, 'menuHistory'])->name('menuHistory');
});
Route::post('/menuOrder/updatee', [OrderController::class, 'updateItemm'])->name('updateItem.menuOrder');
Route::get('/menuOrder/requestToOrder/{table_number}', [OrderController::class, 'requestToOrder'])->name('requestToOrder.menuOrder');


Route::get('reserToOrder/{reservationId}', [PosController::class, 'reserToOrder'])->name('ReToOr');


Route::post('/load-more-dishes', [PosController::class, 'loadMoreDishes']);
Route::get('/api/tables/{tableId}/order', [TableController::class, 'getOrderForReservedTable']);
Route::get('/reservations', [ReservationController::class, 'showReservations'])
    ->name('reservations.upcoming');
Route::get('/reservations/late', [PosController::class, 'getLateReservations'])
    ->name('reservations.late');
Route::post('/reservations', [PosController::class, 'store'])->name('reservations.store');
Route::delete('/order/{order_id}/item/{item_id}', [PosController::class, 'deleteOrderItem']);



Route::post('/reservation/check-table', [PosController::class, 'checkTable'])->name('reservation.checkTable');






route::post(
    "checkout/{orderID}",
    action: [ReservationController::class, "checkout"]
)->name("checkout.admin");

route::post(
    "checkoutt/{orderID}",
    action: [ReservationController::class, "checkoutt"]
)->name("checkout.adminn");

// // Process the offline payment (POST)
// Route::post('/payment/offline', [PosController::class, 'processPaymentOffline'])->name('processPaymentOffline');


// // Process the online payment (POST)
// Route::post('/payment/online', [PosController::class, 'processPaymentOnline'])->name('processPaymentOnline');


// Route::get('/receipt', [PosController::class, 'showReceipt'])->name('pos.receipt');


























Route::get('admin', [AdminController::class, 'index']);
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/', function () {
        return view('auth.login');
    })->name('home');



    Route::resource('table', TableController::class);
    // Trash - Xoá mềm - Khôi Phục
    Route::get('tables/trash', [TableController::class, 'trash'])->name('tables.trash');
    Route::patch('table/{id}/restore', [TableController::class, 'restore'])->name('table.restore');
    Route::delete('table/{id}/force-delete', [TableController::class, 'forceDelete'])->name('table.forceDelete');

    /// xếp bàn cho khách
    Route::get('reservation/{reservationId}/assign-tables', [ReservationController::class, 'assignTables'])->name('reservation.assignTables');
    Route::get('reservation/assign-table', [ReservationController::class, 'assignTable'])->name('assignTable');
    Route::post('reservation/submit-table', [ReservationController::class, 'submitTable'])->name('submit.tables');
    Route::post('reservation/submit-move-table', [ReservationController::class, 'submitMoveTable'])->name('submit.Movetables');

    Route::resource('reservation', ReservationController::class);
    Route::post('reservation/cancel/{id}', [ReservationController::class, 'cancel'])->name('reservation.cancel');
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::get('/admin/reservations', [ReservationController::class, 'adminIndex'])->name('reservations.admin');
    Route::put('/api/reservations/{id}', [ReservationController::class, 'updateReservation']);
    Route::resource('reservationHistory', ReservationHistoryController::class);
    Route::put('/reservations/calendar/{id}', [ReservationController::class, 'updateCalendar']);
    Route::post('/reservations/cancel/{id}', [ReservationController::class, 'processReservationCancellation']);




    Route::resource('category', CategoryController::class);
    // Trash - Xoá mềm - Khôi Phục
    Route::get('category-trash', [CategoryController::class, 'trash'])->name('category.trash');
    Route::patch('category-restore/{id}', [CategoryController::class, 'restore'])->name('category.restore');
    Route::delete('category-force-delete/{id}', [CategoryController::class, 'forceDelete'])->name('category.forceDelete');


    Route::resource('dishes', DishesController::class);
    // Trash - Xoá mềm - Khôi Phuc
    Route::get('dishes-trash', [DishesController::class, 'trash'])->name('dishes.trash');
    Route::patch('dishes-restore/{id}', [DishesController::class, 'restore'])->name('dishes.restore');
    Route::delete('dishes-force-delete/{id}', [DishesController::class, 'forceDelete'])->name('dishes.forceDelete');
    Route::get('dishes/{id}', [DishesController::class, 'show'])->name('dishes.detail');
    Route::get('dishes/{id}/update-ingredients', [DishesController::class, 'editIngredients'])->name('dishes.updateIngredients');
    Route::post('dishes/{id}/update-ingredients', [DishesController::class, 'updateIngredients'])->name('dishes.updateIngredients');
    Route::delete('dishes/{recipeId}/deleteIngredient', [DishesController::class, 'deleteIngredient'])->name('dishes.deleteIngredient');
    Route::post('dishes/{dish}/add-ingredient', [DishesController::class, 'addIngredient'])->name('dishes.addIngredient');
    Route::post('dishes/{id}/toggle-status', [DishesController::class, 'toggleStatus'])->name('dishes.toggleStatus');

    // Route::resource('recipes', RecipesController::class);






    Route::resource('combo', ComboController::class);
    // Trash - Xoá mềm - Khôi Phuc
    Route::get('combo-trash', [ComboController::class, 'trash'])->name('combo.trash');
    Route::patch('combo-restore/{id}', [ComboController::class, 'restore'])->name('combo.restore');
    Route::delete('combo-force-delete/{id}', [ComboController::class, 'forceDelete'])->name('combo.forceDelete');
    Route::post('/combo/{combo}/toggle-status', [ComboController::class, 'toggleStatus'])->name('combo.toggleStatus');

    Route::resource('payment', PaymentController::class);
    // Trash - Xoá mềm - Khôi Phuc
    Route::get('payment-trash', [PaymentController::class, 'trash'])->name('payment.trash');
    Route::patch('payment-restore/{id}', [PaymentController::class, 'restore'])->name('payment.restore');
    Route::delete('payment-force-delete/{id}', [PaymentController::class, 'forceDelete'])->name('payment.forceDelete');


    Route::resource('order', OrderController::class);
    // Trash - Xoá mềm - Khôi Phuc
    Route::get('order-trash', [OrderController::class, 'trash'])->name('order.trash');
    Route::patch('order-restore/{id}', [OrderController::class, 'restore'])->name('order.restore');
    Route::delete('order-force-delete/{id}', [OrderController::class, 'forceDelete'])->name('order.forceDelete');


    Route::resource('coupon', CouponController::class);
    // Trash - Xoá mềm - Khôi Phuc
    Route::get('coupon-trash', [CouponController::class, 'trash'])->name('coupon.trash');
    Route::patch('coupon-restore/{id}', [CouponController::class, 'restore'])->name('coupon.restore');
    Route::delete('coupon-force-delete/{id}', [CouponController::class, 'forceDelete'])->name('coupon.forceDelete');
    Route::get('/coupon/check', [CouponController::class, 'checkCoupon'])->name('coupon.check');



    route::resource('feedback', FeedbackController::class);

    //permission user
    Route::resource('user', UserController::class);
    // Trash - Xoá mềm - Khôi Phuc
    Route::get('user-trash', [UserController::class, 'trash'])->name('user.trash');
    Route::patch('user-restore/{id}', [UserController::class, 'restore'])->name('user.restore');
    Route::delete('user-force-delete/{id}', [UserController::class, 'forceDelete'])->name('user.forceDelete');
    Route::get('users/{id}/show', [UserController::class, 'show'])->name('admin.user.show');


    //permission role
    Route::resource('role', RoleController::class);
    // Trash - Xoá mềm - Khôi Phuc
    Route::get('role-trash', [RoleController::class, 'trash'])->name('role.trash');
    Route::patch('role-restore/{id}', [RoleController::class, 'restore'])->name('role.restore');
    Route::delete('role-force-delete/{id}', [RoleController::class, 'forceDelete'])->name('role.forceDelete');

    // Route::resource('permission', PermissionController::class);
    Route::resource('supplier', SupplierController::class);
    // Route::resource('recipes', RecipesController::class);

    Route::resource('ingredient', IngredientController::class);

    // Route::get('/dashboard/revenue', [DashboardController::class, 'getRevenue'])->name('dashboard.revenue');

    Route::resource('dashboard', DashboardController::class);






    Route::resource('report', ReportController::class);

    Route::resource('accountSetting', SettingController::class);
    Route::resource('inventory', InventoryController::class);
    Route::resource('inventoryDashboard', InventoryDBController::class);

    // Lịch
    Route::resource('calendar', CalendarController::class);

    //inventoy









    //permission route
    Route::get('/admin/permission', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/admin/permission/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/admin/permission', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/admin/permission/{id}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('/admin/permission/{id}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('admin/permission/{id}', [PermissionController::class, 'destroy'])->name('permissions.destroy');

    //roles route
    // Route::get('/admin/permission', [PermissionController::class, 'index'])->name('permissions.index');
    // Route::get('/admin/permission/create', [PermissionController::class, 'create'])->name('permissions.create');
    // Route::post('/admin/permission', [PermissionController::class, 'store'])->name('permissions.store');
    // Route::get('/admin/permission/{id}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    // Route::put('/admin/permission/{id}', [PermissionController::class, 'update'])->name('permissions.update');
    // Route::delete('admin/permission/{id}', [PermissionController::class, 'destroy'])->name('permissions.destroy');


});


Route::get('/register-client', [CustomerAuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register-client', [CustomerAuthController::class, 'register'])->name('client.register');
Route::get('/login-client', [CustomerAuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login-client', [CustomerAuthController::class, 'login'])->name('client.login');
Route::post('/logout-client', [CustomerAuthController::class, 'logout'])->name('client.logout');

Route::post('/check-account', [CustomerAuthController::class, 'checkAccount']);
Route::post('/login-success', [CustomerAuthController::class, 'loginSuccess'])->name('login.success');



Route::post('/verify-code', [CustomerAuthController::class, 'verifyCode'])->name('verify.code');

Route::middleware(['auth'])->group(function () {
    Route::get('/member/profile', [MemberController::class, 'showProfile'])->name('member.profile');
    Route::post('/member/update-booking', [MemberController::class, 'updateBooking'])->name('member.updateBooking');
    Route::post('/cancel-reservation', [ReservationController::class, 'cancelReservation'])->name('cancel.reservation');
});



Route::get('/register-client', [CustomerAuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register-client', [CustomerAuthController::class, 'register'])->name('client.register');
Route::get('/login-client', [CustomerAuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login-client', [CustomerAuthController::class, 'login'])->name('client.login');
Route::post('/logout-client', [CustomerAuthController::class, 'logout'])->name('client.logout');

Route::post('/check-account', [CustomerAuthController::class, 'checkAccount']);
Route::post('/login-success', [CustomerAuthController::class, 'loginSuccess'])->name('login.success');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::post('/verify-code', [CustomerAuthController::class, 'verifyCode'])->name('verify.code');

Route::middleware(['auth'])->group(function () {
    Route::get('/member/profile', [MemberController::class, 'showProfile'])->name('member.profile');
    Route::post('/member/update-booking', [MemberController::class, 'updateBooking'])->name('member.updateBooking');
});



Route::get('/member/profile', [MemberController::class, 'showProfile'])->name('member.profile');
Route::post('/member/update-booking', [MemberController::class, 'updateBooking'])->name('member.updateBooking');

Route::post('/cancel-reservation', [ReservationController::class, 'cancelReservation'])->name('cancel.reservation');


Route::post('/send-otp', [ReservationController::class, 'sendOtp'])->name('send.otp');
Route::post('/verify-otp', [ReservationController::class, 'verifyOtp'])->name('verify.otp');


Route::post('/api/cancel-booking/{id}', [ReservationController::class, 'cancelReservation'])->name('cancel.booking');
Route::post('/cancel-booking/{id}', [ReservationController::class, 'cancelReservation'])->name('cancel.booking');


Route::get('/login1', function () {
    return view('client.login');
});
Route::get('/test', function () {
    return view('test');
});



// Route đăng nhập của khách hàng
Route::get('/register-client', [CustomerAuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register-client', [CustomerAuthController::class, 'register'])->name('client.register');
Route::get('/login-client', [CustomerAuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login-client', [CustomerAuthController::class, 'login'])->name('client.login');
Route::post('/logout-client', [CustomerAuthController::class, 'logout'])->name('client.logout');

Route::post('/check-account', [CustomerAuthController::class, 'checkAccount']);
Route::post('/login-success', [CustomerAuthController::class, 'loginSuccess'])->name('login.success');




Route::post('/verify-code', [CustomerAuthController::class, 'verifyCode'])->name('verify.code');

Route::middleware(['auth'])->group(function () {
    Route::get('/member/profile', [MemberController::class, 'showProfile'])->name('member.profile');
    Route::post('/member/update-booking', [MemberController::class, 'updateBooking'])->name('member.updateBooking');
});



Route::get('/member/profile', [MemberController::class, 'showProfile'])->name('member.profile');
Route::post('/member/update-booking', [MemberController::class, 'updateBooking'])->name('member.updateBooking');

Route::post('/cancel-reservation', [ReservationController::class, 'cancelReservation'])->name('cancel.reservation');


Route::post('/send-otp', [ReservationController::class, 'sendOtp'])->name('send.otp');
Route::post('/verify-otp', [ReservationController::class, 'verifyOtp'])->name('verify.otp');


Route::post('/api/cancel-booking/{id}', [ReservationController::class, 'cancelReservation'])->name('cancel.booking');
Route::post('/cancel-booking/{id}', [ReservationController::class, 'cancelReservation'])->name('cancel.booking');



//session otp đặt bàn
Route::post('/store-otp-session', [ReservationController::class, 'storeOtpSession'])->name('storeOtpSession');

Route::get('/test', function () {
    return view('test');
});

Route::get('/test', [ReservationController::class, 'getBanks']);
Route::get('/print/{orderId}', [ReservationController::class, 'print'])->name('print.page');


require __DIR__ . '/auth.php';
