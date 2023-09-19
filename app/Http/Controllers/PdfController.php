<?php

namespace App\Http\Controllers;

use App\Models\Iscrizione;
use App\Models\Param_bollettini;
use App\Models\Param_etichette;
use App\Models\Soci;
use Carbon\Carbon;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PdfController extends Controller
{

    public function PdfBollettini(Request $req)
    {

        /**
         * Route::get('bollettini/{tipo}', 'PdfBollettini');
         * //usato da bottone "Bollettini da chckbox" che chiama
         * AJAX poi da success in soci.index.blade.php
         *
         */

        $anno = $req->bollettini_anno;
        if ($anno == null) {
            $anno = Carbon::now()->format('Y');
        }

        // attenzione .... $req->tipo non si vede in $req si vede se fai '$tip = $req->tipo;' perche è passato da ajax
        // window.location.href = "/bollettini/1";
        $tip = $req->tipo;
        //$causale = $req->causale;
        $parametri_bollettini = Param_bollettini::all();
        $causale = $parametri_bollettini[0]->causale;
        $prezzo = $parametri_bollettini[0]->prezzo;
           
        /**
         * legge tabella database dove ajax ha memorizzato i check selezionati
         */
        if ($tip == 1) {
            //  $datis = Servizio::find(1);
            $datis = DB::table('servizios')->where('nome', 'check')->first();
            
            //$causale = $causale->dati . ' - anno: ' . (string) $anno;
            $dt = explode(',', $datis->dati);
            $data = Soci::find($dt);
        }

        /**
         * legge unione tra soci e iscrizione select iscrizione.anno
         */
        if ($tip == 3) {

            $data = Soci::leftJoin('iscriziones', 'socis.id', '=', 'iscriziones.socio_id')
                ->select('socis.id',
                    'socis.nome',
                    'socis.cognome',
                    'socis.indirizzo',
                    'socis.consegna',
                    'socis.cap',
                    'socis.localita',
                    'socis.comune',
                    'socis.sigla_provincia',

                    'iscriziones.' . $anno-1 . ' as a' . $anno-1,

                )
                ->orderBy('socis.cognome', 'ASC')
                ->where('iscriziones.' . $anno-1, '=', $anno-1)
                ->where('iscriziones.' . $anno, '=', 'No')
                ->get();

        }

        $pdf = new TCPDF;

        //$nbol2 = 1;//$data->count();

        $nbol = 1;
        $anno = date('Y');
        $inizio = '01-01-' . $anno;
        $costo = $prezzo; // dal database parm_bollettini

        // $causale = "ISCRIZIONE ASSOCIAZIONE PROGETTO 10 Righe APS 2023 piu 2 riviste";
        if (strlen($causale) < 3) {
            echo "Verifica  la causale nella tabella (#__gestionea_parametri)";
            exit;
        }

        // set document information

        $pdf::SetAuthor('10 Righe APS');
        $pdf::SetTitle('Bollettino');
        $pdf::SetSubject('Rinnovi');
        $pdf::SetKeywords('APS');

        // set header and footer fonts
        $pdf::setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf::setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        // set default monospaced font
        $pdf::SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        // set margins
        //$pdf::SetMargins(PDF_MARGIN_LEFT, 0, PDF_MARGIN_RIGHT);
        $pdf::SetMargins(0.0, 1.1, 0.0, 0.0);
        // set auto page breaks
        $pdf::SetAutoPageBreak(true, 1);
// $pdf::SetAutoPageBreak(true ,20);
        // set image scale factor
        $pdf::setImageScale(PDF_IMAGE_SCALE_RATIO);

        // ---------------------------------------------------------

        // set font
        $pdf::SetFont('times', '', 10);
        // add a page
        $pdf::AddPage('L', 'A4');
        //$pdf::Cell(0, 0, 'A4 LANDSCAPE', 1, 1, 'C');
        // set cell padding
        $pdf::setCellPaddings(1, 0, 0, 0);
        // set cell margins
        $pdf::setCellMargins(0, 0, 0, 0);
        // set color for background
        $pdf::SetFillColor(255, 255, 255);

        //$image_file = 'images/logo.png';
        //$html_logo = '<br><br><br><div ><img src="'.$image_file.'" width="25"></div>';
        //$html = $html.$html_logo;

        // Print text using writeHTMLCell()
        //$pdf::writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
        /// $pdf::writeHTML($html, true, false, true, false, '');

        $pdf::SetFillColor(240, 50, 20);
        //$nbol2--;
        $inc = 0;

        foreach ($data as $users) {

            $id = $users->id;
            $user = DB::table('socis')->where('id', $id)->select('id', 'nome', 'cognome')->get();
            $nome = $users->nome;
            $cognome = $users->cognome;
            $indirizzo = $users->indirizzo;
            $cap = $users->cap;
            $comune = $users->comune;
            $privincia = $users->sigla_provincia;
            $html = '';

            $htmlsegnotaglio = '<div>--</div>';
            $htmlprimarigas = '<div style="border-bottom: solid 1px #000;">CONTI CORRENTI POSTALI &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Ricevuta di versamento   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;                      Banco<b>Posta</b></div>';
            $htmlprimarigad = '<div style="border-bottom: solid 1px #000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; CONTI CORRENTI POSTALI &nbsp;&nbsp;&nbsp;&nbsp; Ricevuta di versamento  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   Banco<b>Posta</b></div>';
            $riga_vert = '<div style="border-left: solid 2px #000;height:500px;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp  </div>';

            $htmleuro = '<div style="font-size:24px;background-color:#000;color:#fff;text-align:center;">€</div>';
            $html4 = '<div style="font-size:12px;">sul C/C n.</div>';
            $htmlcc = '<div style="font-size:16px;font-weight:800;">36349785 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    </div>';
            $htmldie = '<div style="font-size:12px;font-weight:800;">di Euro </div>';
            $htmlcosto = '<div style="font-size:16px;font-weight:800;text-align:right;">' . $costo . ' </div>';
            $htmlvi = '<div style="font-size:16px;font-weight:800;text-align:right;">,</div>';
            $htmlze = '<div style="font-size:16px;font-weight:800;text-align:left;">00 </div>';

            $htmlsigla = '<div style="font-size:16px;font-weight:900;text-align:left;">TD &nbsp;&nbsp;&nbsp; 123 </div>';
            $htmlimp = '<div style="font-size:10px;font-weight:900;text-align:left;">IMPORTO IN LETTERE</div>';
            $htmlel = '<div style="font-size:14px;font-weight:900;text-align:left;border-bottom: solid 1px #000;">VENTI/00</div>';

            $htmlintest = '<div style="font-size:10px;font-weight:900;text-align:left;">INTESTATO A</div>';
            $htmlgru = '<div style="font-size:12px;font-weight:900;text-align:left;">&nbsp;&nbsp;ASSOCIAZIONE GRUPPO DI STUDI PROGETTO 10 RIGHE APS</div>';
            $htmlgru2 = '<div style="font-size:12px;font-weight:900;text-align:left;">&nbsp;&nbsp;</div>';
            $htmlcaus = '<div style="font-size:12px;font-weight:900;text-align:left;">CAUSALE</div>';
            $htmlquo = '<div style="font-size:12px;font-weight:900;text-align:left;">' . $causale . '</div>';
            $htmlquo2 = '<div style="font-size:10px;font-weight:900;text-align:left;">  &nbsp;&nbsp;</div>';
            $htmleseg = '<div style="font-size:10px;font-weight:900;text-align:left;">ESEGUITO DA</div>';

            $htmlnome = '<div style="font-size:14px;font-weight:900;text-align:left;">  &nbsp;&nbsp;' . $nome . '</div>';
            $htmlvuota = '<div style="font-size:14px;font-weight:900;text-align:left;">  &nbsp;&nbsp;' . $cognome . '</div>';

            $htmlvia = '<div style="font-size:14px;font-weight:900;text-align:left;">  Via-Piazza &nbsp;&nbsp;' . $indirizzo . '</div>';
            $htmlcap = '<div style="font-size:12px;font-weight:900;text-align:left;">  CAP &nbsp;&nbsp;' . $cap . '</div>';
            $htmlloca = '<div style="font-size:12px;font-weight:900;text-align:left;">  LOCALITA\'&nbsp;&nbsp;' . $comune . '</div>';

            $htmlviandes = '<div style="font-size:10px;font-weight:900;text-align:left;">  &nbsp;&nbsp;VIA - PIAZZA</div>';
            $htmlcapndes = '<div style="font-size:10px;font-weight:900;text-align:left;">  &nbsp;&nbsp;CAP</div>';
            $htmllocandes = '<div style="font-size:10px;font-weight:900;text-align:left;">  &nbsp;&nbsp;LOCALITA\'</div>';
            $htmlviandesval = '<div style="font-size:14px;font-weight:900;text-align:left;">  &nbsp;&nbsp;' . $indirizzo . '</div>';
            $htmlcapndesval = '<div style="font-size:14px;font-weight:900;text-align:left;">  &nbsp;&nbsp;' . $cap . '</div>';
            $htmllocandesval = '<div style="font-size:14px;font-weight:900;text-align:left;">  &nbsp;&nbsp;' . $comune . '</div>';
            $htmlbollino = '<div style="font-size:10px;font-weight:900;text-align:left;">  &nbsp;&nbsp;BOLLO DELL\'UFFICIO POSTALE</div>';
            $htmlnotefondo = '<div style="font-size:8px;font-weight:400;text-align:right;border-bottom: solid 1px #000;">  codice bancoposta &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;importo in euro &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; numero conto &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  tipo documento &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  </div>';
            $htmlcodicefondo = '<div style="font-size:18px;font-weight:900;text-align:right;">  &nbsp;&nbsp;123 ></div>';

            $taglio = ' <br><br>  ---- taglio ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------';
            $altri_pagamenti = '<div style="font-size:14px;font-weight:800;"><br><br><br><b>Caro socia/socio.</b><br>
Ti abbiamo spedito la rivista "al sas" n. 47 relativa al 1° semestre 2023 anche se non hai rinnovato l\'iscrizione perchè pensiamo ad una dimenticanza, nel caso che tu non voglia rinnovare l\'iscrizione non  effettuare il pagamento.<br>
<div style="font-size:14px;"><b>Come effettuare o rinnovare l’iscrizione</b><br>
Il rinnovo dell’iscrizione al Gruppo di studi “Progetto 10 righe” dà diritto a ricevere a domicilio i due  numeri della rivista semestrale di un anno con solo Euro 20,00.
<br><br>E’ possibile:<br>
1) utilizzare il bollettino stampato sopra per l\'ufficio postale<br>
2) effettuare un bonifico sul nostro conto presso EMILBANCA con codice IBAN: IT74 Z070 7237 10000000 0024 254 e con causale
  “iscrizione anno 2023 con due riviste”.<br>Per prima iscrizione aggiungere:
   nome, cognome, data di nascita, indirizzo, n.telefono, email(opzionale)<br>
3) versare la quota presso l’Ufficio Turistico “InfoSasso” - Piazza dei Martiri, via Porrettana 312, Sasso Marconi.
<brt> - Ufficio Informazioni Turistiche Sasso Marconi:
    051 6758409 - 334 8334945 Apertura: martedì – venerdì 9.30-13.00 e 15.00-19.00; sabato 09.30-13.00; lunedì, domenica e festivi chiuso.';

            $nperpagina = 1;
            $hbo = $inc * 100;
            //$nbol2--;
            if ($inc == $nperpagina) {
                $inc = 0;
                $hbo = 0;
                $pdf::AddPage('L', 'A4');
            }
            $inc++;
            if ($inc == 2) {
                $hbo = $hbo + 5;
                $pdf::writeHTMLCell(124, 4, 1, 1 + $hbo - 3, $htmlsegnotaglio, 0, 0, 0, true, '', true);
            }

            $pdf::writeHTMLCell(124, 4, 9, 2 + $hbo, $htmlprimarigas, 0, 0, 0, true, '', true);
            $pdf::writeHTMLCell(160, 4, 131, 2 + $hbo, $htmlprimarigad, 0, 0, 0, true, '', true);

            $pdf::writeHTMLCell(10, 4, 9, 8.2 + $hbo, $htmleuro, 0, 0, 0, true, '', true);
            $pdf::writeHTMLCell(20, 4, 19, 13 + $hbo, $html4, 0, 0, 0, true, '', true);
            $pdf::writeHTMLCell(33, 4, 35, 11 + $hbo, $htmlcc, 1, 0, 0, true, '', true);
            $pdf::writeHTMLCell(20, 4, 68, 13 + $hbo, $htmldie, 0, 0, 0, true, '', true);
            $pdf::writeHTMLCell(34, 4, 80, 11 + $hbo, $htmlcosto, 1, 0, 0, true, '', true);
