<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Traits\TraitCRUD;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    public function __construct()
    {
        // Gán middleware cho các phương thức
        $this->middleware('permission:Xem thanh toán', ['only' => ['index']]);
        $this->middleware('permission:Tạo mới thanh toán', ['only' => ['create']]);
        $this->middleware('permission:Sửa thanh toán', ['only' => ['edit']]);
        $this->middleware('permission:Xóa thanh toán', ['only' => ['destroy']]);
    }

    use TraitCRUD;

    protected $model = Payment::class;
    protected $viewPath = 'admin.payment';
    protected $routePath = 'admin.payment';

    public function index()
    {
        $title = 'Phương Thức Thanh Toán';
        $payments = Payment::all();
        $payments = Payment::latest()->paginate(10);

        return view('admin.payment.index', compact('payments', 'title'));
    }

    public function create()
    {
        return view('admin.payment.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reservation_id' => 'nullable|integer',
            'bill_id' => 'nullable|integer',
            'transaction_id' => 'nullable|integer',
            'refund_amount' => 'nullable|numeric',
            'amount' => 'required|numeric',
            'transaction_status' => 'required|in:pending,completed,failed',
            'payment_method' => 'required|in:cash,credit_card,online',
            'status' => 'required|in:Completed,Pending,Failed',
        ]);

        Payment::create($validated);
        return redirect()->route('admin.payment.index')->with('success', 'Payment created successfully');
    }

    public function show(Payment $payment)
    {
        $title = 'Chi Tiết Phương Thức Thanh Toán';
        return view('admin.payment.detail', compact('payment','title'));
    }

    public function edit(Payment $payment)
    {
        $title = 'Chỉnh Sửa Phương Thức Thanh Toán';
        return view('admin.payment.edit', compact('payment','title'));
    }

    public function update(Request $request, Payment $payment)
    {
        // Xác thực dữ liệu cơ bản
        $validated = $request->validate([
            'payment_method' => 'required|in:Cash,Credit Card,Online',
            'transaction_status' => 'required|in:pending,completed,failed',
            'status' => 'required|in:Completed,Pending,Failed',
        ]);

        // Lấy trạng thái từ request
        $transactionStatus = $validated['transaction_status'];
        $finalStatus = $validated['status'];

        // Định nghĩa các quy tắc hợp lệ
        $validTransitions = [
            'pending' => 'Pending',   // transaction_status = pending -> status = Pending
            'completed' => 'Completed', // transaction_status = completed -> status = Completed
            'failed' => 'Failed',    // transaction_status = failed -> status = Failed
        ];

        // Kiểm tra nếu trạng thái không hợp lệ
        if (isset($validTransitions[$transactionStatus]) && $validTransitions[$transactionStatus] !== $finalStatus) {
            return redirect()->route('admin.payment.edit', $payment->id)
                ->with('error', "Không thể cập nhật trạng thái. 'transaction_status' là '$transactionStatus' nhưng 'status' được chọn là '$finalStatus'.");
        }

        // Cập nhật dữ liệu
        $payment->update($validated);

        return redirect()->route('admin.payment.index')->with('success', 'Cập nhật thanh toán thành công.');
    }


    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);

        if ($payment->transaction_status === 'pending') {
            return redirect()->route($this->routePath . '.index')->with('error', 'Không thể xóa  thanh toán khi đang trong trạng thái chờ xử lý. Vui lòng thực hiện thao tác khác.');
        }

        // Xóa mềm bản ghi
        $payment->delete();

        return redirect()->route($this->routePath . '.index')->with('success', 'Thanh toán đã được xóa thành công!');
    }


    public function trash()
    {
        $title = 'Khôi Phục Danh Sách Thanh Toán';
        $payments = Payment::onlyTrashed()->paginate(10); // Lấy tất cả các thanh toán đã bị xóa mềm
        return view($this->viewPath . '.trash', compact('payments', 'title'));
    }


    public function restore($id)
    {
        $payment = Payment::withTrashed()->findOrFail($id);
        $payment->restore();

        return redirect()->route($this->routePath . '.trash')->with('success', 'Thanh toán đã được khôi phục thành công!');
    }

    public function forceDelete($id)
    {
        $payment = Payment::withTrashed()->findOrFail($id);
        $payment->forceDelete(); // Xóa vĩnh viễn bản ghi

        return redirect()->route($this->routePath . '.trash')->with('success', 'Thanh toán đã được xóa vĩnh viễn!');
    }
}
