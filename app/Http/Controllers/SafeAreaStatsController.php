<?php

namespace App\Http\Controllers;

use App\Models\SafeArea;
use Illuminate\Http\Request;

class SafeAreaStatsController extends Controller
{

    public function index()
    {
        $statistics = SafeArea::getStatistics();
        
        return view('admin.safe_area_stats', compact('statistics'));
    }

    public function getStats()
    {
        return response()->json(SafeArea::getStatistics());
    }

    
    public function export()
    {
        $records = SafeArea::select('id', 'voice_message', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($record) {
                return [
                    'id' => $record->id,
                    'type' => $record->type,
                    'type_thai' => $record->type_thai,
                    'voice_message' => $record->voice_message,
                    'created_at' => $record->created_at->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json([
            'data' => $records,
            'statistics' => SafeArea::getStatistics(),
            'export_date' => now()->format('Y-m-d H:i:s')
        ]);
    }
}