//$pdf::Cell(3, 4, 105, 10, ',', 0, 0, 0, true, '', true);
            $pdf::writeHTMLCell(5, 4, 111, 12 + $hbo, $htmlvi, 0, 0, 0, true, '', true);
            $pdf::writeHTMLCell(10, 4, 117, 11 + $hbo, $htmlze, 1, 0, 0, true, '', true);

            $pdf::writeHTMLCell(10, 5, 138, 8.2 + $hbo, $htmleuro, 0, 0, 0, true, '', true);
            $pdf::writeHTMLCell(20, 5, 148, 12.9 + $hbo, $html4, 0, 0, 0, true, '', true);
            $pdf::writeHTMLCell(35, 5, 163, 11 + $hbo, $htmlcc, 1, 0, 0, true, '', true);

            $pdf::writeHTMLCell(34, 4, 240, 11 + $hbo, $htmlcosto, 1, 0, 0, true, '', true);
            $pdf::writeHTMLCell(5, 5, 271, 12 + $hbo, $htmlvi, 0, 0, 0, true, '', true);
            $pdf::writeHTMLCell(10, 4, 277, 11 + $hbo, $htmlze, 1, 0, 0, true, '', true);

// secoda riga
            $pdf::writeHTMLCell(268, 10, '', '', '', 0, 0, false, true, '');
            $pdf::writeHTMLCell(40, 5, 9, 19 + $hbo, $htmlimp, 0, 0, 0, true, '', true);
            $pdf::writeHTMLCell(87, 5, 40, 17 + $hbo, $htmlel, 0, 0, 0, true, '', true);
            $pdf::writeHTMLCell(40, 5, 140, 17.5 + $hbo, $htmlsigla, 0, 0, 0, true, '', true);
            $pdf::writeHTMLCell(40, 5, 165, 19 + $hbo, $htmlimp, 0, 0, 0, true, '', true);
            $pdf::writeHTMLCell(87, 5, 200, 17 + $hbo, $htmlel, 0, 0, 0, true, '', true);

