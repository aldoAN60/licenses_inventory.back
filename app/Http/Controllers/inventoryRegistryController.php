<?php

namespace App\Http\Controllers;

use App\Models\costCenter;
use App\Models\inventoryRegistry;
use App\Models\license;
use App\Models\subArea;
use App\Models\User;
use App\Models\viewRegistry;
use App\Models\viewSubArea;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class inventoryRegistryController extends Controller
{
/**
 * Obtiene estadísticas sobre los registros en la base de datos.
 *
 * @return \Illuminate\Http\JsonResponse
 */
public function getTotalRegistry()
{
    // Obtiene el total de registros en la tabla 'inventoryRegistry'.
    $totalRegistry = inventoryRegistry::count();

    // Obtiene el total de registros con estado 'active' en la tabla 'inventoryRegistry'.
    $totalActive = inventoryRegistry::where('license_status', 'active')->count();

    // Calcula el total de registros expirados restando el total de registros activos al total de registros.
    $totalExpired = $totalRegistry - $totalActive;

    // Prepara un arreglo asociativo con las estadísticas.
    $allcounts = [
        'totalRegistry' => $totalRegistry,
        'totalActive' => $totalActive,
        'totalExpired' => $totalExpired
    ];

    // Devuelve las estadísticas como una respuesta JSON.
    return response()->json($allcounts);
}

/**
 * Obtiene todos los registros de la vista 'viewRegistry'.
 *
 * @return \Illuminate\Http\JsonResponse
 */
public function registry()
{
    // Obtiene todos los registros de la vista 'viewRegistry'.
    $registry = viewRegistry::all();

    // Devuelve los registros como una respuesta JSON.
    return response()->json($registry);
}

/**
 * Actualiza un registro o crea uno nuevo si la licencia no existe.
 *
 * @param \Illuminate\Http\Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function updateRegistry(Request $request)
{
    // Obtiene los datos del formulario de solicitud.
    $registry = $request->all();

    // Verifica si la licencia ya existe en la tabla 'License'.
    $licenseCheck = License::whereIn('license', [$registry['license']])->first();

    if (!$licenseCheck) {
        // Si la licencia no existe, crea un nuevo registro en la tabla 'License'.
        $licenseRegistry = new License([
            'license' => $registry['license']
        ]);
        $licenseRegistry->save();

        // Llama a la función 'getIDS' para procesar el registro y obtener información adicional.
        $this->getIDS($registry);

        // Devuelve una respuesta JSON indicando una actualización exitosa.
        return response()->json(['response' => 'Actualización exitosa']);
    } else {
        // Si la licencia ya existe, llama a la función 'getIDS' para procesar el registro.
        $ids = $this->getID($registry);

        // Obtiene el registro específico en 'inventoryRegistry' que se va a actualizar.
        $registro = inventoryRegistry::where('id_IR', $registry['id_IR'])->first();

        // Actualiza los campos del registro con los IDs y otros datos del formulario.
        $registro->id_sub_area = $ids['id_sub_area'];
        $registro->id_user = $ids['id_user'];
        $registro->id_CC = $ids['id_CC'];
        $registro->id_license = $ids['id_license'];
        $registro->license_status = $registry['license_status'];
        $registro->license_expiration = $registry['license_expiration'];
        $registro->notes = $registry['notes'];
    
        // Guarda los cambios en el registro.
        $registro->save();
        // Devuelve una respuesta JSON indicando una actualización exitosa.
        return response()->json(['response' => 'Actualización exitosa']);
    }
}
public function validateData($registryData){

}
public function createRegistry(Request $request){

    $validator = Validator::make($request->all(), [
        'sub_area_name' => 'required',
        'CC_number' => 'required',
        'employee_number' => 'required',
        'employee_name' => 'required',
        'license' => 'required',
        'license_status' => 'required',
        'license_expiration' => 'required',
    ]);
    
    if ($validator->fails()) {
        // Aquí puedes manejar las reglas de validación que no se cumplan
        return "Datos no validados";
    }
    
    // Obtiene los datos del formulario de solicitud.
    $registry = $request->all();
    // Verifica si la licencia ya existe en la tabla 'License'.
    $licenseCheck = License::whereIn('license', [$registry['license']])->first();
    // Verifica si el usuario ya existe en la tabla 'users'.
    $userCheck = User::whereIn('employee_name', [$registry['employee_name']])->first();

    if(!$userCheck){
        $this->createUser($registry);
    }

    if (!$licenseCheck) {
        $licenseRegistry = new License([
            'license' => $registry['license']
        ]);
        $licenseRegistry->save();
    }
    // Obtiene el ID de la subárea en función del nombre de la subárea proporcionado.
    $ids = $this->getID($registry);

    $registro = new inventoryRegistry ([
        
        'id_sub_area' => $ids['id_sub_area'],
        'id_user' => $ids['id_user'],
        'id_CC' => $ids['id_CC'],
        'id_license' => $ids['id_license'],
        'license_status' => $registry['license_status'],
        'license_expiration' => $registry['license_expiration'],
        'notes' => $registry['notes']
    ]);
    $registro->save();
    return response()->json(['response' => 'Registro Exitoso']);
    
}
public function getID($dataRegistry){
        // Obtiene el ID de la subárea en función del nombre de la subárea proporcionado.
        $subAreaId = subArea::where('sub_area_name', $dataRegistry['sub_area_name'])->first();
        $subAreaId = $subAreaId['id_sub_area'];
    
        // Obtiene el ID del usuario en función del número de empleado proporcionado.
        $userId = User::where('employee_number', $dataRegistry['employee_number'])->first();
        $userId = $userId['id_user'];
    
        // Obtiene el ID del centro de costos en función del número de centro de costos proporcionado.
        $CC_id = costCenter::where('CC_number', $dataRegistry['CC_number'])->first();
        $CC_id = $CC_id['id_CC'];
    
        // Obtiene el ID de la licencia en función del nombre de la licencia proporcionado.
        $id_license = License::where('license', $dataRegistry['license'])->first();
        $id_license = $id_license['id_license'];
        return [
            'id_sub_area' => $subAreaId,
            'id_user' => $userId,
            'id_CC' => $CC_id,
            'id_license' => $id_license 
        ];
}
/**
 * Realiza operaciones para obtener e ingresar IDs relacionados y actualizar un registro en 'inventoryRegistry'.
 *
 * @param array $registry
 * @return void
 */
public function getIDS($registry){
    // Llama a la función 'updateUser' para realizar operaciones relacionadas con el usuario.
    $this->updateUser($registry);

    // Obtiene el ID de la subárea en función del nombre de la subárea proporcionado.
    $subAreaId = subArea::where('sub_area_name', $registry['sub_area_name'])->first();
    $subAreaId = $subAreaId['id_sub_area'];

    // Obtiene el ID del usuario en función del número de empleado proporcionado.
    $userId = User::where('employee_number', $registry['employee_number'])->first();
    $userId = $userId['id_user'];

    // Obtiene el ID del centro de costos en función del número de centro de costos proporcionado.
    $CC_id = costCenter::where('CC_number', $registry['CC_number'])->first();
    $CC_id = $CC_id['id_CC'];

    // Obtiene el ID de la licencia en función del nombre de la licencia proporcionado.
    $id_license = License::where('license', $registry['license'])->first();
    $id_license = $id_license['id_license'];

    

}

    /**
 * Actualiza la información de un usuario en la base de datos en función del número de empleado proporcionado.
 *
 * @param array $registry
 * @return void
 */
public function updateUser($registry)
{
    // Busca un usuario en la base de datos en función del número de empleado proporcionado.
    $user = User::where('employee_number', $registry['employee_number'])->first();

    // Actualiza el nombre del empleado con el valor proporcionado en el formulario.
    $user->employee_name = $registry['employee_name'];

    // Actualiza la dirección de correo electrónico del empleado con el valor proporcionado en el formulario.
    $user->email = $registry['email'];

    // Guarda los cambios en la información del usuario en la base de datos.
    $user->save();
}

public function createUser($registry){
    $user = new User([
        'employee_number'=>$registry['employee_number'],
        'employee_name' => $registry['employee_name'],
        'email' => $registry['email']
    ]);
    $user->save();
}
    
}
