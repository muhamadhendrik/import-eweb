<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Master\Product;
use App\Models\Master\Qrcode;
use App\Models\Transaction\Customer;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode as FacadesQrCode;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('back.dashboard');
    }
}
