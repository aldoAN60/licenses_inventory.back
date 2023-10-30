<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\area;
class areaController extends Controller
{
    public function getArea(){
        $area = area::all();
        return response()->json($area);
    }
}
