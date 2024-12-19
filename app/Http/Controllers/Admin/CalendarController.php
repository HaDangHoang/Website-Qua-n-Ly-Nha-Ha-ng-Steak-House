<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Table;
use App\Traits\TraitCRUD;

class CalendarController extends Controller
{

  use TraitCRUD;

  //protected $model = \App\Models\Calendar::class;
  protected $viewPath = 'admin.calendar';
  protected $routePath = 'admin.calendar';

  public function index(){
         $title = 'Lịch Đặt Bàn';
         // Lấy các bàn có trạng thái 'Available'
         $tables = Table::where('status', 'Available')->get();
         $reservations = Reservation::all(); // Lấy các đơn đặt bàn reservations

     return view('admin.calendar.index' , compact('title', 'tables', 'reservations'));
  }
}
