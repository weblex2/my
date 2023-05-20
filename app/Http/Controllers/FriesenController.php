<?php

namespace App\Http\Controllers;

use App\Models\Friesen;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FriesenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('friese.index');
    }

    public function getFriesen($plz=""){
        if ($plz=="") {
            $friesen  = Friesen::where('plz', '!=', '')->orderBy("plz")->get()->toArray();
        }
        else{
            $friesen  = Friesen::where('plz', '=', $plz)->orderBy("plz")->get()->toArray();
        }    
        $friesen = json_encode(['results' => $friesen]);
        #header('Content-Type: application/json');
        return response($friesen, 200)
                  ->header('Content-Type', 'text/plain');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
