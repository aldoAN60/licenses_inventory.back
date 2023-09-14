<?php

namespace App\Http\Controllers;

use App\Models\inventoryRegistry;
use App\Models\viewRegistry;
use App\Models\viewSubArea;

class inventoryRegistryController extends Controller
{
    public function getTotalRegistry(){
         $totalRegistry = inventoryRegistry::count();
         $totalActive = inventoryRegistry::where('license_status', 'active')->count();
         $totalExpired = $totalRegistry - $totalActive;
       
         $allcounts = [
            'totalRegistry' => $totalRegistry,
            'totalActive' => $totalActive,
            'totalExpired' => $totalExpired
        ];
        

        return response()->json($allcounts);
    }
    public function registry(){
        $registry = viewRegistry::all();
        return response()->json($registry);
    }
}
