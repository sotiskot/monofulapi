<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\expenses;

class expensesController extends Controller
{

    // Find all expenses for chosen vessel and return as JSON
    public function getExpense($vessel_id){
        $expenses = DB::table('expenses')->select('day', 'cost')->where('vessel_id', $vessel_id)->get();

        return response()->json([$expenses]);
    }

    public function vesselExpense($vessel_id){
        $days = DB::table('vessels')->
            join('voyage', 'vessels.id', '=', 'voyage.vessel_id')->
            join('expenses', 'vessels.id', '=', 'expenses.vessel_id')->
                select('expenses.day', 'expenses.cost', 'voyage.id','voyage.start', 'voyage.end', 'voyage.revenues', 'voyage.expenses')->
                    orderBy('voyage.id')->where('vessels.id', $vessel_id)->get();
        
        $last_voyage = $days[0]->voyage.id;
        $count = 0;
        foreach($days as $day){
            
            if( $last_voyage != $day['voyage.id'] ){
                if($count != 0){
                    $voyages[$last_voyage] = [$last_voyage => ['average_cost' => $voyages[$last_voyage]/$count, 'total_cost' => $voyages[$last_voyage] ]];
                }
                $voyages[$day['voyage.id']] = 0;
                if( $day['voyage.start'] <= $day['expenses.day'] && $day['expenses.day'] <= $day['voyage.end'] ){
                    $voyages[$day['voyage.id']] += $day['expenses.cost'];
                }
            }else{
                if( $day['voyage.start'] <= $day['expenses.day'] && $day['expenses.day'] <= $day['voyage.end'] ){
                    $voyages[$last_voyage] += $day['expenses.cost'];
                }
            }

            $count ++;
            $last_voyage = $day['voyage.id'];
        }

        $ins_voyages= DB::table('voyages')->
                orderBy('voyage.id')->where('vessels.id', $vessel_id)->get();

        $total_voyages = [];
        foreach($ins_voyages as $ins_voyage){
            foreach($voyages as $voyage=>$value){
                if($ins_voyage['id'] == $voyage){
                    array_push($ins_voyage, ['average_expenses' => $value['average_cost']]);
                    array_push($ins_voyage, ['voyage_profit' => $value['total_cost']-$ins_voyage['expenses']]);
                }
            }
            array_push($total_voyages, $ins_voyage);
        }
    }
}
