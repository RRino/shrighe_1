@extends('layouts.app')

@php
use Carbon\Carbon;
$anno = Carbon::now()->format('Y');
// $anno = $viewData["anno"];

$anno0 = 'a'.$anno;
$anno1 = 'a'.$anno-1;
$anno2 = 'a'.$anno-2;
$anno3 = 'a'.$anno+1;
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
                    <div class="card-header">{{ __('xxxxxx') }}</div>

                    <div class="card-body">
                        <a class="btn btn-success btn-sm b-add" href="{{ '/list' }}" role="button">Lista Soci</a><br><br>
                        <hr>
                    

                        <div class="container">
                            <table class="table tab1">
                                <tr class="head">
                        
                                    <td>socio_id</td>
                                    <td>Nome</td>
                                    <td>Cognome</td>
                                    <td>{{ $anno+1 }}</td>
                                    <td>{{ $anno }}</td>
                                    <td>{{ $anno-1 }}</td>
                                    <td>{{ $anno-2 }}</td>
                                   
                                   
                        
                                </tr>
                        
                        
                        
                        
                                
                              
                                    <tr class="colo-list">
                                        <td style="background:#fff;">{{-- $anag->socio_id --}}</td>
                                        <td>{{-- $anag['nome'] --}}</td>
                                        <td>{{-- $anag['cognome'] --}}</td>
                                        <td>{{-- $anag->$anno3 --}}</td>
                                        <td>{{-- $anag->$anno0 --}}</td>
                                        <td>{{-- $anag->$anno1 --}}</td>
                                        <td>{{-- $anag->$anno2 --}}</td>
                                        
                        
                        
                                       
                                            
                                        </td>
                                    </tr>
                        
                                 
                            </table>

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