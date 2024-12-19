<?php

namespace App\Http\Controllers;

use App\Events\CartUpdated;
use App\Events\ItemUpdated;
use App\Events\PosTableUpdated;
use App\Events\PosTableUpdatedWithNoti;
use App\Events\ProcessingDishes;
use App\Events\ProvideDishes;
use App\Models\Combo;
use App\Models\Dishes;
use App\Models\InventoryItem;
use App\Models\InventoryStock;
use App\Models\InventoryTransaction;
use App\Models\Kitchen;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Scalar\MagicConst\Dir;

class KitchenController extends Controller
{

    public function __construct()
    {
        // Gán middleware cho các phương thức
        $this->middleware('permission:Xem bếp', ['only' => ['index']]);

    }

    public function index()
    {
        $items = Kitchen::where('status', 'đang chế biến')
            ->with(['dish', 'order.tables'])
            ->get();
        $items1 = Kitchen::where('status', 'chờ cung ứng')
            ->with(['dish', 'order.tables'])
            ->get();
        return view("kitchen.index", compact("items", "items1"));
    }
    public function cookAll($id, Request $request)
    {
        DB::transaction(function () use ($id, $request) {
            $item = Kitchen::find($id);
            if ($item->item_type == 1) {
                $reciep = Dishes::findOrFail($item->item_id)->recipes;
                foreach ($reciep as $recipe) {
                    $inventoryStock = InventoryStock::where('ingredient_id', $recipe->ingredient_id)->first();
                    $inventoryStock->quantity_reserved -= $recipe->quantity_need * $item->quantity;
                    $inventoryStock->quantity_stock -= $recipe->quantity_need * $item->quantity;
                    $inventoryStock->save();
                    $inventoryTransaction = InventoryTransaction::create([
                        'transaction_type' => 'xuất',
                        'staff_id' => Auth::user()->id,
                        'total_amount' => $recipe->quantity_need * $item->quantity * $recipe->ingredient->price,
                        'description' => 'Xuất nguyên liệu chế biến món',
                        'status' => 'hoàn thành'
                    ]);
                    $inventoryItems = InventoryItem::create([
                        'ingredient_id' => $recipe->ingredient_id,
                        'inventory_transaction_id' => $inventoryTransaction->id,
                        'quantity' => $recipe->quantity_need * $item->quantity
                    ]);
                }
            } else {
                $combos = Combo::findOrFail($item->item_id)->dishes;
                foreach ($combos as $combo) {
                    $reciep = $combo->recipes;
                    foreach ($combo as $recipe) {
                        $inventoryStock = InventoryStock::where('ingredient_id', $recipe->ingredient_id)->first();
                        $inventoryStock->quantity_reserved -= $recipe->quantity_need * $item->quantity;
                        $inventoryStock->quantity_stock -= $recipe->quantity_need * $item->quantity;
                        $inventoryStock->save();
                        $inventoryTransaction = InventoryTransaction::create([
                            'transaction_type' => 'xuất',
                            'staff_id' => Auth::user()->id,
                            'total_amount' => $recipe->quantity_need * $item->quantity * $recipe->ingredient->price,
                            'description' => 'Xuất nguyên liệu chế biến món',
                            'status' => 'hoàn thành'
                        ]);
                        $inventoryItems = InventoryItem::create([
                            'ingredient_id' => $recipe->ingredient_id,
                            'inventory_transaction_id' => $inventoryTransaction->id,
                            'quantity' => $recipe->quantity_need * $item->quantity
                        ]);
                    }
                }
            }
            $orderItem = OrderItem::where('item_id', $item->item_id)
                ->where('order_id', $item->order_id)
                ->where('status', '!=', 'hủy')
                ->where('status', '!=', 'chưa yêu cầu')
                ->where('item_type', $request->itemType)
                ->first();
            $orderItem->processing += $item->quantity;
            $orderItem->status = 'đang xử lý';
            $orderItem->save();
            $item->status = 'chờ cung ứng';
            $item->save();
            $item = Kitchen::find($id);
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
            ])->findOrFail($item->order->id);
            if ($item->item_type == 1) {
                $itemName = $item->dish->name;
            } else {
                $itemName = $item->combo->name;
            }
            $tableId = Table::with('orders')->findOrFail($request->tableId);
            $order = Order::with('tables')->findOrFail($item->order->id);
            $orderId = Table::findOrFail($request->tableId)
                ->orders
                ->where('status', 'pending')
                ->firstOrFail()
                ->id;
            $orderItem = OrderItem::where('order_id', $orderId)
                ->where(function ($query) {
                    $query->where('status', 'chờ xử lý')
                        ->orWhere('status', 'đang xử lý');
                })
                ->get();
            $notiBtn = false;
            foreach ($orderItem as $item) {
                if ($item->quantity > $item->informed) {
                    $notiBtn = true;
                    break;
                } else {
                    $notiBtn = false;
                }
            }
            $order = Order::with(['reservation', 'tables', 'customer'])->findOrFail($orderId);
            broadcast(new PosTableUpdatedWithNoti($orderItems, $tableId, $notiBtn, "Bàn $tableId->table_number đang được chế biến món $itemName"))->toOthers();
            $orderItem = OrderItem::with(['dish:id,name,image', 'combo:id,name,image'])
                ->where('order_id', $orderId)
                ->where('status', '!=', 'chưa yêu cầu')
                ->get();
            $orderItemArray = $orderItem->toArray();
            broadcast(new ItemUpdated($orderItemArray, "Món đang được nhà bếp chế biến!", $request->tableId))->toOthers();
        });
        return response()->json(['status' => 'success']);
    }
    public function doneAll($id, Request $request)
    {
        DB::transaction(function () use ($id, $request) {
            $item = Kitchen::find($id);
            $item->status = 'hoàn thành';
            $orderItem = $item->order->orderItems()
                ->where('item_id', $item->item_id);
            if ($item->item_type == 2) {
                $orderItem = $orderItem->where('item_type', $request->item_type);
            }
            $orderItem = $orderItem->where('status', '!=', 'hủy')
                ->where('status', '!=', 'chưa yêu cầu')
                ->first();
            if ($orderItem) {
                $orderItem->completed += $item->quantity;
                if ($orderItem->completed == $orderItem->quantity) {
                    $orderItem->status = 'hoàn thành';
                }
                $orderItem->save();
            }
            $item->save();
            $item = Kitchen::find($id);
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
            ])->findOrFail($item->order->id);
            if ($item->item_type == 1) {
                $itemName = $item->dish->name;
            } else {
                $itemName = $item->combo->name;
            }
            $tableId = Table::with('orders')->findOrFail($request->tableId);
            $order = Order::with('tables')->findOrFail($item->order->id);
            $orderId = Table::findOrFail($request->tableId)
                ->orders
                ->where('status', 'pending')
                ->firstOrFail()
                ->id;
            $orderItem = OrderItem::where('order_id', $orderId)
                ->where(function ($query) {
                    $query->where('status', 'chờ xử lý')
                        ->orWhere('status', 'đang xử lý');
                })
                ->get();
            $notiBtn = false;
            foreach ($orderItem as $item) {
                if ($item->quantity > $item->informed) {
                    $notiBtn = true;
                    break;
                } else {
                    $notiBtn = false;
                }
            }
            $order = Order::with(['reservation', 'tables', 'customer'])->findOrFail($orderId);
            broadcast(new PosTableUpdatedWithNoti($orderItems, $tableId, $notiBtn, "Bàn $tableId->table_number đã được cung ứng món $itemName"))->toOthers();
            $orderItem = OrderItem::with(['dish:id,name,image', 'combo:id,name,image'])
                ->where('order_id', $orderId)
                ->where('status', '!=', 'chưa yêu cầu')
                ->get();
            $orderItemArray = $orderItem->toArray();
            broadcast(new ItemUpdated($orderItemArray, 'Món đã được nhà bếp hoàn thành!', $request->tableId))->toOthers();
        });
        return response()->json(['status' => 'success']);
    }
    public function delete($id)
    {
        DB::transaction(function () use ($id) {
            $item = Kitchen::find($id);
            $item->delete();
        });
        return response()->json(['status' => 'success']);
    }
}
