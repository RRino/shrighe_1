@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet"
    crossorigin="anonymous" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">



<style>
    .img_dim {
        height: 50px;
    }

    .col {
        border: solid 1px #ccc;
        padding: 5px;
        min-width: 150px;
        margin-bottom:10px;
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
                    <div class="card-header">{{ __('Anagrafiche') }}</div>

                    <div class="card-body">
                        <a class="btn btn-success btn-sm b-add" href="{{ '/iconeHome' }}" role="button">Home
                            Anagrafiche</a><br><br>
                        <hr>


                        <div class="container-fluid">

                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <a href="/anagrafiche/tab1" id="home-tab"
                                    class="nav-link @if ($viewData['anagrafiche'] === 'tab1') active @endif">Dati Generali</a>

                                <a href="/anagrafiche/tab2" id="home-tab"
                                    class="nav-link @if ($viewData['anagrafiche'] === 'tab2') active @endif">Indirizzi e
                                    Contatti</a>

                                <!-- <a href="/anagrafiche/tab3" id="home-tab"
                                        class="nav-link @if ($viewData['anagrafiche'] === 'tab3') active @endif">Dati specifici</a>
                        
                                    <a href="/anagrafiche/tab4" id="home-tab"
                                        class="nav-link @if ($viewData['anagrafiche'] === 'tab4') active @endif">Collegamenti</a>
                                   -->
                                <a href="/anagrafiche/tab5" id="home-tab"
                                    class="nav-link @if ($viewData['anagrafiche'] === 'tab5') active @endif">Documenti</a>
                            </ul>

                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="home" role="tabpanel"
                                    aria-labelledby="home-tab">...</div>
                                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">...
                                </div>

                            </div>
                            {{-- dd($viewData) --}}
                            @if ($viewData['tabella'] == 'anagrafica')
                                <div class="container">
                                    <div class="row">
                                        @php
                                            $ns = count($viewData['dati']);
                                        @endphp
                                        @for ($dt = 0; $dt < $ns; $dt++)
                                            <div class="col">
                                                {{ $viewData['dati'][$dt]->id }}<br>
                                                {{ $viewData['dati'][$dt]->nome }}
                                                {{ $viewData['dati'][$dt]->cognome }}<br>
                                                {{ $viewData['dati'][$dt]->indirizzo }} <br>
                                            </div>
                                            <br>
                                        @endfor
                                    </div>
                                </div>
                            @endif

                            @if ($viewData['tabella'] != 'immagini')
                                <!-- tutte le tabelle -->
                            @else
                                <!-- Tabella immagini -->
                                <table>
                                    <thead>
                                        <tr class="head">
                                            <td>id</td>
                                            <td>Nome</td>
                                            <td>Posizione</td>
                                            <td style="padding:5px">Documento</td>
                                            <td>Scarica </td>
                                            <td>Cancella </td>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($viewData['images4'] as $img)
                                            @if ($img != '.' && $img != '..')
                                                {{-- dd($img->path) --}}
                                                <tr class="colo-list">
                                                    <td style="background:#fff;padding:5px">{{ $img->id }}</td>

                                                    <td style="padding:5px"> <a
                                                            href=<?php echo '/' . $img->path . '/' . str_replace(' ', '%20', $img->nome_file); ?>><?php echo $img->nome_file . '&nbsp;&nbsp;&nbsp;'; ?></a></td>

                                                    <td style="padding:5px">{{ '/' . $img->path }}</td>

                                                    @if ($img->categor == 'png' || $img->categor == 'jpg')
                                                        <td style="padding-right:5px"> <a href=<?php echo '/' . $img->path . '/' . str_replace(' ', '%20', $img->nome_file); ?>><img
                                                                    class="img_dim" src="<?php echo '/' . $img->path . '/' . $img->nome_file; ?>"></a> </td>
                                                    @else
                                                        <td style="padding:5px"></td>
                                                    @endif

                                                    <td style="padding:5px"><a
                                                            href={{ '/download/' . $img->id }}>Scarica</a></td>

                                                    <td style="padding:5px"><a href={{ '/deleteFile/' . $img->id }}
                                                            onclick="return confirm('Sei sicuro?')">Cancella</a></td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                                <br>
                                <a class="btn btn-success btn-sm " href="{{ '/file' }}" role="button">File
                                    upload</a>
                            @endif




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
