<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Combo;
use App\Models\Dishes;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy các món ăn, phân trang với 10 món
        $dishes = Dishes::where('is_active', 1)->paginate(10);

        // Lấy các combo có trạng thái is_active = 1, phân trang với 10 combo
        $combos = Combo::where('is_active', 1)->paginate(10);

        // Trả về view với các dữ liệu món ăn và combo
        return view('client.index', compact('dishes', 'combos'));
    }
}
