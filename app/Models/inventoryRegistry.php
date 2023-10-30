<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inventoryRegistry extends Model
{
    use HasFactory;
    protected $table = 'inventoryRegistry';
    protected $fillable = [
    'id_sub_area',
    'id_user',
    'id_CC',
    'id_license',
    'license_status',
    'license_expiration',
    'notes'];
    public $timestamps = false;
    protected $primaryKey = 'id_IR';
}
