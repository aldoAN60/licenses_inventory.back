<?php

namespace App\Http\Controllers;

use App\Models\viewCostCenter;
use Illuminate\Http\Request;

class CostCenterController extends Controller
{
    
    public function getCostCenter(){
        $costCenter = viewCostCenter::all();
        return response()->json($costCenter);
    }
}
