<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\TraitCRUD;

class SettingController extends Controller
{

  use TraitCRUD;

  protected $model = 'App\Models\Setting';
  protected $viewPath = 'admin.settings';
  protected $routePath = 'admin.settings';

  public function index(){
    $title = 'Settings';
     return view('admin.accountSetting.index', compact('title', 'setting'));
  }
}
