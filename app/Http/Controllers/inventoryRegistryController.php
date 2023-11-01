<?php

namespace App\Http\Controllers;

use App\Models\costCenter;
use App\Models\inventoryRegistry;
use App\Models\license;
use App\Models\subArea;
use App\Models\User;
use App\Models\viewRegistry;
use App\Models\viewSubArea;
use Illuminate\Http\Request;

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
    
    public function updateRegistry(Request $request){
        $registry = $request->all();
        $licenseCheck = License::whereIn('license', [$registry['license']])->first();


        if(!$licenseCheck){
                $licenseRegistry = new license([
                    'license' => $registry['license']
                ]);
                $licenseRegistry->save();
            
            $this->getIDS($registry);
            return response()->json(['response' => 'Actualizacion exitosa']);
        }else{
            $this->getIDS($registry);
            return response()->json(['response' => 'Actualizacion exitosa']);
        }
        // return response()->json($registry);
    }
    public function getIDS($registry){
        $this->updateUser($registry);
        
        $subAreaId = subArea::where('sub_area_name', $registry['sub_area_name'])->first();
        $subAreaId = $subAreaId['id_sub_area'];
        $userId = User::where('employee_number', $registry['employee_number'])->first();
        $userId = $userId['id_user'];
        $CC_id = costCenter::where('CC_number', $registry['CC_number'])->first();
        $CC_id = $CC_id['id_CC'];
        $id_license = license::where('license', $registry['license'])->first();
        $id_license = $id_license['id_license'];


        $registro = inventoryRegistry::where('id_IR', $registry['id_IR'])->first();
        
        $registro->id_sub_area = $subAreaId;
        $registro->id_user = $userId;
        $registro->id_CC = $CC_id;
        $registro->id_license = $id_license;
        $registro->license_status = $registry['license_status'];
        $registro->license_expiration = $registry['license_expiration'];
        $registro->notes = $registry['notes'];
    
    $registro->save();

    }
    public function updateUser($registry){
        $user = User::where('employee_number', $registry['employee_number'])->first();
        
        $user->employee_name = $registry['employee_name'];
        $user->email = $registry['email'];
        $user->save();
    }
    
}
