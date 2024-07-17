<?php

namespace App\Models\Outbound;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ArReserve extends Model
{
    use HasFactory;
    protected $connection = 'DB_ILS';

    protected $table = 'LOCATION_INVENTORY';

    public static function getArReserve(){
        $result = DB::connection ('DB_ILS')->select('EXEC DashboardV2_AR_Reserve');
        $collection = collect($result);
        //->sortByDesc('TYPE'); 
      //  $sorted = $collection->sortByDesc('late');   
        return $collection;        
    }
}
