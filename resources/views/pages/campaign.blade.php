@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Campanhas')

@section('content')


{{-- page styles --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-sidebar.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-contacts.css')}}">
@endsection


<div style="bottom: 54px; right: 19px;" class="fixed-action-btn direction-top">
    <a class="btn-floating btn-large primary-text gradient-shadow contact-sidebar-trigger" onclick="openModal(false)" href="#modal1">
        <i class="material-icons">add</i>
    </a>
</div>
<!-- Add new contact popup Ends-->

<div class="content-area content-left">
    <div class="app-wrapper">
        <div class="datatable-search">
            <form action="{{ URL::route('search_campaigns') }}">
                <i class="material-icons mr-2 search-icon">search</i>
                <input type="text" placeholder="Procurar (Pressione enter para procurar)" class="app-filter" id="global_filter" name="search_campaign_name" value="{{Arr::get($search,'search_campaign_name')}}">
            </form>
        </div>
        <div id="button-trigger" class="card card card-default scrollspy border-radius-6 fixed-width">
            <div class="card-content p-0">
                <ul class="collapsible collection" data-collapsible="accordion">
                    @if(count($datas) == 0)
                    <li class="center">Não há campanhas cadastradas</li>
                    @endif
                    @foreach ($datas as $data)
                    <li id="{{$data->id}}">
                        <div class="collapsible-header">
                            {{$data->name}}
                        </div>
                        <div class="collapsible-body white">
                            <div class="row">
                                <span class="span-body">
                                    <span class="green-text">Status:</span>
                                    {{ $data->active ==  "1" ? 'Ativa': 'Desativada' }}
                                </span>
                            </div></br>
                            <div class="row">
                                <span class="span-body">
                                    <span class="green-text">Duração Frames:</span>
                                    {{ $data->duration_frames }}
                                </span>
                            </div></br>
                            @if($data->is_continuous )
                            @if($data['days_week'])
                            <div class="row">
                                <span class="span-body">
                                    <span class="green-text">Dias:</span>
                                    @if(in_array("0", $data['days_week']))
                                    Domingo
                                    @endif
                                    @if(in_array("1", $data['days_week']))
                                    Segunda
                                    @endif
                                    @if(in_array("2", $data['days_week']))
                                    Terça
                                    @endif
                                    @if(in_array("3", $data['days_week']))
                                    Quarta
                                    @endif
                                    @if(in_array("4", $data['days_week']))
                                    Quinta
                                    @endif
                                    @if(in_array("5", $data['days_week']))
                                    Sexta
                                    @endif
                                    @if(in_array("6", $data['days_week']))
                                    Sábado
                                    @endif
                                </span>
                            </div>

                            @endif
                            </br>
                            @else
                            <div class="row">
                                <span class="span-body">
                                    <span class="green-text">Início:</span>
                                    {{ $data->valid_at_format }}
                                    <br>
                                    <span class="green-text">Término:</span>
                                    {{ $data->valid_from_format }}
                                </span>
                            </div></br>
                            @endif
                            <div class="row">
                                <span class="span-body">
                                    <div class="row" id="photo{{$data->id}}" <?php if (!$data['background']) { ?> style="display:none" <?php } ?>>
                                        <h5 class="center">Remover Background <a class="waves-effect waves-light btn" onclick="askDeletePhoto({{$data->id}})"><i class="white-text material-icons">delete</i></a>
                                        </h5>
                                        <div class="col s12 m4 center">
                                            @if($data['background'])
                                            <img class="materialboxed responsive-img" width="550" src="<?= $urlAws . $data['background'] ?>" />
                                            @endif
                                        </div>
                                    </div>
                                </span>
                                <div class="row center">
                                    <span class="span-body">
                                        <!-- <a class="btn-small tooltipped green {{   count($data->users) <= 0 ? 'disabled' : '' }}" onclick="managerUser({{$data,json_encode($data->users)}})" data-position='bottom' data-delay='50' data-tooltip="Gerenciar Usuários">
                                            <i class="material-icons white-text">
                                                manage_accounts
                                            </i>
                                        </a> -->
                                        @if(!$data->is_birthday )
                                        <a class="btn-small tooltipped blue" onclick="addUser({{$data,json_encode($data->users)}})" data-position='bottom' data-delay='50' data-tooltip="Gerenciar Usuários">
                                            <i class="material-icons white-text">person</i>
                                        </a>
                                        @endif
                                    </span>
                                </div></br>
                            </div></br>
                            <hr>
                            <div class="row center">
                                <a class="btn-small tooltipped" onclick="editCampaign('{{$data->valid_at_input}}','{{$data->valid_from_input}}',{{$data}})" data-position='left' data-delay='50' data-tooltip="Editar Campanha">
                                    <i class="material-icons white-text">
                                        edit
                                    </i>
                                </a>
                                <a class="btn-small tooltipped red" onclick="askDelete({{$data->id}})" data-position='bottom' data-delay='50' data-tooltip="Deletar Campanha">
                                    <i class="material-icons white-text">
                                        clear
                                    </i>
                                </a>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<div id="modalAdd" class="modal modal-fixed-footer">
    <form class="col s12" method="POST" action="{{ URL::route('add_users_campaign') }}" id="formUsers">
        <div class="modal-content">
            <h4 class='center text-center'>Gerenciar usuário(s)</h4>
            <div class="row">
                <select class="select2 browser-default js-example-basic-multiple" name="users[]" id="user_selected" multiple="multiple">
                    @foreach ($users as $user)
                    <option data-name="{{$user->name}}" value="{{$user->id}}">
                        {{$user->name}}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-small waves-effect waves-light">
                <span>Salvar</span>
            </button>
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Fechar</a>
        </div>
    </form>
</div>

<!-- <div id="modalList" class="modal modal-fixed-footer">
    <form class="col s12" method="POST" action="{{ URL::route('add_users_campaign') }}" id="formUsersList">
        <div class="modal-content">
            <h4 class='center text-center'>Gerenciar usuários</h4>
            <div class="row">
                <table class="bordered center">
                    <tbody id="users_form">
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-small waves-effect waves-light">
                <span>Salvar</span>
            </button>
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
        </div>
    </form>
</div> -->

<div id="modalDeletePhoto" class="modal">
    <div class="modal-content gradient-45deg-indigo-purple white-text">
        <h4 class="center white-text row">Deletar background?</h4><br>
        <div class="row center">
            <input type="hidden" id="deleteInputPhoto">
            <a class="btn waves-effect waves-light white-text" onclick="deletePhoto('<?= URL::route('delete_background_campaign') ?>')">
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
            <div class="progress" id="loading" style="display:none">
                <div class="indeterminate"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Structure -->
<div id="modalDelete" class="modal">
    <div class="modal-content  gradient-45deg-indigo-purple  white-text">
        <h4 class="center white-text">Deletar Campanha?</h4>
        <div class="row center">
            <input type="hidden" id="deleteInput">
            <a class="btn waves-effect waves-light white-text " onclick="deleteData('{{ URL::route('delete_campaign') }}')">
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

<!--  Contact sidebar -->
<div class="contact-compose-sidebar">
    <form class="edit-contact-item mb-5 mt-5" method="POST" action="{{ URL::route('make_campaign') }}" id="form" enctype="multipart/form-data">
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
                        <input id="name" name="name" type="text" class="validate" required>
                        <label for="name">Nome</label>
                    </div>
                    <div class="input-field col s12">
                        <div class="row">
                            <div class="file-field input-field">
                                <div class="btn">
                                    <span>Background</span>
                                    <input type="file" name="background" accept="image/*" id="background">
                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text" id="background2">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="input-field col s12">
                        <p>
                            <label>
                                <input type="checkbox" name="active" id="active" checked>
                                <span>Ativa</span>
                            </label>
                        </p>
                    </div>


                    <div class="input-field col s12">
                        <input id="duration_frames" name="duration_frames" id="duration_frames" type="number" class="validate" required>
                        <label for="duration_frames">Duração por Frame(em segundos)</label>
                    </div>

                    <div id="dates">
                        <div class="input-field col s12">
                            <p>
                                <label>
                                    <input type="checkbox" id="checkDates" name="is_not_continuous" onclick="changeTypeDate()" checked>
                                    <span>Determinar data de início e fim</span>
                                </label>
                            </p>
                        </div>
                        <div id="dates_start_end">
                            <div class="input-field col s12">
                                <input id="valid_at" name="valid_at" type="date" class="validate">
                                <label for="valid_at">Data de início</label>
                            </div>
                            <div class="input-field col s12">
                                <input id="valid_from" name="valid_from" type="date" class="validate">
                                <label for="valid_from">Data de término</label>
                            </div>
                        </div>
                        <div id="days_week" style="display:none">
                            <div class="input-field col s12">
                                <p>
                                    <label>
                                        <input type="checkbox" name="days_week[]" id="day0" value="0" checked>
                                        <span>Domingo</span>
                                    </label>
                                </p>
                                <p>
                                    <label>
                                        <input type="checkbox" name="days_week[]" id="day1" value="1" checked>
                                        <span>Segunda</span>
                                    </label>
                                </p>
                                <p>
                                    <label>
                                        <input type="checkbox" name="days_week[]" id="day2" value="2" checked>
                                        <span>Terça</span>
                                    </label>
                                </p>
                                <p>
                                    <label>
                                        <input type="checkbox" name="days_week[]" id="day3" value="3" checked>
                                        <span>Quarta</span>
                                    </label>
                                </p>
                                <p>
                                    <label>
                                        <input type="checkbox" name="days_week[]" id="day4" value="4" checked>
                                        <span>Quinta</span>
                                    </label>
                                </p>
                                <p>
                                    <label>
                                        <input type="checkbox" name="days_week[]" id="day5" value="5" checked>
                                        <span>Sexta</span>
                                    </label>
                                </p>
                                <p>
                                    <label>
                                        <input type="checkbox" name="days_week[]" id="day6" value="6" checked>
                                        <span>Sábado</span>
                                    </label>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-action pl-0 pr-0 right-align">
                    <button class="btn-small waves-effect waves-light add-contact">
                        <span>Adicionar</span>
                    </button>
                    <button class="btn-small waves-effect waves-light update-contact display-none">
                        <span>Atualizar</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
    @if( method_exists($datas,'links') )
    <h1 class="center">{{$datas->links('vendor.pagination.materializecss')}}</h1>
    @endif
</div>

@endsection
<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>

<script>
    $(document).ready(function() {
        "use strict";
        $(".modal").modal();
        $("#modalAdd").modal();
        $('.materialboxed').materialbox();
        $('.js-example-basic-multiple').select2();


        var $old = "<?= !empty(old()); ?>";
        if ($old) {
            $("#old").val(1);
            formOldUser();
        }
    });

    function formOldUser() {
        if ($("#old").val() != 1) {
            this.clean();
        } else {
            $("#old").val(0);

            let $data = [];

            var list_of_all_old_value = <?= json_encode(session()->getOldInput())  ?>;

            let $id = '<?= old('id'); ?>' != '' ? '<?= old('id'); ?>' : false;
            if ($id) {
                $data['id'] = $id;
            }

            let $name = '<?= old('name'); ?>' != '' ? '<?= old('name'); ?>' : false;
            if ($name) {
                $data['name'] = $name;
            }

            let $duration_frames = '<?= old('duration_frames'); ?>' != '' ? '<?= old('duration_frames'); ?>' : false;
            if ($duration_frames) {
                $data['duration_frames'] = $duration_frames;
            }

            let $active = '<?= old('active'); ?>' != '' ? '<?= old('active'); ?>' : false;
            if ($active) {
                $data['active'] = $active;
            }

            let $is_not_continuous = '<?= old('is_not_continuous'); ?>' != '' ? false : true;
            $data['is_continuous'] = $is_not_continuous;

            $data['days_week'] = '<?= is_array(old('days_week')) == true ? implode(',', old('days_week'))  : "" ?>';

            let $is_birthday = '<?= old('is_birthday'); ?>' != '' ? '<?= old('is_birthday'); ?>' : false;
            if ($is_birthday) {
                $data['is_birthday'] = $is_birthday;
            }

            this.editCampaign('{{old("valid_at_input")}}', '{{old("valid_from_input")}}', $data);
        }
    }

    function clean() {
        $('#form').get(0).setAttribute('method', 'POST');
        $("#id").remove();
        $("#idCampaign").remove();
        $("#name").val('');
        $("#active").prop('checked', true);
        $("#duration_frames").val('');
        $("#checkDates").prop('checked', false);
        $('input[name^="days_week"]').each(function() {
            $(this).prop('checked', true);
        });
        changeTypeDate();
        $("#background1").val('');
        $("#background2").val('');
    }

    function addUser($data, $users) {
        closeForm();
        $("#idCampaign").remove();
        $id = $data['id'];
        $('#modalAdd').modal('open');
        $('<input>').attr({
            type: 'hidden',
            id: 'idCampaign',
            name: 'campaign_id',
            value: $id
        }).appendTo('#formUsers');

        let listaSimilares = [];

        $users = $data['users'];
        $users.forEach(element => {
            listaSimilares.push(element.id);
        });

        $("#user_selected").val(listaSimilares).change();
    }

    function managerUser($data, $users) {
        closeForm();
        $("#idCampaign").remove();
        $id = $data['id'];
        $('#modalList').modal('open');
        $('<input>').attr({
            type: 'hidden',
            id: 'idCampaign',
            name: 'campaign_id',
            value: $id
        }).appendTo('#formUsersList');

        $users = $data['users'];
        $("#users_form").empty();
        $users.forEach(element => {
            $element = createRow(element.id, element.name);
            $("#users_form").append($element);
        });
    }

    function askDeletePhoto(id) {
        $('#modalDeletePhoto').modal('open');
        $("#deleteInputPhoto").val(id);
    }

    function createRow($user, $name) {
        return $(`<tr id="rowuser${$user}">
                            <td>
                                <input type="hidden"  name="users[]"  value="${$user}">
                                <input placeholder="Nome" type="text" class="validate" readonly disabled value="${$name}">
                                <label for="name">Nome</label>
                            </td>
                            <td>
                                <a class="btn-small red" onclick="removeUserCamp(${$user})">
                                    <i class="material-icons white-text">
                                        clear
                                    </i>
                                </a>
                            </td>
                        </tr>`);

    }

    function removeUserCamp($user) {
        $(`#rowuser${$user}`).remove();
    }
</script>