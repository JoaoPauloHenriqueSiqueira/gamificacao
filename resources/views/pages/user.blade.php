@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Usuários')

@section('content')

{{-- page styles --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-sidebar.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-contacts.css')}}">
@endsection

<!-- Add new contact popup -->
<div style="bottom: 54px; right: 19px;" class="fixed-action-btn direction-top">
    <a class="btn-floating btn-large primary-text gradient-shadow contact-sidebar-trigger" onclick="openModal(false)" href="#modal1">
        <i class="material-icons">add</i>
    </a>
</div>
<!-- Add new contact popup Ends-->

<div class="content-area content-left">
    <div class="app-wrapper">
        <div class="datatable-search">
            <form action="{{ URL::route('search_users') }}">
                <i class="material-icons mr-2 search-icon">search</i>
                <input type="text" placeholder="Procurar (Pressione enter para procurar)" class="app-filter" id="global_filter" name="search_name" value="{{Arr::get($search,'search_name')}}">
            </form>
        </div>
        <div id="button-trigger" class="card card card-default scrollspy border-radius-6 fixed-width">
            <div class="card-content p-0">
                <ul class="collapsible collection" data-collapsible="accordion">
                    @if(count($datas) == 0)
                    <li class="center">Não há usuários cadastrados</li>
                    @endif
                    @foreach ($datas as $data)
                    <li id="{{$data->id}}">
                        <div class="collapsible-header">
                            {{$data->name}}
                        </div>
                        <div class="collapsible-body white">
                            <div class="row ">
                                <span class="span-body">
                                    <span class="green-text">{{ $data->admin ==  "1" ? 'Admin': 'Usuário comum' }}</span>
                                </span>
                            </div></br>
                            <div class="row ">
                                <span class="span-body">
                                    <span class="green-text">Email:</span>
                                    {{ $data->email ==  "" ? '-' : $data->email }}
                                </span>
                            </div></br>
                            <div class="row ">
                                <span class="span-body">
                                    <span class="green-text">Nascimento:</span>
                                    {{ $data->birthday ==  "" ? '-' : $data->birthday }}
                                </span>
                            </div></br>
                            <div class="row ">
                                <span class="span-body">
                                    <div class="row" id="photo{{$data->id}}" <?php if (!$data['photo']) { ?> style="display:none" <?php } ?>>
                                        <h5 class="center">Remover Foto <a class="waves-effect waves-light btn" onclick="askDeletePhoto({{$data->id}})"><i class="white-text material-icons">delete</i></a>
                                        </h5>
                                        <div class="col s12 m4 center">
                                            <img class="materialboxed responsive-img" width="550" src="<?= $urlAws . $data['photo'] ?>" />
                                        </div>
                                    </div>

                                </span>
                            </div></br>
                            <hr>
                            <div class="row center">
                                <a class="btn-small tooltipped" onclick="editUser('{{ $data->birthday_date }}',{{$data}})" data-position='left' data-delay='50' data-tooltip="Editar Usuário">
                                    <i class="material-icons white-text">
                                        edit
                                    </i>
                                </a>
                                <a class="btn-small tooltipped red" onclick="askDelete({{$data->id}})" data-position='bottom' data-delay='50' data-tooltip="Deletar Usuário">
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

<!-- Modal Structure -->
<div id="modalDeletePhoto" class="modal">
    <div class="modal-content gradient-45deg-indigo-purple white-text">
        <h4 class="center white-text row">Deletar foto?</h4><br>
        <div class="row center">
            <input type="hidden" id="deleteInputPhoto">
            <a class="btn waves-effect waves-light white-text" onclick="deletePhoto('<?= URL::route('delete_user_photo') ?>')">
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
        <h4 class="center white-text">Deletar Usuário?</h4>
        <div class="row center">
            <input type="hidden" id="deleteInput">
            <a class="btn waves-effect waves-light white-text " onclick="deleteData('{{ URL::route('delete_user') }}')">
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
    <form class="edit-contact-item mb-5 mt-5" method="POST" action="{{ URL::route('make_user') }}" id="form" enctype="multipart/form-data">
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
                        <i class="material-icons prefix"> perm_identity </i>
                        <input id="name" name="name" type="text" class="validate">
                        <label for="name">Nome</label>
                    </div>
                    <div class="input-field col s12">
                        <p>
                            <label>
                                <input type="checkbox" name="admin" id="admin">
                                <span>Administrador</span>
                            </label>
                        </p>
                    </div>
                    <div class="input-field col s12">
                        <i class="material-icons prefix"> email </i>
                        <input id="email" name="email" type="email" class="validate">
                        <label for="email">Email</label>
                    </div>
                    <div class="input-field col s12" id="password_row">
                        <i class="material-icons prefix"> lock </i>
                        <input id="password" name="password" type="text" class="validate">
                        <label for="password">Senha Padrão</label>
                    </div>
                    <div class="input-field col s12">
                        <input id="birthday" name="birthday" type="date" class="validate">
                        <label for="birthday">Data de nascimento</label>
                    </div>
                    <div class="input-field col s12">
                        <div class="row">
                            <div class="file-field input-field">
                                <div class="btn">
                                    <span>Foto</span>
                                    <input type="file" name="photo" accept="image/*" id="foto1">
                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text" id="foto2">
                                </div>
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
                <!-- form start end-->
            </div>
        </div>
    </form>
</div>
</div>
@if( method_exists($datas,'links') )
<h1 class="center">{{$datas->links('vendor.pagination.materializecss')}}</h1>
@endif

@endsection
<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>

<script>
    $(document).ready(function() {
        "use strict";
        $(".modal").modal();
        $('.materialboxed').materialbox();

        var $old = "<?= !empty(old()); ?>";
        if ($old) {
            $("#old").val(1);
            formOldUser();
        }

        if ($old != "") {
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

            let $id = '<?= old('id'); ?>' != '' ? '<?= old('id'); ?>' : '';
            if ($id) {
                $data['id'] = $id;
            }

            let $name = '<?= old('name'); ?>' != '' ? '<?= old('name'); ?>' : '';
            if ($name) {
                $data['name'] = $name;
            }

            $data['admin'] = '<?= old('admin'); ?>' != '' ? true : false;

            $data['email'] = '<?= old('email'); ?>' != '' ? '<?= old('email'); ?>' : '';
            
            $data['password']= '<?= old('password'); ?>' != '' ? '<?= old('password'); ?>' : '';

            let $birthday = '<?= old('birthday'); ?>' != '' ? '<?= old('birthday'); ?>' : '';
            if ($birthday != '') {
                $data['birthday'] = $birthday;
            }

            this.editUser($birthday, $data);
        }
    }

    function clean() {
        $('#form').get(0).setAttribute('method', 'POST');
        $('#id').remove();
        $("#idUser").remove();
        $("#name").val('');
        $("#email").val('');
        $("#birthday").val('');
        $("#foto1").val('');
        $("#foto2").val('');
        $("#password").val('');
        $("#admin").prop('checked', false);
    }

    var contactOverlay = $(".contact-overlay"),
        updatecontact = $(".update-contact"),
        addcontact = $(".add-contact"),
        contactComposeSidebar = $(".contact-compose-sidebar"),
        password = "<?= $password_default ?>",
        $old = "<?= old('name') ?>";


    function askDeletePhoto(id) {
        $('#modalDeletePhoto').modal('open');
        $("#deleteInputPhoto").val(id);
    }

</script>