// terza riga intestato a:
//$pdf::writeHTMLCell(268, 10, '', '', '', 0, 0, false, true, '');
            $pdf::writeHTMLCell(40, 4, 8, 22.5 + $hbo, $htmlintest, 0, 0, 0, true, '', true);
            $pdf::writeHTMLCell(40, 4, 140, 22.5 + $hbo, $htmlintest, 0, 0, 0, true, '', true);
            $pdf::writeHTMLCell(118, 4, 9, 26 + $hbo, $htmlgru, 1, 0, 0, true, '', true);
            $pdf::writeHTMLCell(148, 4, 140, 26 + $hbo, $htmlgru, 1, 0, 0, true, '', true);

            $pdf::writeHTMLCell(118, 4, 9, 30.5 + $hbo, $htmlgru2, 1, 0, 0, true, '', true);
            $pdf::writeHTMLCell(148, 4, 140, 30.5 + $hbo, $htmlgru2, 1, 0, 0, true, '', true);

//causale
            $pdf::writeHTMLCell(40, 4, 9, 36 + $hbo, $htmlcaus, 0, 0, 0, true, '', true);
            $pdf::writeHTMLCell(40, 4, 140, 36 + $hbo, $htmlcaus, 0, 0, 0, true, '', true);
            $pdf::writeHTMLCell(118, 4, 9, 40 + $hbo, $htmlquo, 1, 0, 0, true, '', true);
            $pdf::writeHTMLCell(148, 4, 140, 40 + $hbo, $htmlquo, 1, 0, 0, true, '', true);

            $pdf::writeHTMLCell(118, 4, 9, 44.5 + $hbo, $htmlquo2, 1, 0, 0, true, '', true);
            $pdf::writeHTMLCell(148, 4, 140, 44.5 + $hbo, $htmlquo2, 1, 0, 0, true, '', true);

