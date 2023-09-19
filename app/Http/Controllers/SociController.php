<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Consegne;
use App\Models\Iscrizione;
use App\Models\Soci;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SociController extends Controller
{
    public function index()
    {
        /**
         * Visualizza lista soci
         *  Route::get('/list', 'index')->name('soci.index')->middleware('is_admin');
         */
        $anno = Carbon::now()->format('Y');

        if ((session('asc_desc') == null)) {
            session(['asc_desc' => 'ASC']);
        }

        if ((session('colOrd') == null)) {
            session(['colOrd' => 'cognome']);
        }

        if ((session('anno') == null)) {
            session(['anno' => 'tutti']);
        }

        $viewData = [];
        $viewData["title"] = "Lista soci - Anagrafica";

        Paginator::useBootstrap();
// -------------------------------------------------------------------------------------

        $viewData["anno"] = session('anno');
        $viewData["servizio"] = '';

// ----------------------- Visualizza tutti i soci---------------------------------------------------

        if (session('anno') == 'tutti' && session('cognome') == null) {
            $viewData["socis"] = Soci::leftJoin('iscriziones', 'socis.id', '=', 'iscriziones.socio_id')
                ->select('socis.id',
                    'socis.nome',
                    'socis.cognome',
                    'socis.indirizzo',
                    'socis.consegna',
                    'socis.cap',
                    'socis.localita',
                    'socis.comune',
                    'socis.sigla_provincia',
                    'socis.email',
                    'socis.pec',
                    'socis.codice_fiscale',
                    'socis.partita_iva',
                    'socis.telefono',
                    'socis.cellulare',
                    'socis.tipo_socio',
                    'socis.published',
                    'socis.created_at',
                    'socis.updated_at',
                    'iscriziones.' .($anno+1) . ' as a' . ($anno+1),
                    'iscriziones.' . $anno . ' as a' . $anno,
                    'iscriziones.' . ($anno - 1) . ' as a' . ($anno - 1),
                    'iscriziones.' . ($anno - 2) . ' as a' . ($anno - 2),
                )
                ->orderBy('socis.' . session('colOrd'), session('asc_desc'))
                ->paginate(session('pag'));

            session(['anno' => null]);

            $numsel = $viewData["socis"]->total();

// ------------------- Seleziona anno ------------------------------

        } elseif (session('anno') != 'tutti' && session('cognome') == null) {
            $viewData["socis"] = Soci::leftJoin('iscriziones', 'socis.id', '=', 'iscriziones.socio_id')
                ->select('socis.id',
                    'socis.nome',
                    'socis.cognome',
                    'socis.indirizzo',
                    'socis.consegna',
                    'socis.cap',
                    'socis.localita',
                    'socis.comune',
                    'socis.sigla_provincia',
                    'socis.email',
                    'socis.pec',
                    'socis.codice_fiscale',
                    'socis.partita_iva',
                    'socis.telefono',
                    'socis.cellulare',
                    'socis.tipo_socio',
                    'socis.published',
                    'socis.created_at',
                    'socis.updated_at',
                    'iscriziones.' .($anno+1) . ' as a' . ($anno+1),
                    'iscriziones.' . $anno . ' as a' . $anno,
                    'iscriziones.' . ($anno - 1) . ' as a' . ($anno - 1),
                    'iscriziones.' . ($anno - 2) . ' as a' . ($anno - 2),
                )
                ->orderBy('socis.' . session('colOrd'), session('asc_desc'))
                ->where('iscriziones.' . session('anno'), '=', session('anno'))
                ->paginate(session('pag'));

            // ------- conta totale selezionati --------------
            $numsel = $viewData["socis"]->total();

            // ---------------- selziona cognome -------------------
        } elseif (session('cognome') != null) {
            $viewData["socis"] = Soci::leftJoin('iscriziones', 'socis.id', '=', 'iscriziones.socio_id')
                ->select('socis.id',
                    'socis.nome',
                    'socis.cognome',
                    'socis.indirizzo',
                    'socis.consegna',
                    'socis.cap',
                    'socis.localita',
                    'socis.comune',
                    'socis.sigla_provincia',
                    'socis.email',
                    'socis.pec',
                    'socis.codice_fiscale',
                    'socis.partita_iva',
                    'socis.telefono',
                    'socis.cellulare',
                    'socis.tipo_socio',
                    'socis.published',
                    'socis.created_at',
                    'socis.updated_at',
                    'iscriziones.' .($anno+1) . ' as a' . ($anno+1),
                    'iscriziones.' . $anno . ' as a' . $anno,
                    'iscriziones.' . ($anno - 1) . ' as a' . ($anno - 1),
                    'iscriziones.' . ($anno - 2) . ' as a' . ($anno - 2),
                )
                ->orderBy('socis.' . session('colOrd'), session('asc_desc'))
                ->where('socis.cognome', '=', session('cognome'))
                ->paginate(session('pag'));

            session(['cognome' => null]);
            $numsel = $viewData["socis"]->total();
        }

        $viewData["servizio"] = $numsel;

        return view('soci.index')->with("viewData", $viewData);
    }

    public function index_old()
    {
        /**
         * Visualizza lista soci
         *  Route::get('/list', 'index')->name('soci.index')->middleware('is_admin');
         */
        if ((session('asc_desc') == null)) {
            session(['asc_desc' => 'ASC']);
        }

        if ((session('colOrd') == null)) {
            session(['colOrd' => 'cognome']);
        }

        if ((session('anno') == null)) {
            session(['anno' => 'tutti']);
        }

        $viewData = [];
        $viewData["title"] = "Lista soci - Anagrafica";

        Paginator::useBootstrap();
        // $viewData["socis"] = Soci::orderBy('cognome')->paginate(session('pag'));

// -------------------------------------------------------------------------------------
        /* per contare i record selezionati da anno */
        $viewData["socix"] = Soci::leftJoin('iscriziones', 'socis.id', '=', 'iscriziones.socio_id')
            ->paginate(session('pag'));

        if (session('anno') != 'tutti') {
            $viewData["socix"] = Soci::select('socis.id',
                'socis.nome',
                'iscriziones.anno as iscrizione_anno',
                'iscriziones.socio_id',
            )
                ->rightJoin('iscriziones', 'iscriziones.socio_id', '=', 'socis.id')
                ->where('iscriziones.anno', '=', session('anno'))
                ->get();
        } else {
            $viewData["socix"] = Soci::all();
        }

        $numsel = count($viewData["socix"]);
        $viewData["servizio"] = $numsel;
        $viewData["anno"] = session('anno');

// --------------------------------------------------------------------------

        if (session('anno') != 'tutti') {
            $viewData["socis"] = Soci::select('socis.id',
                'socis.nome',
                'socis.cognome',
                'socis.indirizzo',
                'socis.consegna',
                'socis.cap',
                'socis.localita',
                'socis.comune',
                'socis.sigla_provincia',
                'socis.email',
                'socis.pec',
                'socis.codice_fiscale',
                'socis.partita_iva',
                'socis.telefono',
                'socis.cellulare',
                'socis.tipo_socio',
                'socis.published',
                'socis.created_at',
                'socis.updated_at',

                'iscriziones.socio_id',
            )
                ->rightJoin('iscriziones', 'iscriziones.socio_id', '=', 'socis.id')
                ->orderBy('socis.' . session('colOrd'), session('asc_desc'))
                ->where('iscriziones.anno', '=', session('anno'))
            //->where('socis.cognome', '=', session('cognome'))
            // ->orWhere('iscriziones.anno', '=', null)
                ->paginate(session('pag'));
            session(['anno' => null]);

        } elseif (session('cognome') != null) {
            $viewData["socis"] = Soci::select('socis.id',
                'socis.nome',
                'socis.cognome',
                'socis.indirizzo',
                'socis.consegna',
                'socis.cap',
                'socis.localita',
                'socis.comune',
                'socis.sigla_provincia',
                'socis.email',
                'socis.pec',
                'socis.codice_fiscale',
                'socis.partita_iva',
                'socis.telefono',
                'socis.cellulare',
                'socis.tipo_socio',
                'socis.published',
                'socis.created_at',
                'socis.updated_at',
                'iscriziones.socio_id',
            )
                ->rightJoin('iscriziones', 'iscriziones.socio_id', '=', 'socis.id')
                ->orderBy('socis.' . session('colOrd'), session('asc_desc'))
                ->where('socis.cognome', '=', session('cognome'))
            // ->orWhere('iscriziones.anno', '=', null)
                ->paginate(session('pag'));

            session(['cognome' => null]);
        } else {
            $viewData["socis"] = Soci::select('socis.id',
                'socis.nome',
                'socis.cognome',
                'socis.indirizzo',
                'socis.consegna',
                'socis.cap',
                'socis.localita',
                'socis.comune',
                'socis.sigla_provincia',
                'socis.email',
                'socis.pec',
                'socis.codice_fiscale',
                'socis.partita_iva',
                'socis.telefono',
                'socis.cellulare',
                'socis.tipo_socio',
                'socis.published',
                'socis.created_at',
                'socis.updated_at',
                'iscriziones.socio_id',
            )
                ->leftJoin('iscriziones', 'iscriziones.socio_id', '=', 'socis.id')
                ->orderBy('socis.' . session('colOrd'), session('asc_desc'))
            //->where('socis.cognome', '=', session('cognome'))
                ->paginate(session('pag'));

        }

        return view('soci.index')->with("viewData", $viewData);
    }

    public function pagine(Request $req)
    {

        /**
         * Route::POST('/paginazione', 'pagine');
         * setta il numero righe nelle tabelle per la paginazione
         * nota - se c'è un join non va ?
         * /paginazione usato in  consegne.blade -- soci.index.blade
         */

        session(['pag' => $req->rows]);
        return redirect('/list');
    }

    public function selAnno(Request $req)
    {

        /**
         * Route::POST('/selAnno', 'selAnno');
         * setta anno da filtrare
         *
         * /selAnnousato in  -- soci.index.blade
         */
        session(['anno' => 500]);
        session(['anno' => $req->anno]);
        return redirect('/list');
    }

    public function selCognome(Request $req)
    {

        /**
         * Route::POST('/selCognome', 'selCognome');
         * setta anno da filtrare
         *
         * /selCognome usato in  -- soci.index.blade
         */
        session(['cognome' => '']);
        session(['cognome' => $req->cognome]);

        return redirect('/list');
    }

    public function changeStatus($id)
    {
// TODO  non usato
        /**
         * Cambia lo stato Ablilitato/sospeso del socio
         *Route::get('changeStatus/{id}', 'changeStatus');
         * richiamato da ajax
         */
        $viewData = [];
        $soci = Soci::find($id);
        $stato = $soci->getPublished();

        if ($stato == "Si") {
            $soci->setPublished('No');
        } else {
            $soci->setPublished('Si');
        }

        $soci->save();

        $id = '';
        $viewData["title"] = "Soci";
        Paginator::useBootstrap();
        $viewData["socis"] = Soci::orderBy('cognome')->paginate(session('pag'));

        return redirect('/list');
    }

    public function indexOrd($ord)
    {
        /**
         * Crea ordinamento colonna passata in $ord
         * Route::get('/list/{col}', 'indexOrd')->middleware('is_admin');
         */

        session(['colOrd' => $ord]);
        if (session('ord') == 1) {
            session(['ord' => 2]);
        } else {
            session(['ord' => 1]);
        }

        $viewData = [];
        $viewData["title"] = "Soci";

        Paginator::useBootstrap();
        if (session('ord') == 1) {
            //$viewData["socis"] = Soci::orderBy($ord, 'DESC')->paginate(session('pag'));
            session(['asc_desc' => 'DESC']);
        } else {
            // $viewData["socis"] = Soci::orderBy($ord, 'ASC')->paginate(session('pag'));
            session(['asc_desc' => 'ASC']);
        }

        $viewData["servizio"] = '';
        $viewData["anno"] = '';

        return redirect('/list');
    }

    public function indexFiltro(Request $req)
    {

        /**
         * Filtra soci per anno join con iscrizione
         *  Route::post('filtro', 'indexFiltro');
         * usato in filtroAanno.blade
         *
         */

        $anno = $req->anno;

        $viewData = [];
        $viewData["title"] = "Filtro anno";
        
        $viewData["soci"] = Soci::join('iscriziones', 'iscriziones.socio_id', '=', 'socis.id')
            ->where('iscriziones.anno', '=', $anno)
            ->orderBy('socis.cognome', 'DESC')
            ->get(['iscriziones.id as idi', 'iscriziones.anno', 'iscriziones.socio_id', 'socis.*']);

        return view('soci.indexFiltro')->with("viewData", $viewData);
    }

    public function formAdd()
    {

        /**
         * crea la form per aggiungere anno
         *  Route::view('formAdd', 'soci.formAdd');
         * *
         * usato in indexFiltro.blade -- index.blade --
         */
        $viewData = [];
        $viewData["title"] = "Admin Page - Soci -";
        $viewData["consegnes"] = Consegne::all();
        return view('soci.addSoci')->with("viewData", $viewData);
    }

    public function salvasocio(Request $request)
    {

        /**
         * Route::POST('add', 'salvasocio');
         * // ok salva nuovo socio nel database
         * usato in formAdd.blade
         */
        Soci::validate($request);
        $viewData["consegnes"] = Consegne::all();

        $newsoci = new Soci();
        $newsoci->setNome($request->input('nome'));
        $newsoci->setCognome($request->input('cognome'));
        $newsoci->setIndirizzo($request->input('indirizzo'));
        $newsoci->setConsegna($request->get('consegna'));
        $newsoci->setTipo_socio($request->get('tipo_socio'));

        $newsoci->setCap($request->input('cap'));
        $newsoci->setLocalita($request->input('localita'));
        $newsoci->setComune($request->input('comune'));
        $newsoci->setSigla_provincia($request->input('sigla_provincia'));
        $newsoci->setEmail($request->input('email'));
        $newsoci->setPec($request->input('pec'));
        $newsoci->setCodice_fiscale($request->input('codice_fiscale'));
        $newsoci->setPartita_iva($request->input('partita_iva'));
        $newsoci->setTelefono($request->input('telefono'));
        $newsoci->setCellulare($request->input('cellulare'));
        $newsoci->setPublished($request->input('published'));
        $newsoci->setDescription($request->input('description'));

        $newsoci->save();

        return view('soci.formAdd', ['name' => 'James']);

    }

    public function cancellaSocio($id)
    {
        /**
         * Route::delete('delete/{id}', 'cancellaSocio');
         * // ok Cancella socio dal database
         *
         */
        soci::destroy($id);
        return redirect('/list');
    }

    public function editSocio($id)
    {

        $viewData = [];
        // da helpers
        $viewData = select_sociRightjoin_singolo($id);
        $viewData["title"] = "Edit socio ";
        $viewData["consegnes"] = Consegne::all();

        return view('soci.edit')->with("viewData", $viewData);
    }

    public function update(Request $request)
    {

        /**
         *  Route::post('editSocio', 'update');
         * // ok Aggiorna Socio
         * usato in soci.edit.blade
         *
         *
         */


         $anno = Carbon::now()->format('Y');
         $id = $request->id;
 
         $viewData = [];
         $viewData["title"] = "iscr ";
         $viewData["subtitle"] = "Iscrizioni";
         $viewData["anno"] = $anno;
 
     

        Soci::validate($request);
        $viewData["title"] = "- soci - ";
        $soci = Soci::find($id);
  
        $soci->setNome($request->input('nome'));
        $soci->setCognome($request->input('cognome'));
        $soci->setIndirizzo($request->input('indirizzo'));
        $soci->setConsegna($request->get('consegna'));
        $soci->setTipo_socio($request->get('tipo_socio'));
        $soci->setCap($request->input('cap'));
        $soci->setLocalita($request->input('localita'));
        $soci->setComune($request->input('comune'));
        $soci->setSigla_provincia($request->input('sigla_provincia'));
        $soci->setEmail($request->input('email'));
        $soci->setPec($request->input('pec'));
        $soci->setCodice_fiscale($request->input('codice_fiscale'));
        $soci->setPartita_iva($request->input('partita_iva'));
        $soci->setTelefono($request->input('telefono'));
        $soci->setCellulare($request->input('cellulare'));
        $soci->setPublished($request->get('published'));
        $soci->setDescription($request->input('description'));
// salva soci
        $soci->save();

        Iscrizione::validate($request);
        $idi = select_sociRightjoin_singolo($id);
        $id_iscrizioni = $idi['socis'][0]->socio_id;

       /* $iscrizioni = Iscrizione::find($id_iscrizioni);
        $iscrizioni[$anno] = $request[$anno+1];
        $iscrizioni[$anno] = $request[$anno];
        $iscrizioni[$anno - 1] = $request[$anno - 1];
        $iscrizioni[$anno - 2] = $request[$anno - 2];

// salva
        $iscrizioni->save();
*/

        return redirect('/list');
     
    }

    public function singolo(Request $req)
    {

        /**
         *
         * Route::get('singolo/{id}', 'singolo');
         *  // ok Visualizza dati singolo socio
         * richiamato da click sul cognome da lista soci
         *
         */

        $id = $req->id;

        $viewData = [];
        $viewData["title"] = "Socio ";
        $viewData["subtitle"] = "Singolo";

        // da helpers
        $viewData = select_sociRightjoin_singolo($id);

        return view('soci.singolo')->with("viewData", $viewData);
    }



    public function sociCancella(Request $requ)
    {

        // Recupera gli 'id' salvati nel database tabella 'servizios'
        // inseriti dal Controller ServizioController
        //public function salvaSelChck()

        $datis = DB::table('servizios')->where('nome', 'check')->first();
        $dt = explode(',', $datis->dati);

        $sheet1Data = Soci::find($dt);
        // Cancella i soci
        foreach ($sheet1Data as $key => $socio) {
            soci::destroy($socio->id);
        }

        return redirect('/list');
    }

}
