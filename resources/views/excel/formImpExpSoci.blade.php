@extends('layouts.app')

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




        <div class="card-body">
            <a class="btn btn-success btn-sm b-add" href="{{ '/list' }}" role="button">Lista
                Soci</a><br><br>
            <hr>

            <div class="container" style="border:1px solid #cecece;padding:20px;border-radius:5px;">
                <div class="row">
                    <div class="col-sm">
                        <div class="card">

                            <div class="card-header bgsize-primary-4 white card-header">
                                <h4 class="card-title">Importa Soci da Excel</h4>
                            </div>

                            <div class="card-body">

                                @if ($message = Session::get('success'))
                                    <div class="alert alert-success alert-block">
                                        <button type="button" class="close" data-dismiss="alert"></button>
                                        <strong>{{ $message }}</strong>
                                    </div>

                                    <br>
                                @endif

                                <form action="{{ url('importSoci') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <fieldset>
                                        <label>Segliere il file Soci da caricare <small
                                                class="warning text-muted">{{ __('Please upload only Excel (.xlsx or .xls) files') }}</small></label>
                                        <div class="minput-group">
                                            <input type="file" required class="form-control" name="uploaded_file"
                                                id="uploaded_file">
                                            @if ($errors->has('uploaded_file'))
                                                <p class="text-right mb-0">
                                                    <small class="danger text-muted"
                                                        id="file-error">{{ $errors->first('uploaded_file') }}</small>
                                                </p>
                                            @endif

                                            <div class="input-group-append" id="button-addon2">
                                                <button class="btn btn-primary square btn-sm cae" type="submit"><i
                                                        class="ft-upload mr-1"></i> Carica Soci
                                                    Excel esportati</button>
                                            </div>
                                        </div>
                                    </fieldset>
                                </form>

                                <!-- da cancellare dopo utilizzato la nuova versione di excel -->
                                <form action="{{ url('importSoci_old') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <fieldset>
                                        <label>Segliere il file Soci da caricare <small
                                                class="warning text-muted">{{ __('Please upload only Excel (.xlsx or .xls) files') }}</small></label>
                                        <div class="minput-group">
                                            <input type="file" required class="form-control" name="uploaded_file"
                                                id="uploaded_file">
                                            @if ($errors->has('uploaded_file'))
                                                <p class="text-right mb-0">
                                                    <small class="danger text-muted"
                                                        id="file-error">{{ $errors->first('uploaded_file') }}</small>
                                                </p>
                                            @endif

                                            <div class="input-group-append" id="button-addon2">
                                                <button class="btn btn-primary square btn-sm cae" type="submit"><i
                                                        class="ft-upload mr-1"></i> Carica Soci
                                                    Excel old</button>
                                            </div>
                                        </div>
                                    </fieldset>
                                </form>
                                <!-- /cancellare -->

                            </div>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="card-header bgsize-primary-4 white card-header">
                            <h4 class="card-title"> Esporta Soci in Excel</h4>
                        </div>

                        <div class="card-body">
                            <div class="pull-right--">
                                <form method="POST" action="/exportSoci" enctype="multipart/form-data">
                                    @csrf
                                    <!--<div class="mb-3 row">
                                                                <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Anno iscriz:</label>
                                                                <div class="col-lg-10 col-md-6 col-sm-12">
                                                                    <input name="anno" value="" type="text" class="form-control">
                                                                    <button type="submit" class="btn btn-primary btn-sm">Esporta Soci anno</button>
                                                                </div>
                                                            </div>-->


                                    <div class="mb-3 row">
                                        <label class="col-lg-2 col-md-6 col-sm-12 col-form-label">Tutti:</label>
                                        <div class="col-lg-10 col-md-6 col-sm-12">
                                            <a class="col-lg-6 col-md-6 col-sm-12  btn btn-primary btn-sm"
                                                href="{{ '/exportSociTutti' }}" role="button">Esporta soci</a>
                                        </div>
                                    </div>
                                </form>


                            </div>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="card-header bgsize-primary-4 white card-header">
                            <h4 class="card-title">Ritorno a pagina</h4>
                        </div>

                        <div class="card-body">
                            <a class="btn btn-success btn-sm b-add cae" href="{{ '/list' }}" role="button">Lista
                                Soci</a>
                            <a class="btn btn-success btn-sm b-add cae" href="{{ '/iscrizione' }}" role="button">Lista
                                Iscrizioni</a>
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
