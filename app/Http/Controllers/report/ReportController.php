<?php

namespace App\Http\Controllers\report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    
    public function balance()
    {
        return view('report.balance');
    }

    public function movementInv()
    {
        return view('report.movementInv');
    }

    public function costActivity()
    {
        return view('report.costActivity');
    }
}
