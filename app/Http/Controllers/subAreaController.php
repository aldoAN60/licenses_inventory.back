<?php

namespace App\Http\Controllers;


use App\Models\viewSubArea;
use Illuminate\Http\Request;

class subAreaController extends Controller
{
    public function getSubArea(){
        $subArea = viewSubArea::all();
        return response()->json($subArea);
    }
}
