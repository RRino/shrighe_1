<?php

namespace App\Http\Controllers;

use App\Models\Anagrafica;
use App\Models\Associati;
use App\Models\Dateiscr;
use App\Models\Enumruolispec;
use App\Models\Ruoli;
use App\Models\Ruoli_spec;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

        //w
        $viewData['associati'] = Associati::with(["anagrafica", "ruoli", "ruoli_specm", "dateiscr"])->get();
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

        $viewData['associati'] = Associati::with(["anagrafica", "ruoli",  "ruoli_specm", "dateiscr_many"])->get();
        
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

        while ($anno < $annox + 2) {
            $anni = $anni . ',' . $anno + 1;
            $anno++;
        }

        $viewData["dataiscr"] = $anni;
        $viewData["anagrafica"] = Anagrafica::all();
        $viewData["ruoli"] = Ruoli::all();
        $viewData["ruoli_spec"] = Ruoli_spec::all();
        $viewData["enumruolispec"] = Enumruolispec::all();
        $viewData['associati'] = Associati::with(["anagrafica", "ruoli", "ruoli_specm", "dateiscr_many"])->get();

        return view('associati.formAddAssociati')->with("viewData", $viewData);
    }

    public function addAssociati(Request $request)
    {

        $id = $request->id;

        $viewData = [];
        $viewData["ruoli"] = Enumruolispec::all();
        
        $ruoli_speca = $request->anagrafica;
        foreach ($request->ruolo_spec as $rq) {
            $ruoli_spec = new Ruoli_spec;
            $ruoli_spec->associati_id = $ruoli_speca;
           // $ruoli_spec->ruoli_spec_id = $rq;
            $ruoli_spec->nome = $viewData["ruoli"][$rq]->nome;
            $ruoli_spec->save();
           $rspid =  $ruoli_spec->id;
        }

       
        
        foreach ($request->dataiscr as $rqd) {
            $dataiscr = new Dateiscr;
            $dataiscr->associati_id = $request->anagrafica;
            $dataiscr->nome = $rqd;
            $dataiscr->save();
        }

        $associati = new Associati;
        $associati->anagrafica_id = $request->anagrafica;
        $associati->ruoli_id = $request->ruolo;
        $associati->ruoli_spec_id = $request->anagrafica;
        $associati->dateiscr_id = $request->anagrafica;

        $datis = Associati::where('anagrafica_id', $request->anagrafica)->get();

        if ($datis->isEmpty()) {
            $associati->save();
        }

        return redirect('/associati');

    }
}
