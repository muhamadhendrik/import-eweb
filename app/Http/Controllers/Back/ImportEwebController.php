<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Imports\OrderImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportEwebController extends Controller
{
    public function index()
    {
        return view('back.import-eweb.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pos_excel' => 'required|file|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new OrderImport, $request->file('pos_excel'));
            return back()->with('success', 'Data imported successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to import data: ' . $e->getMessage());
        }
    }
}
