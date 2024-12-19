<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Traits\TraitCRUD;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{


    public function __construct()
    {
        // Gán middleware cho các phương thức
        $this->middleware('permission:Xem feedback', ['only' => ['index']]);
        $this->middleware('permission:Tạo mới feedback', ['only' => ['create']]);
        $this->middleware('permission:Sửa feedback', ['only' => ['edit']]);
        $this->middleware('permission:Xóa feedback', ['only' => ['destroy']]);
    }


    use TraitCRUD;

    protected $model = Feedback::class;
    protected $viewPath = 'admin.feedback';
    protected $routePath = 'admin.feedback';

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'created_at'); // Mặc định sắp xếp theo 'created_at'
        $direction = $request->input('direction', 'desc'); // Mặc định sắp xếp giảm dần

        $query = Feedback::query();

        // Tìm kiếm theo tên khách hàng
        if ($search) {
            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        // Áp dụng sắp xếp
        if (in_array($sort, ['reservation_id', 'rating', 'created_at']) && in_array($direction, ['asc', 'desc'])) {
            $query->orderBy($sort, $direction);
        }

        $feedbacks = $query->paginate(10);

        $title = 'Phản Hồi';
        return view('admin.feedback.index', compact('feedbacks', 'title'));
    }



    public function create()
    {

        return view('admin.feedback.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'reservation_id' => 'required|integer',
            'customer_id' => 'required|integer',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        Feedback::create($validated);

        return redirect()->route('admin.feedback.index')->with('success', 'Feedback added successfully.');
    }


    public function show(Feedback $feedback)
    {
        return view('admin.feedback.detail', compact('feedback'));
    }



    public function edit(Feedback $feedback)
    {
        return view('admin.feedback.edit', compact('feedback'));
    }

    public function update(Request $request, Feedback $feedback)
    {
        $validated = $request->validate([
            'reservation_id' => 'required|integer',
            'customer_id' => 'required|integer',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $feedback->update($validated);

        return redirect()->route('admin.feedback.index')->with('success', 'Feedback updated successfully.');
    }

    public function destroy(Feedback $feedback)
    {
        $feedback->delete();

        return redirect()->route('admin.feedback.index')->with('success', 'Feedback deleted successfully.');
    }
}
