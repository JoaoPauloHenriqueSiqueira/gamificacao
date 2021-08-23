{{-- extend layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Mensagem')
<style>
    .center .materialboxed,
    .center-align .materialboxed .responsive-img {
        margin: 0 auto;
        position: relative !important;
    }
</style>
{{-- page content --}}

@section('content')

{{-- vendor styles --}}
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/flag-icon/css/flag-icon.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/data-tables/css/jquery.dataTables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css')}}">
@endsection

{{-- page styles --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-sidebar.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-contacts.css')}}">
@endsection

<?php $user = Auth::user(); ?>
@if(isset($user))

<div class="content-area content-left">
    @if( method_exists($datas,'links') )
    <h1 class="center">{{$datas->links('vendor.pagination.materializecss')}}</h1>
    @endif

    <div class="app-wrapper">
        <div id="button-trigger" class="card card card-default scrollspy border-radius-6 fixed-width">
            <div class="card-content p-0">
                <ul class="collapsible collection" data-collapsible="accordion">
                    @if(count($datas) == 0)
                    <li class="center">Nenhuma mensagem enviada hoje</li>
                    @endif
                    @foreach ($datas as $data)
                    <li id="{{$data->id}}" class="collection-item">
                        <div>
                            {{$data->message}}
                            <a class="secondary-content tooltipped red" onclick="askDelete({{$data->id}})" data-position='bottom' data-delay='50' data-tooltip="Deletar">
                                <i class="material-icons white-text">
                                    clear
                                </i>
                            </a>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @if( method_exists($datas,'links') )
    <h1 class="center">{{$datas->links('vendor.pagination.materializecss')}}</h1>
    @endif

    <div style="bottom: 54px; right: 19px;" class="fixed-action-btn direction-top">
        <a class="btn-floating btn-large primary-text gradient-shadow contact-sidebar-trigger" onclick="openModal(false)" href="#modal1">
            <i class="material-icons">add</i>
        </a>
    </div>
</div>

<div id="modalDelete" class="modal">
    <div class="modal-content  gradient-45deg-indigo-purple  white-text">
        <h4 class="center white-text">Deletar Mensagem?</h4>
        <div class="row center">
            <input type="hidden" id="deleteInput">
            <a class="btn waves-effect waves-light white-text " onclick="deleteData('{{ URL::route('delete_message') }}')">
                <i class="material-icons white-text">
                    done
                </i>
            </a>
            <a class="btn blue waves-effect waves-light white-text" onclick="closeModal()">
                <i class="material-icons white-text">
                    close
                </i>
            </a>
        </div>
        <br>
        <div class="row center">
            <div class="progress" id="indeterminate" style="display:none">
                <div class="indeterminate"></div>
            </div>
        </div>
    </div>
</div>

<div class="contact-compose-sidebar">
    <form class="edit-contact-item mb-5 mt-5" method="POST" action="{{ URL::route('make_message') }}" id="form">
        <input type="hidden" id="old">
        <div class="card quill-wrapper">
            <div class="card-content pt-0">
                <div class="card-header display-flex pb-2">
                    <h3 class="card-title contact-title-label">Formulário</h3>
                    <div class="close close-icon">
                        <i class="material-icons" onclick="closeForm()">close</i>
                    </div>
                </div>
                <div class="divider"></div>

                <!-- form start -->
                <div class="row">
                    <div class="input-field col s12">
                        <input type="text" id="message" name="message" class="validate" data-length="255" maxlength="255">
                        <label for="message">Mensagem</label>
                    </div>
                </div>

                <div class="card-action pl-0 pr-0 right-align">
                    <button class="btn-small waves-effect waves-light add-contact">
                        <span>Enviar</span>
                    </button>
                </div>
                <!-- form start end-->
            </div>
        </div>
    </form>
</div>

@else

<div class="row">
    <!-- invoice view page -->
    <div class="col s12">
        <div class="card">
            <div class="card-content px-36">
                <div class="invoice-product-details mb-3">
                    <form class="form invoice-item-repeater" action="{{ URL::route('make_message') }}" method="POST">
                        <div data-repeater-list="group-a">
                            <div class="mb-2" data-repeater-item="">
                                <div class="row">
                                    <div class="invoice-item display-flex mb-1">
                                        <div class="col s12 input-field">
                                            <input type="text"  id="message" name="message" class="invoice-item-desc" placeholder='Envie sua mensagem' data-length="255" maxlength="255">
                                        </div>
                                        <input type="hidden" name="token" value="{{$company['token_screen']}}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col right s12  input-field">
                                        <button class="btn right" type="submit">
                                            <span class="material-icons-outlined">
                                                <i class="material-icons">send</i>
                                            </span>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endif
@endsection
<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('input#message').characterCounter();
    });

    function clean() {
        $('#form').get(0).setAttribute('method', 'POST');
        $("#message").val('');
    }
</script>