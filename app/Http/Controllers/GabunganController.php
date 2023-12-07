<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class GabunganController extends Controller
{

    public function index()
    {
        $joins = DB::select('SELECT mobils.merk as merk, pemilik.ktp_pemilik as ktp_pemilik, pemilik.nama as nama FROM mobils JOIN pemilik ON mobils.ktp_pemilik = pemilik.ktp_pemilik');
        // $joins = DB::table('mobils')
        //     ->join('pemilik', 'mobils.ktp_pemilik', '=', 'pemilik.ktp_pemilik')
        //     ->select('mobils.merk as merk', 'pemilik.ktp_pemilik as ktp_pemilik', 'pemilik.nama as nama')
        //     ->paginate(6);

        return view('gabungan.index',compact('joins')) ->with('i', (request()->input('page', 1)-1)*6);
    }
}
