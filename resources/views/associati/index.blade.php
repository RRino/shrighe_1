@extends('layouts.app')

<style>
    .img_dim {
        height: 50px;
    }

    .col {
        border: solid 1px #ccc;
        padding: 5px;
        min-width: 250px;
        margin-bottom: 10px;
        margin-left: 10px;
        height: auto;

    }

    .lab {
        width: 150px;
        color: #2f3a79;
    }





    .published {
        position: absolute;
        bottom: 0px;

    }

    .rsp {
        padding-top: 10px;
    }


</style>



@php
    use Carbon\Carbon;
    $anno = Carbon::now()->format('Y');
    // $anno = $viewData["anno"];
    
    $anno0 = 'a' . $anno;
    $anno1 = 'a' . $anno - 1;
    $anno2 = 'a' . $anno - 2;
    $anno3 = 'a' . $anno + 1;
@endphp

@section('content')
    <div class="container-fluid">

        @if ($errors->any())
            <ul class="alert alert-danger list-unstyled">
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        @endif


        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Associati') }}</div>

                    <div class="card-body">
                        <a class="btn btn-success btn-sm b-add" href="{{ '/formAddAssociati' }}" role="button">Aggiungi</a><br><br>
                        <hr>

                        <div id="tit2" style="text-align:center;margin-bottom:10px;font-size:16px;font-weight:700;"></div>
                        <div class="container">
                            
                            <div class="row">

                               
                                
                                @php $primo = 0; @endphp
                                @foreach ($viewData['associati'] as $soci)
                                    <div class="col">
                                        @if ($soci->ruoli->id == 1)
                                            @if ($primo == 0)
                                                <div id="tit1">{{ $soci->ruoli->nome }}</div>
                                                @php $primo = 1; @endphp
                                            @endif
                                            {{ $soci->anagrafica->id . ' ' . $soci->anagrafica->nome . ' ' . $soci->anagrafica->cognome }}<br><br>
                                            <label class="lab">Indirizzo:</label>{{ $soci->anagrafica->indirizzo }}<br>
                                            <label class="lab">CAP:</label>{{ $soci->anagrafica->cap }}<br>
                                            <label class="lab">località:</label>{{ $soci->anagrafica->localita }}<br>
                                            <label class="lab">Comune:</label>{{ $soci->anagrafica->comune }}<br>
                                            <label
                                                class="lab">Provincia:</label>{{ $soci->anagrafica->sigla_provincia }}<br>
                                            <label class="lab">Email:</label>{{ $soci->anagrafica->email }}<br>
                                            <label class="lab">PEC:</label>{{ $soci->anagrafica->pec }}<br>
                                            <label class="lab">C.D.F:</label>{{ $soci->anagrafica->codice_fiscale }}<br>
                                            <label class="lab">P.IVA:</label>{{ $soci->anagrafica->partita_iva }}<br>
                                            <label class="lab">Tel.:</label>{{ $soci->anagrafica->telefono }}<br>
                                            <label class="lab">Cell.:</label>{{ $soci->anagrafica->cellulare }}<br>

                                            <div class="ruolo"><label
                                                    class="lab">Ruolo:</label>{{ $soci->ruoli->nome }}<br></div>

                                            <div class="ruolo_spec">
                                                
                                                <label class="lab rsp">Ruolo
                                                    specifico:</label><br>
                                                @forelse($soci->ruoli_specm as $soci->ruoli_specm)
                                                    {{ $soci->ruoli_specm->nome }}
                                                @empty
                                                   
                                                @endforelse
                                                <br>
                                            </div>
                                            <br>
                                            <div >
                                                <label class="lab dat">Data
                                                    iscrizione:</label><br>
                                                @forelse($soci->dateiscr_many as $soci->date_specm)
                                                    {{ $soci->date_specm->nome }}
                                                @empty
                                                   
                                                @endforelse
                                                <br>
                                            </div>

                                            <div >
                                                <label class="lab">Pubblicato:</label>
                                                {{ $soci->anagrafica->published }}
                                                </label><br>
                                            </div>
                                    </div>
                                @endif
                                @endforeach


                            </div>
                            {{-- $viewData['iscrizioni']->links() --}}
                            <br>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>
    <br>
    <br>
@endsection

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        var myDiv1Para = $('#tit1').remove();
        myDiv1Para.appendTo('#tit2');

    });
</script>
