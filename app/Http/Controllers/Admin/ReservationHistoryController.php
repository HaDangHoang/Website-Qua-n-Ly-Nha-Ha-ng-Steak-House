<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReservationHistory;
use App\Traits\TraitCRUD;
use Illuminate\Http\Request;

class ReservationHistoryController extends Controller
{


    public function __construct()
    {
        // Gán middleware cho các phương thức
        $this->middleware('permission:Xem lịch sử đặt bàn', ['only' => ['index']]);
        $this->middleware('permission:Tạo mới lịch sử đặt bàn', ['only' => ['create']]);
        $this->middleware('permission:Sửa lịch sử đặt bàn', ['only' => ['edit']]);
        $this->middleware('permission:Xóa lịch sử đặt bàn', ['only' => ['destroy']]);

    }

    use TraitCRUD;

    protected $model = ReservationHistory::class;
    protected $viewPath = 'admin.reservationHistory';
    protected $routePath = 'admin.reservationHistory';

    public function index()
{
    $title = ' Lịch Sử Đặt Bàn';
    $reservationHistories = ReservationHistory::all();
    return view('admin.reservation.reservationHistory.index', compact('reservationHistories', 'title'));
}



    public function create()
    {
        $title = 'Thêm Mới Lịch Sử Đặt Bàn';
        return view('admin.reservation.reservationHistory.create', compact('title'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reservation_id' => 'required|integer|exists:reservations,id',
            'change_time' => 'required|date',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'note' => 'nullable|string',
        ]);

        ReservationHistory::create($validated);

        return redirect()->route('admin.reservation.reservationHistory.index')->with('success', 'Lịch sử đặt chỗ đã được thêm thành công.');
    }

    public function show($id)
    {
        $title = 'Chi Tiết Lịch Sử Đặt Bàn';
        $reservationsHistory = ReservationHistory::findOrFail($id);
        return view('admin.reservation.reservationHistory.show', compact('reservationsHistory','title')); // Sửa tên biến
    }


    public function edit($id)
    {
        $title = 'Chỉnh Sửa Lịch Sử Đặt Bàn';
        $reservationsHistory = ReservationHistory::findOrFail($id);
        return view('admin.reservation.reservationHistory.edit', compact('reservationsHistory','title')); // Sửa tên biến
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'reservation_id' => 'required|integer|exists:reservations,id',
            'change_time' => 'required|date',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'note' => 'nullable|string',
        ]);

        $reservationsHistory = ReservationHistory::findOrFail($id);
        $reservationsHistory->update($validated);

        return redirect()->route('admin.reservation.reservationHistory.index')->with('success', 'Lịch sử đặt chỗ đã được cập nhật thành công.');
    }

    public function destroy($id)
    {
        $reservationsHistory = ReservationHistory::findOrFail($id);
        $reservationsHistory->delete();

        return redirect()->route('admin.reservation.reservationHistory.index')->with('success', 'Lịch sử đặt chỗ đã được xóa thành công.');
    }
}
