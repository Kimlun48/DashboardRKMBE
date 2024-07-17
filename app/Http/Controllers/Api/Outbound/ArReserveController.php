<?php

namespace App\Http\Controllers\Api\Outbound;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Outbound\ArReserve;
use App\Http\Resources\Outbound\ArReserveResource;
use Illuminate\Support\Facades\Log;

class ArReserveController extends Controller
{
    protected $data;
    public function __construct()
    {
        $this -> data = ArReserve::getArReserve();
    }

    public function index ()
    {
        return new ArReserveResource(true, 'List Data ArReserve', $this->data);
    }

    public function late()
    {

    }

    public function onTime()
    {

    }

    public function getStatistic()
    {
        $late = $this->data->where('CONDITION', '=', 'LATE')->count();
        $today = $this->data->where('CONDITION', '=', 'Today')->count();
        $dDay = $this->data->where('CONDITION', '=', 'H-1')->count();

        //dd($late);
        
        $total_all = $late + $today + $dDay ;

        // $totalQTYLate = $this->data->where('late', '>', 0)->sum('Total_QTY');
        // $totalQTYOntime = $this->data->where('late', '=', 0)->sum('Total_QTY');

        $totalDoclate = $this->data
        ->where('CONDITION', '=', 'LATE')  // Filter data dengan Deadline > 0
        ->groupBy('DOCNUM')      // Kelompokkan berdasarkan receipt_id
        ->count();                   // Hitung jumlah distinct receipt_id

        $totalDocToday = $this->data
        ->where('CONDITION', '=', 'Today')  // Filter data dengan Deadline > 0
        ->groupBy('DOCNUM')      // Kelompokkan berdasarkan receipt_id
        ->count();  
        
        $totalDocdDay = $this->data
        ->where('CONDITION', '=', 'H-1')  // Filter data dengan Deadline > 0
        ->groupBy('DOCNUM')      // Kelompokkan berdasarkan receipt_id
        ->count(); 
        
        $total = $totalDoclate+$totalDocToday+$totalDocdDay;
    
    // dd($totalDoclate);

        return response()->json([
            'success' => true,
            'message' => 'Statistik Data ArReserve ',
            'data' => [
                'late' => $late,
                'today'=> $today,
                'dDay' => $dDay,

                'total' => $total,
                // 'total_QTY_late' => $totalQTYLate,
                // 'total_QTY_ontime' => $totalQTYOntime,
                'Total_Doc_late' => $totalDoclate,
                'Total_Doc_today' => $totalDocToday,
                'Total_Doc_dDay' => $totalDocdDay
            ],
        ]);
    }

}
