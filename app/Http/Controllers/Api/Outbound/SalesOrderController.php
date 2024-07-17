<?php

namespace App\Http\Controllers\Api\Outbound;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Outbound\SalesOrder;
use App\Http\Resources\Outbound\SalesOrderResource;

class SalesOrderController extends Controller
{
    protected $data;
    public function __construct()
    {
        $this->data = SalesOrder::getSalesOrder();
    }

    public function index ()
    {
        return new SalesOrderResource(true, 'List data Sales Order', $this->data);
    }

    public function getStatistic()
    {
        $late = $this->data->where('Kondisi', '=', 'Late')->count();
        $today = $this->data->where('Kondisi', '=', 'Today')->count();
        $dDay = $this->data->where('Kondisi', '=', 'H-1')->count();

        //dd($late);
        
        $total_all = $late + $today + $dDay ;

        // $totalQTYLate = $this->data->where('late', '>', 0)->sum('Total_QTY');
        // $totalQTYOntime = $this->data->where('late', '=', 0)->sum('Total_QTY');

        $totalDoclate = $this->data
        ->where('Kondisi', '=', 'Late')  // Filter data dengan Deadline > 0
        ->groupBy('DocNum')      // Kelompokkan berdasarkan receipt_id
        ->count();                   // Hitung jumlah distinct receipt_id

        $totalDocToday = $this->data
        ->where('Kondisi', '=', 'Today')  // Filter data dengan Deadline > 0
        ->groupBy('DocNum')      // Kelompokkan berdasarkan receipt_id
        ->count();  
        
        $totalDocdDay = $this->data
        ->where('Kondisi', '=', 'H-1')  // Filter data dengan Deadline > 0
        ->groupBy('DocNum')      // Kelompokkan berdasarkan receipt_id
        ->count();  

        $total = $totalDoclate+$totalDocToday+$totalDocdDay;
    
    // dd($totalDoclate);

        return response()->json([
            'success' => true,
            'message' => 'Statistik Data ITR Out ',
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
