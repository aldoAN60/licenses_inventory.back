<?php

namespace App\Http\Controllers;

use App\Models\license;
use Illuminate\Http\Request;

class licensesController extends Controller
{
    public function getTotalLicenses(){
        $totalLicenses = license::count();
        return response()->json($totalLicenses);
    }
}
