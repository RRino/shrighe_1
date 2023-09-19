@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet"
    crossorigin="anonymous" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">



<style>
    .frighe {
        width: 200px;
    }

    .adds {
       margin:3px;
    }
</style>
<div class="box">
    @section('content')

        <div class="container-fluid">
        @section('title', $viewData['title'])
        <div class="card mb-6">
            <div class="card-header">
                <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ">
                            <a class="btn btn-primary btn-sm adds" href="{{ '/formExcel_soci' }}" role="button">Excel</a>
                            <a class="btn btn-primary btn-sm adds" href="{{ '/formAdd' }}" role="button">Aggiungi Socio</a>
                            <a class="btn btn-primary btn-sm adds" href="{{ '/etichette_anno' }}" role="button">Etichette anno</a>
                            <a class="btn btn-primary btn-sm adds" href="{{ '/bollettini_anno' }}" role="button">Bollettini anno</a>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Azione da Sel.
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <button class="dropdown-item btn-link saveEtt">Etichette da Sel.</button>
                                        <button class="dropdown-item btn-link  saveAll">Bollettini da Sel.</button>
                                        <button class="dropdown-item btn-link del_socio">Cancella soci Sel. </button>
                                    </ul>

                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                                </div>
                            </li>

                    <a class="btn btn-success btn-sm" href="{{ '/iscrizione' }}" role="button">Iscrizioni</a>
                        </ul>
                    </div>
                </nav>

            </div>

            <div class="card-body">
                @if ($errors->any())
                    <ul class="alert alert-danger list-unstyled">
                        @foreach ($errors->all() as $error)
                            <li>- {{ $error }}</li>
                        @endforeach
                    </ul>
                @endif


                <div class="card">
                    <div class="card-header">

                        <div class="container">
                            <div class="row">
                                <div class="col-3">
                                    <!-- input numero righe -->
                                    <form class="frighe" action="/paginazione" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="input-group">
                                                @php
                                                    if (session('pag') == null) {
                                                        session(['pag' => 25]);
                                                    }
                                                @endphp
                                                <input type="text" class="form-control te " id="nom"
                                                    name="rows" value="{{ session('pag') }}"
                                                    placeholder="N. righe visualizzate">
                                                <button type="submit" class="btn btn-success bt-sm te">N.Righe</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-3">
                                    <!-- input anno -->
                                    <form class="fanno" action="/selAnno" method="POST">
                                        @csrf

                                        <div class="input-group">
                                            <input type="text" class="form-control te wanno" id="nom"
                                                name="anno" value="" placeholder="inserire anno o  tutti ">
                                            <button type="submit" class="btn btn-success bt-sm te">Anno
                                                rinnovo</button>
                                        </div>

                                    </form>
                                </div>
                                <div class="col-3">
                                    <form class="fanno" action="/selAnno" method="POST">
                                        @csrf

                                        <div class="input-group">
                                            <input type="hidden" class="form-control te wanno" id="nom"
                                                name="anno" value="tutti" placeholder="inserire anno o  tutti ">
                                            <button type="submit" class="btn btn-success bt-sm te">Tutti</button>
                                        </div>

                                    </form>
                                </div>


                                <div class="col-3">
                                    <!-- input cognome-->
                                    <form class="fcognome" action="/selCognome" method="POST">
                                        @csrf
                                        <div class="input-group gr">

                                            <input type="text" class="form-control te wcognome" id="wcognome"
                                                name="cognome" value="" placeholder="Cerca un cognome ">
                                            <button type="submit" class="btn btn-success bt-sm te">Cognome</button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>


                </div>
            </div>





        </div>
        <div class="colo">

            @php
                use Carbon\Carbon;
                $anno = Carbon::now()->format('Y');
                $anno3 = 'a' . $anno+1;
                $anno0 = 'a' . $anno;
                $anno1 = 'a' . $anno - 1;
                $anno2 = 'a' . $anno - 2;
            @endphp

            <div class="card-body">
                {{ 'Selezionati: ' . $viewData['servizio'] }}.<span
                    class="coloreanno">{{ ' Anno: ' . $viewData['anno'] }}</span>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Sel</th>
                            <th scope="col">Id</th>

                            <th scope="col">Nome</th>
                            <th scope="col"><a href="/list/cognome">Cognome</a></th>
                            <!-- Route::get('/list/{col}', 'indexOrd');//ok ordina una colonna in index.blade -->
                            <th scope="col"><a href="/list/indirizzo">Indirizzo</a></th>
                            <th scope="col">CAP</th>
                            <th scope="col"><a href="/list/localita">Località</a></th>
                            <th scope="col"><a href="/list/comune">Comune</a></th>
                            <th scope="col"><a href="/list/sigla_provincia">Prov.</a></th>
                            <th scope="col">Consegne</th>
                            <th scope="col">Socio</th>
                            <th scope="col">{{ $anno+1 }}</th>
                            <th scope="col">{{ $anno }}</th>
                            <th scope="col">{{ $anno - 1 }}</th>
                            <th scope="col">{{ $anno - 2 }}</th>
                            <!--<th scope="col">Attivo</th>--><!-- Non usato per ora -->

                        </tr>
                    </thead>
                    <tbody>
                        {{-- dd($viewData) --}}
                        @foreach ($viewData['socis'] as $soci)
                            <tr>
                                <td><input type="checkbox" class="checkbox" data-id="{{ $soci->getId() }}"></td>

                                <td><a href="/singolo/{{ $soci->getId() }}">Edit</a></td>

                                <td>{{ $soci->getNome() }}</td>
                                <td>{{ $soci->getCognome() }}</td>
                                <td>{{ $soci->getIndirizzo() }}</td>
                                <td>{{ $soci->getCap() }}</td>
                                <td>{{ $soci->getLocalita() }}</td>
                                <td>{{ $soci->getComune() }}</td>
                                <td>{{ $soci->getSigla_provincia() }}</td>
                                <td>{{ $soci->getConsegna() }}</td>

                                <td>{{ $soci->getTipo_socio() }}</td>
                                <td>{{ $soci->$anno3 }}</td>
                                <td>{{ $soci->$anno0 }}</td>
                                <td>{{ $soci->$anno1 }}</td>
                                <td>{{ $soci->$anno2 }}</td>
                                <!-- non usato per ora -->
                                <?php /*
                                @if ($soci->getPublished() == "Si")
                                    <td><a style="color:green" href="/changeStatus/{{ $soci->getId() }}"> Si </a>
                                    @else
                                    <td><a style="color:red" href="/changeStatus/{{ $soci->getId() }}"> No </a>
                                @endif 
                                </td>
                               */
                                ?>

                                <td>{{ $soci->iscrizione_anno }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $viewData['socis']->links() }}
        </div>

    @endsection
    <br>
    <br>
