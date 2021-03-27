<?php

namespace App\Http\Controllers;


use App\Models\voyages;
use Illuminate\Http\Request;

class voyageController extends Controller
{

    // return all voyages as json
    public function getVoyages() {
        $voyages = DB::table('voyage')->get();

        return response()->json([$voyages]);
    }


    // insert new Voyage into Database
    public function newVoyage(Request $request) {
        $vessel_id = $request->vessel_id;
        $start = $request->start;
        $end = $request->end;
        $revenues = $request->revenues;
        $expenses = $request->expenses;


        try{
            DB::table('voyage')->insert([
                'vessel_id' => $vessel_id,
                'start' => $start,
                'end' => $end,
                'revenues' => $revenues,
                'expenses' => $expenses,
                'status' => 'submited'
            ]);
        }catch(Exception $e){
            return $e;
        }    
    }


    // update a Voyage
    public function editVoyage($voyage_id, Request $request) {
        $start = $request->start;
        $end = $request->end;
        $revenues = $request->revenues;
        $expenses = $request->expenses;
        $status = $request->status;

        try{
            DB::table('voyage')->where('voyage_id', $voyage_id)->update([
                'start' => $start,
                'end' => $end,
                'revenues' => $revenues,
                'expenses' => $expenses,
                'status' => $status
            ]);
        }catch(Exception $e){
            return $e;
        } 
    }
}
