<?php

namespace App\Http\Controllers\Client;


use App\Models\Combo;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Dishes;
use GuzzleHttp\Psr7\Request;

class MenuController extends Controller
{
    public function index()
    {
        // Lấy danh sách các danh mục, món ăn và combo
        $categories = Category::all();
        $dishes = Dishes::paginate(6);
        $combos = Combo::all();

        // Mảng ảnh tương ứng với từng category_id
        $categoryImages = [
            1 => 'efceca728a56f463c50e172c0104c5ab.jpg',  // Rượu Vang
            2 => 'bo-beefsteak.jpg',  // Beefsteak
            3 => 'e3d79194e9623b502aa5f7378455addc.jpg',  // Salad
        ];

        // Trả về view và truyền các biến cần thiết
        return view('client.menu', compact('categories', 'dishes', 'combos', 'categoryImages'));
    }


    // public function filterByCategory(Request $request)
    // {
    //     $categoryId = $request->input('category_id');

    //     // Filter based on the selected category
    //     if ($categoryId == 1) {
    //         $items = Dishes::where('category_id', 1)->get();
    //     } elseif ($categoryId == 2) {
    //         $items = Dishes::where('category_id', 2)->get();
    //     } elseif ($categoryId == 3) {
    //         $items = Dishes::where('category_id', 3)->get();
    //     } else {
    //         $items = Dishes::all(); // Show all items if no category is selected
    //     }

    //     return response()->json([
    //         'items' => $items
    //     ]);
    // }


}
