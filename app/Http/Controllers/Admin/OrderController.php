<?php

namespace App\Http\Controllers\Admin;

use App\Events\CartUpdated;
use App\Events\ComboStatusUpdated;
use App\Events\DishStatusUpdated;
use App\Events\ItemUpdated;
use App\Events\MenuOrderUpdateItem;
use App\Events\NotifyAdminEvent;
use App\Events\PosTableUpdated;
use App\Events\PosTableUpdatedWithNoti;
use App\Events\UpdateComboStatus;
use App\Events\UpdateDishStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Combo;
use App\Models\Dishes;
use App\Models\InventoryStock;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use App\Traits\TraitCRUD;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct()
    {
        // Gán middleware cho các phương thức
        $this->middleware('permission:Xem order', ['only' => ['index']]);
        $this->middleware('permission:Tạo mới order', ['only' => ['create']]);
        $this->middleware('permission:Sửa order', ['only' => ['edit']]);
        $this->middleware('permission:Xóa order', ['only' => ['destroy']]);
    }

    use TraitCRUD;

    protected $model = Order::class;
    protected $viewPath = 'admin.order';
    protected $routePath = 'admin.order';

    public function index(Request $request)
    {
        $title = 'Hóa Đơn';
        // Lấy tham số sort, direction, id, và status từ request
        $sort = $request->input('sort', 'id');
        $direction = $request->input('direction', 'desc');
        $id = $request->input('id');
        $status = $request->input('status');

        // Xác nhận cột sắp xếp hợp lệ
        $allowedSorts = ['id', 'reservation_id', 'total_amount', 'final_amount'];
        $sort = in_array($sort, $allowedSorts) ? $sort : 'id';

        // Xác nhận thứ tự sắp xếp hợp lệ
        $direction = in_array($direction, ['asc', 'desc']) ? $direction : 'desc';

        // Lấy danh sách hóa đơn, áp dụng sắp xếp
        $orders = Order::query();

        // Lọc theo mã đơn hàng nếu có
        if ($id) {
            $orders->where('id', 'like', "%{$id}%");
        }

        // Lọc theo trạng thái nếu có
        if ($status) {
            $orders->where('status', $status);
        }

        // Áp dụng sắp xếp
        $orders = $orders->orderBy($sort, $direction)->paginate(10);

        return view('admin.order.index', compact('orders', 'title'));

    }



    public function create()
    {
        return view('admin.order.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reservation_id' => 'required|integer|exists:reservations,id',
            'staff_id' => 'required|integer|exists:users,id',
            'table_id' => 'required|integer|exists:tables,id',
            'total_amount' => 'required|numeric',
            'final_amount' => 'required|numeric',
            'status' => 'required|in:Completed,Pending,Cancelled',
        ]);

        Order::create($validated);

        return redirect()->route('admin.order.index')->with('success', 'Đơn hàng đã được thêm thành công.');
    }

    public function show($id)
    {
        $title = 'Chi Tiết Hoá Đơn';
        $order = Order::with(['staff', 'reservation', 'customer'])->findOrFail($id);
        return view('admin.order.show', compact('order', 'title'));
    }

    public function edit(Order $order)
    {
        // Trả về view chỉnh sửa với dữ liệu đơn hàng
        $title = 'Chỉnh Sửa Hoá Đơn';
        return view('admin.order.edit', compact('order', 'title'));
    }

    public function update(Request $request, Order $order)
    {
        // Xác thực dữ liệu người dùng nhập vào
        $validated = $request->validate([
            'reservation_id' => 'required|integer|exists:reservations,id', // Kiểm tra mã đặt chỗ hợp lệ
            'staff_id' => 'required|integer|exists:users,id', // Kiểm tra nhân viên hợp lệ
            'total_amount' => 'required|numeric|min:0', // Tổng tiền phải là số hợp lệ và >= 0
            'order_type' => 'required|in:dine_in,take_away,delivery', // Loại đơn hàng phải hợp lệ
            'status' => 'nullable|in:pending,completed,cancelled', // Trạng thái có thể thay đổi hoặc không
            'discount_amount' => 'nullable|numeric|min:0', // Số tiền giảm giá có thể là null nhưng nếu có phải là số hợp lệ
            'final_amount' => 'required|numeric|min:0', // Số tiền cuối cùng phải là số hợp lệ và >= 0
        ]);

        // Lấy trạng thái hiện tại và trạng thái mới
        $currentStatus = $order->status;
        $newStatus = $validated['status'] ?? $currentStatus;

        // Kiểm tra trạng thái ngược
        if ($this->isStatusReversal($currentStatus, $newStatus)) {
            return redirect()->back()->with('error', 'Không thể cập nhật trạng thái ngược.');
        }

        // Kiểm tra tính hợp lệ của số tiền cuối cùng
        $discountAmount = $validated['discount_amount'] ?? 0;

        if ($validated['final_amount'] < $validated['total_amount'] - $discountAmount) {
            return redirect()->back()->with('error', 'Số tiền cuối cùng không hợp lệ. Nó phải lớn hơn hoặc bằng tổng tiền trừ số tiền giảm giá.');
        }

        // Chuẩn bị dữ liệu để cập nhật
        $orderData = [
            'reservation_id' => $validated['reservation_id'],
            'staff_id' => $validated['staff_id'],
            'total_amount' => $validated['total_amount'],
            'order_type' => $validated['order_type'],
            'discount_amount' => $discountAmount,
            'final_amount' => $validated['final_amount'],
            'status' => $newStatus,
        ];

        // Cập nhật thông tin đơn hàng
        $order->update($orderData);

        return redirect()->route('admin.order.index')->with('success', 'Đơn hàng đã được cập nhật thành công.');
    }

    /**
     * Kiểm tra trạng thái ngược.
     *
     * @param string $currentStatus
     * @param string $newStatus
     * @return bool
     */
    private function isStatusReversal($currentStatus, $newStatus)
    {
        $statusOrder = [
            'pending' => 1,    // Trạng thái khởi tạo
            'completed' => 2,  // Trạng thái hoàn thành
            'cancelled' => 3,  // Trạng thái hủy
        ];

        // Kiểm tra thứ tự trạng thái
        return isset($statusOrder[$currentStatus], $statusOrder[$newStatus]) &&
            $statusOrder[$currentStatus] > $statusOrder[$newStatus];
    }









    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        if ($order->status == 'Pending') {
            return redirect()->route('admin.order.index')->with('error', 'Không thể xóa đơn hàng đang trong trạng thái Đang xử lý. Vui lòng thực hiện thao tác khác.');
        }

        $order->delete();

        return redirect()->route('admin.order.index')->with('success', 'Đơn hàng đã được xóa thành công.');
    }


    public function trash()
    {
        $title = 'Khôi Phục Danh Sách Hoá Đơn';
        $orders = Order::onlyTrashed()->paginate(10);
        return view('admin.order.trash', compact('orders', 'title'));
    }

    public function restore($id)
    {
        $order = Order::withTrashed()->findOrFail($id);
        $order->restore();

        return redirect()->route('admin.order.trash')->with('success', 'Đơn hàng đã được khôi phục thành công.');
    }

    public function forceDelete($id)
    {
        $order = Order::withTrashed()->findOrFail($id);
        $order->forceDelete();

        return redirect()->route('admin.order.trash')->with('success', 'Đơn hàng đã được xóa vĩnh viễn.');
    }
    public function menuOrder(Request $request)
    {
        try {
            $tableId = $request->input('data');
            $table = Table::findOrFail($tableId);
            $order = $table->orders->where('status', 'pending')->first();
            $item = $order->items
                ->where('status', '==', 'chưa yêu cầu')
            ;
            $dishes = Dishes::all();
            $combo = Combo::all();
            $cate = Category::all();
            $total = $item->sum(function ($item) {
                return $item->price * $item->quantity;
            });
            return view('client.menuOrder', compact('total', 'cate', 'table', 'order', 'item', 'dishes', 'combo'));
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function menuSelected($id)
    {
        $table = Table::findOrFail($id);
        $order = $table->orders->where('status', 'pending')->first();
        $item = $order->items
            ->where('status', '==', 'chưa yêu cầu')
        ;
        if ($item->count() == 0) {
            return redirect()->route('menuHistory', $id);
        }
        ;
        $total = $item->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        $url = route('menuOrder', ['data' => $id]);
        return view('client.menuSelected', compact('item', 'table', 'order', 'url', 'total'));
    }
    public function updateItemm(Request $request)
    {
        $item_id = $request->item_id;
        $action = $request->action;
        $item_type = $request->item_type;
        $table = $request->table;
        DB::transaction(function () use ($item_id, $item_type, $action, $table) {
            if ($item_type == 'dish') {
                $item = OrderItem::where('item_id', $item_id)
                    ->where('item_type', 1)
                    ->where('status', 'chưa yêu cầu')
                    ->lockForUpdate()
                    ->first();
            } else {
                $item = OrderItem::where('item_id', $item_id)
                    ->where('item_type', 2)
                    ->where('status', 'chưa yêu cầu')
                    ->lockForUpdate()
                    ->first();
            }
            if ($action === 'increase') {
                $item->quantity += 1;
                if ($item_type == 'dish') {
                    $reciep = Dishes::findOrFail($item_id)->recipes;
                    foreach ($reciep as $recipe) {
                        $inventoryStock = InventoryStock::where('ingredient_id', $recipe->ingredient_id)->first();
                        $inventoryStock->quantity_reserved += $recipe->quantity_need;
                        if ($inventoryStock->quantity_reserved >= $inventoryStock->quantity_stock) {
                            DB::rollBack();
                            $dish = Dishes::find($item_id);
                            $dish->is_active = 0;
                            $dish->status = 'out_of_stock';
                            $dish->save();
                            broadcast(new DishStatusUpdated($dish));
                            broadcast(new UpdateDishStatus($dish));
                            broadcast(new NotifyAdminEvent('Người dùng đã yêu cầu gọi món, thanh toán sẽ bị hủy!', $table));
                            return response()->json([
                                'success' => false,
                            ]);
                        }
                        $inventoryStock->save();
                    }
                } else {
                    $combos = Combo::findOrFail($item_id)->dishes;
                    foreach ($combos as $combo) {
                        $reciep = $combo->recipes;
                        foreach ($reciep as $recipe) {
                            $inventoryStock = InventoryStock::where('ingredient_id', $recipe->ingredient_id)->first();
                            $inventoryStock->quantity_reserved += $recipe->quantity_need * $combo->pivot->quantity;
                            if ($inventoryStock->quantity_reserved >= $inventoryStock->quantity_stock) {
                                DB::rollBack();
                                $combo = Combo::find($item_id);
                                $combo->is_active = 0;
                                $combo->save();
                                broadcast(new ComboStatusUpdated($combo));
                                broadcast(new UpdateComboStatus($combo));
                                broadcast(new NotifyAdminEvent('Người dùng đã yêu cầu gọi món, thanh toán sẽ bị hủy!', $table));
                                return response()->json([
                                    'success' => false
                                ]);
                            }
                            $inventoryStock->save();
                        }
                    }
                }
            } elseif ($action === 'decrease') {
                $item->quantity = max(0, $item->quantity - 1);
                if ($item_type == 'dish') {
                    $reciep = Dishes::findOrFail($item_id)->recipes;
                    foreach ($reciep as $recipe) {
                        $inventoryStock = InventoryStock::where('ingredient_id', $recipe->ingredient_id)->first();
                        $inventoryStock->quantity_reserved -= $recipe->quantity_need;
                        $inventoryStock->save();
                    }
                    $dish = Dishes::find($item_id);
                    $dish->is_active = 1;
                    $dish->status = 'available';
                    $dish->save();
                    broadcast(new DishStatusUpdated($dish));
                    broadcast(new UpdateDishStatus($dish));
                } else {
                    $combos = Combo::findOrFail($item_id)->dishes;
                    foreach ($combos as $combo) {
                        $reciep = $combo->recipes;
                        foreach ($reciep as $recipe) {
                            $inventoryStock = InventoryStock::where('ingredient_id', $recipe->ingredient_id)->first();
                            $inventoryStock->quantity_reserved -= $recipe->quantity_need * $combo->pivot->quantity;
                            $inventoryStock->save();
                        }
                    }
                    $combo = Combo::find($item_id);
                    $combo->is_active = 1;
                    $combo->save();
                    broadcast(new ComboStatusUpdated($combo));
                    broadcast(new UpdateComboStatus($combo));
                }
                if ($item->quantity == 0) {
                    $item->delete();
                    if ($item_type == 'dish') {
                        $reciep = Dishes::findOrFail($item_id)->recipes;
                        foreach ($reciep as $recipe) {
                            $inventoryStock = InventoryStock::where('ingredient_id', $recipe->ingredient_id)->first();
                            $inventoryStock->quantity_reserved -= $recipe->quantity_need;
                            $inventoryStock->save();
                        }
                        $dish = Dishes::find($item_id);
                        $dish->is_active = 1;
                        $dish->status = 'available';
                        $dish->save();
                        broadcast(new DishStatusUpdated($dish));
                        broadcast(new UpdateDishStatus($dish));
                    } else {
                        $combos = Combo::findOrFail($item_id)->dishes;
                        foreach ($combos as $combo) {
                            $reciep = $combo->recipes;
                            foreach ($reciep as $recipe) {
                                $inventoryStock = InventoryStock::where('ingredient_id', $recipe->ingredient_id)->first();
                                $inventoryStock->quantity_reserved -= $recipe->quantity_need * $item->quantity * $combo->pivot->quantity;
                                $inventoryStock->save();
                            }
                        }
                        $combo = Combo::find($item_id);
                        $combo->is_active = 1;
                        $combo->save();
                        broadcast(new ComboStatusUpdated($combo));
                        broadcast(new UpdateComboStatus($combo));
                    }
                    $tableId = Table::find($table)->orders->where('status', 'pending')->first()->id;
                    $total = OrderItem::where('order_id', $tableId)
                        ->where('status', 'chưa yêu cầu')
                        ->get()
                        ->sum(function ($item) {
                            return $item->price * $item->quantity;
                        });
                    broadcast(new MenuOrderUpdateItem([
                        'id' => $item_id,
                        'deleted' => true,
                        'type' => $item_type,
                        'table' => $table,
                        'total' => $total
                    ]));
                    $item = OrderItem::where('order_id', $tableId)
                        ->where('status', 'chưa yêu cầu')
                        ->get();
                    $countItems = $item->sum('quantity');
                    $total = $item->sum(function ($items) {
                        return $items->price * $items->quantity;
                    });
                    broadcast(new CartUpdated($countItems, $total, $table))->toOthers();
                    broadcast(new NotifyAdminEvent('Người dùng đã yêu cầu gọi món, thanh toán sẽ bị hủy!', $table));
                    return response()->json(['success' => true]);
                }
            } elseif ($action === 'remove') {
                $item->delete();
                if ($item_type == 'dish') {
                    $reciep = Dishes::findOrFail($item_id)->recipes;
                    foreach ($reciep as $recipe) {
                        $inventoryStock = InventoryStock::where('ingredient_id', $recipe->ingredient_id)->first();
                        $inventoryStock->quantity_reserved -= $recipe->quantity_need;
                        $inventoryStock->save();
                    }
                    $dish = Dishes::find($item_id);
                    $dish->is_active = 1;
                    $dish->status = 'available';
                    $dish->save();
                    broadcast(new DishStatusUpdated($dish));
                    broadcast(new UpdateDishStatus($dish));
                } else {
                    $combos = Combo::findOrFail($item_id)->dishes;
                    foreach ($combos as $combo) {
                        $reciep = $combo->recipes;
                        foreach ($reciep as $recipe) {
                            $inventoryStock = InventoryStock::where('ingredient_id', $recipe->ingredient_id)->first();
                            $inventoryStock->quantity_reserved -= $recipe->quantity_need * $item->quantity * $combo->pivot->quantity;
                            $inventoryStock->save();
                        }
                    }
                    $combo = Combo::find($item_id);
                    $combo->is_active = 1;
                    $combo->save();
                    broadcast(new ComboStatusUpdated($combo));
                    broadcast(new UpdateComboStatus($combo));
                }
                $tableId = Table::find($table)->orders->where('status', 'pending')->first()->id;

                $total = OrderItem::where('order_id', $tableId)
                    ->where('status', 'chưa yêu cầu')
                    ->get()
                    ->sum(function ($item) {
                        return $item->price * $item->quantity;
                    });
                broadcast(new MenuOrderUpdateItem([
                    'id' => $item_id,
                    'deleted' => true,
                    'type' => $item_type,
                    'table' => $table,
                    'total' => $total
                ]));
                $item = OrderItem::where('order_id', $tableId)
                    ->where('status', 'chưa yêu cầu')
                    ->get();
                $countItems = $item->sum('quantity');
                $total = $item->sum(function ($items) {
                    return $items->price * $items->quantity;
                });
                broadcast(new CartUpdated($countItems, $total, $table))->toOthers();
                return response()->json(['success' => true]);
            }

            $item->save();
            $tableId = Table::find($table)->orders->where('status', 'pending')->first()->id;
            $total = OrderItem::where('order_id', $tableId)
                ->where('status', 'chưa yêu cầu')
                ->get()
                ->sum(function ($item) {
                    return $item->price * $item->quantity;
                });
            broadcast(new MenuOrderUpdateItem([
                'id' => $item->item_id,
                'type' => $item_type,
                'name' => $item->item_type == 1 ? $item->dish->name : $item->combo->name,
                'image' => asset('storage/' . $item->dish->image),
                'price' => $item->price,
                'quantity' => $item->quantity,
                'table' => $table,
                'total' => $total
            ]));
            $item = OrderItem::where('order_id', $tableId)
                ->where('status', 'chưa yêu cầu')
                ->get();
            $countItems = $item->sum('quantity');
            $total = $item->sum(function ($items) {
                return $items->price * $items->quantity;
            });
            broadcast(new CartUpdated($countItems, $total, $table))->toOthers();
        });
        return response()->json(['success' => 'Item updated successfully']);
    }
    public function menuHistory($id)
    {
        $table = Table::findOrFail($id);
        $order = $table->orders->where('status', 'pending')->first();
        $item = $order->items
            ->where('status', '!=', 'chưa yêu cầu')
        ;
        if ($item->count() == 0) {
            return redirect("menu-order?data=$id")->with('error', 'Vui lòng gọi món trước!');
        }
        ;
        $total = $order->items
            ->where('status', '!=', 'chưa yêu cầu')
            ->filter(function ($item) {
                return $item->status != 'hủy';
            })
            ->sum(function ($item) {
                return $item->price * $item->quantity;
            });
        $url = route('menuOrder', ['data' => $id]);
        return view('client.menuHistory', compact('item', 'table', 'order', 'url', 'total'));
    }
    public function requestToOrder($id)
    {
        DB::transaction(function () use ($id) {
            $table = Table::find($id);
            $order = $table->orders->where('status', 'pending')->first();
            $waitingItems = $order->items->where('status', 'chưa yêu cầu');
            $orderItems = $order->items->where('status', '!=', 'hủy')->where('status', '!=', 'chưa yêu cầu');
            foreach ($waitingItems as $waitingItem) {
                $existingItem = $orderItems->firstWhere('item_id', $waitingItem->item_id);
                if ($existingItem) {
                    $existingItem->quantity += $waitingItem->quantity;
                    if ($existingItem->status == 'hoàn thành') {
                        $waitingItem->status = 'đang xử lý';
                    }
                    $existingItem->save();
                    $waitingItem->delete();
                } else {
                    $waitingItem->status = 'chờ xử lý';
                    $waitingItem->save();
                }
                $order->total_amount += $waitingItem->price * $waitingItem->quantity;
                $order->save();
            }
            $orderItems = Order::with([
                'orderItems' => function ($query) {
                    $query->where('status', '!=', 'hủy')
                        ->where('status', '!=', 'chưa yêu cầu')
                    ;
                },
                'orderItems.dish:id,name',
                'orderItems.combo:id,name',
                'reservation:id,user_name,guest_count',
                'customer:id,name'
            ])->findOrFail($order->id);
            $tableId = Table::with('orders')->findOrFail($id);
            $orderItem = OrderItem::where('order_id', $order->id)
                ->where(function ($query) {
                    $query->where('status', '!=', 'hủy')
                        ->where('status', '!=', 'chưa yêu cầu')
                    ;
                })
                ->get();
            $checkoutBtn = false;
            $countItems = $orderItem->count();
            if ($countItems > 0) {
                $checkoutBtn = true;
            }
            $notiBtn = false;
            foreach ($orderItem as $item) {
                if ($item->quantity > $item->informed) {
                    $notiBtn = true;
                    break;
                } else {
                    $notiBtn = false;
                }
            }
            broadcast(new PosTableUpdatedWithNoti($orderItems, $tableId, $notiBtn, "Bàn $tableId->table_number đã yêu cầu gọi món!"))->toOthers();
            $item = OrderItem::where('order_id', $order->id)
                ->where('status', 'chưa yêu cầu')
                ->get();
            $countItems = $item->sum('quantity');
            $total = $item->sum(function ($items) {
                return $items->price * $items->quantity;
            });
            broadcast(new CartUpdated($countItems, $total, $id))->toOthers();
            $orderItem = OrderItem::with(['dish:id,name,image', 'combo:id,name,image'])
                ->where('order_id', $order->id)
                ->where('status', '!=', 'chưa yêu cầu')
                ->get();
            $orderItemArray = $orderItem->toArray();
            broadcast(new ItemUpdated($orderItemArray, 'Danh sách đã được cập nhật!', $id))->toOthers();
            broadcast(new NotifyAdminEvent('Người dùng đã yêu cầu gọi món, thanh toán sẽ bị hủy!', $id));
        });
        return redirect()->route('menuHistory', $id);
    }
}
