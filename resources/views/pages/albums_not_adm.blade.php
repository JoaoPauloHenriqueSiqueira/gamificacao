@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Álbuns')

@section('content')

{{-- page styles --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-sidebar.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-contacts.css')}}">
@endsection

<div class="content-area content-left">
    <div class="app-wrapper">
        <div class="datatable-search">
            <form action="{{ URL::route('search_public_albums') }}">
                <i class="material-icons mr-2 search-icon">search</i>
                <input type="text" placeholder="Procurar (Pressione enter para procurar)" class="app-filter" id="global_filter" name="search_album_name" value="{{Arr::get($search,'search_album_name')}}">
            </form>
        </div>
        <div id="button-trigger" class="card card card-default scrollspy border-radius-6 fixed-width">
            <div class="card-content p-0">
                <ul class="collapsible collection" data-collapsible="accordion">
                    @if(count($datas) == 0)
                    <li class="center">Não há álbums cadastrados</li>
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
                                    <span class="green-text">Pública?:</span>
                                    {{ $data->public ==  "1" ? 'Sim': 'Não' }}
                                </span>
                            </div></br>
                            @if($data->is_continuous)
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
                            </div></br>
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

                            <div class="row center">
                                <span class="span-body">
                                    <a class="btn-small tooltipped green {{   count($data->photos) <= 0 ? 'disabled' : '' }}" onclick="managerPhotos({{$data,json_encode($data->photos)}})" data-position='bottom' data-delay='50' data-tooltip="Gerenciar Fotos">
                                        <i class="material-icons white-text">
                                            perm_media
                                        </i>
                                    </a>
                                    <a class="btn-small tooltipped blue" onclick="addPhotos({{$data,json_encode($data->photos)}})" data-position='bottom' data-delay='50' data-tooltip="Adicionar Fotos">
                                        <i class="material-icons white-text">
                                            add_a_photo
                                        </i>
                                    </a>
                                </span>
                            </div>
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
</div>

<div id="modalAdd" class="modal modal-fixed-footer">
    <form class="col s12" method="POST" action="{{ URL::route('add_photo_album_public') }}" id="formUsers" enctype="multipart/form-data">

        <div class="modal-content">
            <h4 class='center text-center'>Adicionar foto(s)</h4>
            <div class="row">
                <div class="input-field col s12">
                    <div class="file-field input-field">
                        <div class="btn">
                            <span>Fotos</span>
                            <input type="file" name="photos[]" accept="image/*" id="photos" multiple>
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text" id="photos2">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-small waves-effect waves-light">
                <span>Salvar</span>
            </button>
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Cancelar</a>
        </div>
    </form>
</div>


<div id="modalList" class="modal modal-fixed-footer">
    <div class="modal-content">
        <div class="row">
            <table class="bordered center">
                <thead>
                    <tr>
                    </tr>
                </thead>
                <tbody id="users_form">
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Fechar</a>
    </div>
</div>

<!-- Modal Structure -->
<div id="modalDeletePhoto" class="modal">
    <div class="modal-content gradient-45deg-indigo-purple white-text">
        <h4 class="center white-text row">Deletar background?</h4><br>
        <div class="row center">
            <input type="hidden" id="deleteInputPhoto">
            <a class="btn waves-effect waves-light white-text" onclick="deletePhoto('<?= URL::route('delete_background_album') ?>')">
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

<div id="modalDeletePhotoAlbum" class="modal">
    <div class="modal-content gradient-45deg-indigo-purple white-text">
        <h4 class="center  white-text row">Deletar foto?</h4><br>
        <div class="row center">
            <input type="hidden" id="deleteInputPhotoAlbum">
            <a class="btn  waves-effect waves-light white-text" onclick="deletePhotoAlbum()">
                <i class="material-icons white-text">
                    done
                </i>
            </a>
            <a class="btn blue waves-effect waves-light white-text" onclick="closeModalAlbum()">
                <i class="material-icons white-text">
                    close
                </i>
            </a>
        </div>
    </div>
</div>

<!-- Modal Structure -->
<div id="modalDelete" class="modal">
    <div class="modal-content  gradient-45deg-indigo-purple  white-text">
        <h4 class="center white-text">Deletar Álbum?</h4>
        <div class="row center">
            <input type="hidden" id="deleteInput">
            <a class="btn waves-effect waves-light white-text " onclick="deleteData('{{ URL::route('delete_album') }}')">
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
    <form class="edit-contact-item mb-5 mt-5" method="POST" action="{{ URL::route('make_album') }}" id="form" enctype="multipart/form-data">
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
                        <p>
                            <label>
                                <input type="checkbox" name="public" id="public">
                                <span>Pública (usuários podem contribuir)</span>
                            </label>
                        </p>
                    </div>
                    <div class="input-field col s12">
                        <input id="duration_frames" name="duration_frames" id="duration_frames" type="number" class="validate" required>
                        <label for="duration_frames">Duração por Frame(em segundos)</label>
                    </div>
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
  
</div>

@endsection
<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>

<script>
    $(document).ready(function() {
        "use strict";
        $(".modal").modal();
        $("#modalAdd").modal();
        $('.materialboxed').materialbox();
        var $old = "<?= !empty(old()); ?>";
        if ($old || $old != "") {
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

            let $public = '<?= old('public'); ?>' != '' ? '<?= old('public'); ?>' : false;
            if ($public) {
                $data['public'] = $public;
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
        $("#idalbum").remove();
        $("#name").val('');
        $("#active").prop('checked', true);
        $("#public").prop('checked', false);
        $("#duration_frames").val('');
        $("#checkDates").prop('checked', false);
        $('input[name^="days_week"]').each(function() {
            $(this).prop('checked', true);
        });
        changeTypeDate();
        $("#background1").val('');
        $("#background2").val('');
    }

    function addPhotos($data) {
        closeForm();
        $("#idalbum").remove();
        $id = $data['id'];
        $('#modalAdd').modal('open');
        $('<input>').attr({
            type: 'hidden',
            id: 'idalbum',
            name: 'album_id',
            value: $id
        }).appendTo('#formUsers');
    }

    function managerPhotos($data, $photos) {
        closeForm();
        $('#modalList').modal('open');
        $photos = $data['photos'];

        $("#users_form").empty();
        var items = JSON.parse(sessionStorage.getItem('deletePhotos') || '[]');

        $photos.forEach(element => {
            console.log(element);
            var result = contains(items, "photo", element.id)

            if (!result) {
                $element = createRow(element.id, element.path, element.user_id);
                $("#users_form").append($element);
            }
        });

        $('.materialboxed').materialbox();
    }

    function askDeletePhoto(id) {
        $('#modalDeletePhoto').modal('open');
        $("#deleteInputPhoto").val(id);
    }

    function createRow($id, $path, $user_id) {
        $urlAws = "<?= $urlAws ?>";
        $myUser = '<?= $myUser ?>';
        if($myUser && $myUser == $user_id){
            return $(`<tr class='photos' id="rowPhoto${$id}">
                        <td class='imageColumn'>
                            <img class="materialboxed responsive-img" width="80" src="${$urlAws}${$path}"></img>
                        </td>
                        <td>
                            <a class="btn-small red" onclick="askDeletePhotoAlbum(${$id})">
                                <i class="material-icons white-text">
                                    clear
                                </i>
                            </a>
                        </td>
                    </tr>`);
        }

        return $(`<tr class='photos' id="rowPhoto${$id}">
                        <td class='imageColumn'>
                            <img class="materialboxed responsive-img" width="80" src="${$urlAws}${$path}"></img>
                        </td>
                        <td>
                            <a class="btn-small red disabled" >
                                <i class="material-icons white-text">
                                    clear
                                </i>
                            </a>
                        </td>
                    </tr>`);
        
    }


    function askDeletePhotoAlbum(id) {
        $("#deleteInputPhotoAlbum").val(id);
        $('#modalDeletePhotoAlbum').modal('open');
    }

    function contains(arr, key, val) {
        for (var i = 0; i < arr.length; i++) {
            if (arr[i][key] == val) return true;
        }
        return false;
    }

    function deletePhotoAlbum() {
        let id = $("#deleteInputPhotoAlbum").val();

        $("#rowPhoto" + id).hide();
        $("#modalDeletePhotoAlbum").modal("close");

        let $url = "<?= URL::route('delete_album_photo_public') ?>";
        $.ajax({
            type: 'DELETE',
            url: $url,
            data: {
                "id": id
            },
            success: function(data) {
                closeCleanModalALbum(id, data, true);
            },
            error: function(data) {
                closeCleanModalALbum(id, data.responseText, false);
            }
        });
    }

    function closeCleanModalALbum($id, $data, $success) {
        if (!$success) {
            $("#rowPhoto" + $id).show();
            M.toast({
                html: $data
            }, 5000);
        } else {
            var items = JSON.parse(sessionStorage.getItem('deletePhotos') || '[]');
            var result = contains(items, "photo", $id)
            if (!result) {
                items.push({
                    photo: $id
                });
                sessionStorage.setItem('deletePhotos', JSON.stringify(items));
            }
        }

        $("#modalDeletePhotoAlbum").modal("close");
        $("#deleteInputPhotoAlbum").val('');
    }

    function closeModalAlbum() {
        $('#modalDeletePhotoAlbum').modal('close');
    }

    function deleteData() {
        $("#indeterminate").show();
        let id = $("#deleteInput").val();
        let $url = "<?= URL::route('delete_album') ?>";
        $.ajax({
            type: 'DELETE',
            url: $url,
            data: {
                "id": id
            },
            success: function(data) {
                closeCleanModal(id, data, true);
            },
            error: function(data) {
                closeCleanModal(id, data.responseText, false);
            },
            complete: function(data) {
                $("#indeterminate").hide();
            }
        });
    }
</script>