//eseguito da
            $pdf::writeHTMLCell(40, 4, 8, 49.5 + $hbo, $htmleseg, 0, 0, 0, true, '', true);
            $pdf::writeHTMLCell(40, 4, 190, 49.5 + $hbo, $htmleseg, 0, 0, 0, true, '', true);

            $pdf::writeHTMLCell(65, 5, 9, 53 + $hbo, $htmlnome, 1, 0, 0, true, '', true);
            $pdf::writeHTMLCell(98, 5, 190, 53 + $hbo, $htmlnome, 1, 0, 0, true, '', true);

            $pdf::writeHTMLCell(65, 5, 9, 58 + $hbo, $htmlvuota, 1, 0, 0, true, '', true);
            $pdf::writeHTMLCell(98, 5, 190, 58 + $hbo, $htmlvuota, 1, 0, 0, true, '', true);

// via piazza cap localita SINISTRA
            $pdf::writeHTMLCell(65, 5, 9, 63 + $hbo, $htmlvia, 1, 0, 0, true, '', true);
            $pdf::writeHTMLCell(65, 5, 9, 68 + $hbo, $htmlcap, 1, 0, 0, true, '', true);
            $pdf::writeHTMLCell(65, 5, 9, 73 + $hbo, $htmlloca, 1, 0, 0, true, '', true);