</div>




<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        @if ($errors->any())
            alert(' Selezionare almeno un Utente [x]');
        @endif

        // Crea Bollettini da chckbox selezionato

        $('.saveAll').on('click', function(e) {
            console.log('0-clic');
            var studentIdArr = [];
            $(".checkbox:checked").each(function() {
                studentIdArr.push($(this).attr('data-id'));
            });
            console.log('1-st ' + studentIdArr);
            if (studentIdArr.length <= 0) {
                alert("Scegliere almeno un nome [ ]");
            } else {
                var stuId = studentIdArr.join(",");
                console.log('2-s', stuId);
                $.ajax({
                    url: "{{ url('salvaChck') }}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: 'ids=' + stuId,
                    success: function(data) {
                        console.log('3-dt '.data);
                        window.location.href = "/bollettini/1";
                    },
                    error: function(data) {
                        alert(data.responseText);
                    }
                });
            }
        });

        // Crea etichette da sel. chckbox

        $('.saveEtt').on('click', function(e) {
            console.log('0xclic');
            var studentIdArr = [];
            $(".checkbox:checked").each(function() {
                studentIdArr.push($(this).attr('data-id'));
            });
            console.log('1xst ' + studentIdArr);
            if (studentIdArr.length <= 0) {
                alert("Scegliere almeno un nome [ ]");
            } else {
                var stuId = studentIdArr.join(",");
                console.log('2xs', stuId);
                $.ajax({
                    url: "{{ url('salvaChck') }}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: 'ids=' + stuId,
                    success: function(data) {
                        console.log('3xdt '.data);
                        window.location.href = "/etichette/1";
                    },
                    error: function(data) {
                        alert(data.responseText);
                    }
                });
            }
        });


        // Del socio sel. checkbox

        $('.del_socio').on('click', function(e) {
            console.log('clicy');

            var eticIdArr = [];
            $(".checkbox:checked").each(function() {
                eticIdArr.push($(this).attr('data-id'));

            });
            console.log('sty ' + eticIdArr);
            if (eticIdArr.length <= 0) {
                alert("Scegliere almeno un nome [ ]");
            } else {
                sicuro = confirm('Sei sicuro?');
                console.log('sicuroy ' + sicuro);
                if (sicuro) {
                    //if (confirm("Sicuro?")) {
                    var etiId = eticIdArr.join(",");
                    console.log('datay ' + etiId);
                    $.ajax({
                        url: "{{ url('salvaChck') }}",
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: 'ids=' + etiId,
                        success: function(data) {
                            window.location.href = "/deleteSoci/1";
                        },
                        error: function(data) {
                            alert('ERRORE'.data.responseText);
                        }
                    });
                    //}
                }
            }
        });


    });
</script>


<!-- Cambia stato published -->
<script>
    $(function() {
        $('.form-check-input').change(function() {
            var status = $(this).prop('checked') == true ? 1 : 0;
            var user_id = $(this).data('id');
            $.ajax({
                type: "GET",
                dataType: "json",
                url: '/changeStatus',
                data: {
                    'status': status,
                    'user_id': user_id
                },
                success: function(data) {
                    console.log(data.success)
                }
            });
        })
    })
</script>
