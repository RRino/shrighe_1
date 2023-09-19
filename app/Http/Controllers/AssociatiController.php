<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anagrafica;
use App\Models\Associati;
use App\Models\Ruoli;
use App\Models\Ruoli_spec;
use App\Models\Dateiscr;
use App\Models\Enumruolispec;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;


class AssociatiController extends Controller
{
    public function test(Request $request)
    {
        $viewData = [];
       // $viewData['associati'] = Associati::with("anagrafica")->get();
       //return Collegamenti::with("anagrafica")->get();
       //$viewData['ruoli'] = Ruoli::all();
       $viewData = [];
       $viewData['title'] = " associati";
      

       $viewData['associati'] = Associati::with(["anagrafica","ruoli","ruoli_specm","dateiscr"])->get();
       return $viewData;

        //return $viewData['associati'][0]->anagrafica->nome;
        //return Associati::with("anagrafica")->get();// vedi tutti
        // return Associati::find(1)->anagrafica()->get();
        //return Anagrafica::with("getAssociati")->get();// vedi tutti
    }

    public function index()
    {

        $viewData = [];
        $viewData['title'] = " associati";
       
      
        $viewData['associati'] = Associati::with(["anagrafica","ruoli","ruoli_spec","dateiscr_many"])->get();
 

        
       // return view('associati.index', compact('associatis', 'ruoli'));
        return view('associati.index')->with("viewData", $viewData);
    }

    public function formAddassociati()
    {

        $annox = Carbon::now()->format('Y');

        $viewData = [];
        $viewData["title"] = "Aggiunge Associato";
        $anno = 2000;
        $anni = 2000;
        
        while ($anno < $annox+2) {
            $anni = $anni.','.$anno+1;
            $anno++;
        }
        
        $viewData["dataiscr"] = $anni;
        $viewData["anagrafica"] = Anagrafica::all();
        $viewData["ruoli"] = Ruoli::all();
        $viewData["ruoli_spec"] = Ruoli_spec::all();
        $viewData["enumruolispec"] = Enumruolispec::all();
        $viewData['associati'] = Associati::with(["anagrafica","ruoli","ruoli_spec","dateiscr_many"])->get();
     
        return view('associati.formAddAssociati')->with("viewData", $viewData);
    }

    public function addAssociati(Request $request)
    {

        $id = $request->id;

        $viewData = [];
        $associati = new Associati;
        $associati->ruolo = $request->input('ruolo');
        $associati->anagrafica_id = $request->input('anagrafica');
        $datis = Associati::where('anagrafica_id',$associati->anagrafica_id)->get();
        
        if (!isset($datis->anagrafica_id)) {
            $associati->save();
        }

        return redirect('/associati');

    }
}