// via piazza cap localita DESTRA
            $pdf::writeHTMLCell(65, 5, 187, 63 + $hbo, $htmlviandes, 0, 0, 0, true, '', true);
            $pdf::writeHTMLCell(98, 5, 190, 66 + $hbo, $htmlviandesval, 1, 0, 0, true, '', true);
            $pdf::writeHTMLCell(65, 5, 190, 71 + $hbo, $htmlcapndes, 0, 0, 0, true, '', true);
            $pdf::writeHTMLCell(65, 5, 235, 71 + $hbo, $htmllocandes, 0, 0, 0, true, '', true);

            $pdf::writeHTMLCell(35, 5, 190, 74.4 + $hbo, $htmlcapndesval, 1, 0, 0, true, '', true);
            $pdf::writeHTMLCell(50, 5, 238, 74.4 + $hbo, $htmllocandesval, 1, 0, 0, true, '', true);

//note a fondo
            $pdf::writeHTMLCell(50, 4, 85, 78 + $hbo, $htmlbollino, 0, 0, 0, true, '', true);
            $pdf::writeHTMLCell(50, 4, 138, 78 + $hbo, $htmlbollino, 0, 0, 0, true, '', true);
            $pdf::writeHTMLCell(289, 4, 5, 81 + $hbo, $htmlnotefondo, 0, 0, 0, true, '', true);
            $pdf::writeHTMLCell(285, 4, 5, 90 + $hbo, $htmlcodicefondo, 0, 0, 0, true, '', true);
            $pdf::writeHTMLCell(285, 4, 5, 90 + $hbo, $taglio, 0, 0, 0, true, '', true);
            $pdf::writeHTMLCell(285, 4, 5, 90 + $hbo, $altri_pagamenti, 0, 0, 0, true, '', true);

// $pdf::writeHTMLCell(289, 4, 5, 96+$hbo, $htmlvuota, 0, 0, 0, true, '', true);
            $pdf::writeHTMLCell(2, 4, 135, 5 + $hbo, $riga_vert, 0, 0, 0, true, '', true);
            $pdf::Ln(0);
