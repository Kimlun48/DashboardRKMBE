<?php

namespace App\Http\Controllers\Api\Inbound;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inbound\ReturnInbound;
use App\Http\Resources\Inbound\ReturnResource;

class ReceiptReturnController extends Controller
{
    protected $data;

    public function __construct()
    {
        $this->data = ReturnInbound::getReturnInbound();
    }

    public function index()
    {
         return new ReturnResource(true, 'List Data Return', $this->data);
    }

    public function late()
    {
        // $late = $this->data->where('Deadline', '>', 0);
        // return new LateItrInResource(true, 'List Data ITRIN', $late);
    }

    public function onTime()
    {
        // $ontime = $this->data->where('Deadline', '=', 0);
        // return new OntimeItrInResource(true, 'List Data ITRIN', $ontime);
    }

    public function getStatistic()
    {
        $late = $this->data->where('late', '>', 0)->count();
        $ontime = $this->data->where('late', '=', 0)->count();
        $total = $late + $ontime;

        $totalQTYLate = $this->data->where('late', '>', 0)->sum('open_qty');
        $totalQTYOntime = $this->data->where('late', '=', 0)->sum('open_qty');

        $totalDoclate = $this->data
        ->where('late', '>', 0)  // Filter data dengan Deadline > 0
        ->groupBy('ITEM_DESC')      // Kelompokkan berdasarkan receipt_id
        ->count();                   // Hitung jumlah distinct receipt_id

        $totalDocOntime = $this->data
        ->where('late', '=', 0)  // Filter data dengan Deadline > 0
        ->groupBy('ITEM_DESC')      // Kelompokkan berdasarkan receipt_id
        ->count();     
    
    // dd($totalDoclate);

        return response()->json([
            'success' => true,
            'message' => 'Statistik Data RETURN',
            'data' => [
                'late' => $late,
                'ontime' => $ontime,
                'total' => $total,
                'total_QTY_late' => $totalQTYLate,
                'total_QTY_ontime' => $totalQTYOntime,
                'Total_Doc_late' => $totalDoclate,
                'Total_Doc_Ontime' => $totalDocOntime,
            ],
        ]);
    }
}
