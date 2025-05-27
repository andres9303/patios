<?php

namespace App\Http\Controllers\report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    
    public function balance(Request $request)
    {
        $date = $request->date ?? now()->format('Y-m-d');
        
        return view('report.balance', compact('date'));
    }

    public function movementInv()
    {
        return view('report.movementInv');
    }

    public function costActivity()
    {
        return view('report.costActivity');
    }

    public function kardex(Request $request)
    {
        $start_date = $request->start_date ?? now()->format('Y-m-d');
        $end_date = $request->end_date ?? now()->format('Y-m-d');

        return view('report.kardex', compact('start_date', 'end_date'));
    }

    public function ticketHistory(Request $request)
    {
        return view('report.ticketHistory');
    }

    public function monthlyCost(Request $request)
    {
        return view('report.monthlyCost');
    }

    public function borrow(Request $request)
    {
        $date = $request->date ?? now()->format('Y-m-d'); 
        
        return view('report.borrow', compact('date'));
    }

    public function balanceProject(Request $request)
    {
        return view('report.balanceProject');
    }

    public function costDetail(Request $request)
    {
        return view('report.costDetail');
    }

    public function balanceSpace(Request $request)
    {
        return view('report.balanceSpace');
    }

    public function spaceDetail(Request $request)
    {
        return view('report.spaceDetail');
    }

    public function pendingActivity(Request $request)
    {
        return view('report.pendingActivity');
    }

    public function responsibleActivity(Request $request)
    {
        return view('report.responsibleActivity');
    }

    public function categoryEvent(Request $request)
    {
        return view('report.categoryEvent');
    }

    public function timetableEvent(Request $request)
    {
        return view('report.timetableEvent');
    }
}
