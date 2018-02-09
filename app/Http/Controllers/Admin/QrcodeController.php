<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QrcodeController extends Controller
{

    /**
     * Display a listing of the qrcodes.
     *
     * @return \Illuminate\Http\Response
    */
    public function index()
    {
        return view('backend.qrcodes.index');
    }
}