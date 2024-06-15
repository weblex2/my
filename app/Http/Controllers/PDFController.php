<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PDFGenerate;

class PDFGenerateController extends Controller
{
    public function create()
    {
        return view('form');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'company_name' => 'required|max:255',
            'department_name' => 'required|max:255',
            'team_lead_name' => 'required|max:255',
        ]);
        PDFGenerate::create($validatedData);
   
        return redirect('/pdfgenerate')->with('success', 'Your PDF Data is successfully saved');
    }
}