//$pdf::AddPage();
        }
        $pdf::SetFillColor(255, 255, 255);
        $filename = 'Bollettino';

        $pdf::Output(public_path($filename), 'F');

        return response()->download(public_path($filename));

    }

    /***
     * Crea pdf etichette
     *
     *
     */
    public function PdfEtichette(Request $req)
    {

        // $etichetta_nome = $req->etichetta_nome;

        //  if ($etichetta_nome == 'Seleziona Tipo etichetta') {
        //     return redirect('/etichette_anno');
        // }
 
        $anno = $req->etichette_anno;
        $no_anno = $req->etichette_no_anno;
        // attenzione .... $req->tipo non si vede in $req si vede se fai '$tip = $req->tipo;' perche è passato da ajax
        // window.location.href = "/etichette/1";
        $tip = $req->tipo;

        /**
         * legge tabella database dove ajax ha memorizzato i check selezionati
         */
        if ($tip == 1) {
            //$datis = Servizio::find(1);
            $datis = DB::table('servizios')->where('nome', 'check')->first();
            $dt = explode(',', $datis->dati);
            $sheet1Data = Soci::find($dt);

        }

        if ($tip == 2) {
            // $sheet1Data = DB::table('socis')->where('socis.anno', $anno)->orderBy('id', 'DESC')->get();
            //  $sheet1Data = Soci::leftJoin('iscriziones', 'socis.id', '=', 'iscriziones.socio_id')
            //   ->where('iscriziones.anno', $anno)
            //   ->get();
            if($anno != $no_anno){// solo chi non ha pagato
            $sheet1Data = Soci::leftJoin('iscriziones', 'socis.id', '=', 'iscriziones.socio_id')
                ->select('socis.id',
                    'socis.nome',
                    'socis.cognome',
                    'socis.indirizzo',
                    'socis.consegna',
                    'socis.cap',
                    'socis.localita',
                    'socis.comune',
                    'socis.sigla_provincia',
                    'iscriziones.' . $anno . ' as a' . $anno,

                )
                ->orderBy('socis.cognome', 'ASC')
                ->where('iscriziones.' . $no_anno, '=', $no_anno)            
                ->whereNot('iscriziones.' . $anno, '=', $anno)             
                ->get();
            }else{// solo chi ha pagato
                $sheet1Data = Soci::leftJoin('iscriziones', 'socis.id', '=', 'iscriziones.socio_id')
                ->select('socis.id',
                    'socis.nome',
                    'socis.cognome',
                    'socis.indirizzo',
                    'socis.consegna',
                    'socis.cap',
                    'socis.localita',
                    'socis.comune',
                    'socis.sigla_provincia',
                    'iscriziones.' . $anno . ' as a' . $anno,

                )
                ->orderBy('socis.cognome', 'ASC')
                ->where('iscriziones.' . $anno, '=', $anno)                       
                ->get(); 
            }

        }

        $pdf = new TCPDF;

        $netichette = $sheet1Data->count();

        $anno = date('Y');
        $inizio = '01-01-' . $anno;
        $costo = 20;

        // Position at 15 mm from bottom
        $pdf::SetY(-15);
        // Set font

        // set document information
        $pdf::SetCreator(PDF_CREATOR);
        $pdf::SetAuthor('10 Righe APS');
        $pdf::SetTitle('Ettichetta');
        $pdf::SetSubject('Rinnovi');
        $pdf::SetKeywords('APS');

        // set header and footer fonts
        $pdf::setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf::setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        // set default monospaced font
        $pdf::SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        // set margins
        //$pdf::SetMargins(PDF_MARGIN_LEFT, 0, PDF_MARGIN_RIGHT);
        $pdf::SetMargins(0.0, 1.1, 0.0, 0.0);
        // set auto page breaks
        $pdf::SetAutoPageBreak(true, 1);
        // $pdf::SetAutoPageBreak(true ,20);
        // set image scale factor
        $pdf::setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf::SetPrintHeader(false);
        $pdf::SetPrintFooter(false);
        // ---------------------------------------------------------

        // set font
        $pdf::SetFont('times', '', 10);
        // add a page
        $pdf::AddPage('P', 'A4');
        //$pdf::Cell(0, 0, 'A4 LANDSCAPE', 1, 1, 'C');
        // set cell padding
        $pdf::setCellPaddings(1, 0, 0, 0);
        // set cell margins
        $pdf::setCellMargins(0, 0, 0, 0);
        // set color for background
        $pdf::SetFillColor(255, 255, 255);
        //$pdf::SetFillColor(240,50,20);

        // Print text using writeHTMLCell()
        //$pdf::writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

        // Page number
        //$pdf::Cell(0, 5, 'Pagina ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');

        $incr = 0;
        $nrighe = 0;

        $datiEtichetta = Param_etichette::all();
        $spost_destra = -$datiEtichetta[0]->larghezza; //-70; // posizione orrizontale nella riga (larghezza etichetta)
        $spost_vertic = $datiEtichetta[0]->spazio_sopra; //3; // bordo pagina sopra
        $bordo_sopra = $datiEtichetta[0]->spazio_sopra; //3
        $n_etic_x_pagina = $datiEtichetta[0]->numero_verticale * $datiEtichetta[0]->numero_orrizontale; // 24;
        $altezza_etic = $datiEtichetta[0]->altezza;
        $spost_riga = 3;

        $rig = 8;

        for ($re = 0; $re < $netichette; $re++) {

            $spost_destra = $spost_destra + 70;
            if ($spost_destra > 140) {
                $spost_destra = 0;
                $spost_vertic = $spost_vertic + $altezza_etic; // posizione verticale della riga
                $spost_riga = $spost_vertic + 35; // posizione verticale della riga
                $nrighe++;
                $spost_vertic = $spost_vertic + 0.5;

            }

            if ($nrighe == $rig) {
                $nrighe = 0;
                $spost_vertic = $bordo_sopra;
                $pdf::AddPage('P', 'A4');
            }
            $riga = "--------";
            $xx = 'xx';
            if (isset($sheet1Data[$re]->nome)) {
                $htmlx = '<div style="font-size:14px">&nbsp;&nbsp;&nbsp;&nbsp;' . $sheet1Data[$re]->nome . '&nbsp;&nbsp;' . $sheet1Data[$re]->cognome . '
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;' . $sheet1Data[$re]->indirizzo . '
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;' . $sheet1Data[$re]->cap . '&nbsp;&nbsp;' . $sheet1Data[$re]->localita . '
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;' . $sheet1Data[$re]->sigla_provincia . '
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span style="font-size:8px">' . $sheet1Data[$re]->consegna . '</span>
                     </div>';
            } else {
                $htmlx = '<div style="font-size:14px">&nbsp;&nbsp;&nbsp;&nbsp;' . $xx . '&nbsp;&nbsp;' . $xx . '
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;' . $xx . '
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;' . $xx . '&nbsp;&nbsp;' . $xx . '
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;' . $xx . '
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span style="font-size:8px">' . $xx . '</span>
                     </div>';
            }

            $pdf::writeHTMLCell(70, 36, $spost_destra, $spost_vertic, $htmlx, 0, 0, 0, true, '', true);
            //$pdf::writeHTMLCell(70, 36, $spost_destra, $spost_riga, $riga, 0, 0, 0, true, '', true);

        }

        $pdf::Ln(0);

        $pdf::SetFillColor(255, 255, 255);
        $titolo_doc = 'Ettichette';

        // move pointer to last page
        $pdf::lastPage();

        // ---------------------------------------------------------
        //Close and output PDF document
        ob_clean();
        //Close and output PDF document
        $pdf::Output('escursione-' . $titolo_doc . '.pdf', 'I');

        exit();
    }

    public function getFiltroEtichette()
    {

        $viewData = [];
        $viewData["TipoEtichette"] = Param_etichette::all();

        return view('pdf.PdfFiltroEtichette')->with("viewData", $viewData);

    }

    public function printPagePdf()
    {

           $viewData = [];
           $anno = 2023;
           $html =  DB::table('socis')
           ->leftJoin('iscriziones', 'socis.id', '=', 'iscriziones.socio_id')
           ->get();

        

        $html2 = '<hr>';

        //$html = '<h1 style="color:red;">Hello World</h1>';

        $pdf = new TCPDF;

        $pdf::SetTitle('Hello World');

        $pdf::AddPage();

          //$pdf::writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

        foreach($html as $ht){ 
         $pdf::writeHTML('<div style="font-size:12px;margin-bottom:4px">'.$ht->nome.' '.$ht->cognome.' '.$ht->localita.' -- '.$ht->$anno.'</div>', true, false, true, false, '');
         $pdf::writeHTML($html2, true, false, true, false, '');
        }
        

        $pdf::Output('hello_world.pdf');

    }

}
