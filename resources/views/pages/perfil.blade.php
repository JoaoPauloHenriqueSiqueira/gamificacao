@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Minha conta')

@section('content')

{{-- page styles --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-sidebar.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-contacts.css')}}">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
@endsection

<div class="content-area content-left">
  <div class="app-wrapper">
    <div id="button-trigger" class="card card card-default scrollspy border-radius-6 fixed-width">
      <div class="card-content p-0">
        <ul class="collapsible collection" data-collapsible="accordion">

          <li id="{{$data->id}}" class="active">
            <div class="collapsible-header">
              {{$data->name}}
            </div>
            <div class="collapsible-body white">
              <div class="row ">
                <span class="span-body">
                  <span class="green-text">{{ $data->admin ==  "1" ? 'Admin': 'Usuário' }}</span>
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
              @if ($data['photo'])
              <div class="row ">
                <span class="span-body">
                  <div class="row" id="photo{{$data->id}}">
                    <h5 class="center">Remover Foto <a class="waves-effect waves-light btn" onclick="askDeletePhoto({{$data->id}})"><i class="white-text material-icons">delete</i></a>
                    </h5>
                    <div class="col s12 m4 center">
                      <img class="materialboxed responsive-img" id="imageLoaded" width="550" src="<?= $urlAws . $data['photo'] ?>" />
                    </div>
                  </div>
                </span>
              </div></br>
              @endif
              <hr>
              <div class="row center">
                <a class="btn-small tooltipped" onclick="editUser('{{ $data->birthday_date }}',{{$data}})" data-position='left' data-delay='50' data-tooltip="Editar Minha Conta">
                  <i class="material-icons white-text">
                    edit
                  </i>
                </a>

                <a class="btn-small tooltipped blue" data-position='right' data-delay='50' data-tooltip="Editar Foto Perfil" onclick="open_file()">
                  <i class="material-icons white-text">
                    face
                  </i>
                </a>
                <input type="file" name="image" class="image" hidden>

              </div>
            </div>
          </li>
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

<div id="modalLoad" class="modal">
    <div class="modal-content gradient-45deg-indigo-purple white-text">
        <h5 class="center  white-text row">Enviando sua foto</h5><br>
        <br>
        <div class="row center">
            <img class="media-responsive center" src="https://thumbs.gfycat.com/ColdMiniatureDorking-max-1mb.gif">
        </div>
        <div class="row center">
            <div class="progress">
                <div class="indeterminate"></div>
            </div>
        </div>
    </div>
</div>

<!--  Contact sidebar -->
<div class="contact-compose-sidebar">
  <form class="edit-contact-item mb-5 mt-5" method="POST" action="{{ URL::route('update_my_user') }}" id="form" enctype="multipart/form-data">
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
            <input id="name" name="name" type="text" class="validate">
            <label for="name">Nome</label>
          </div>
          <div class="input-field col s12">
            <input id="email" name="email" type="email" class="validate">
            <label for="email">Email</label>
          </div>
          <div class="input-field col s12" id="password_row">
            <input id="password" name="password" type="text" class="validate">
            <label for="password">Senha</label>
          </div>
          <div class="input-field col s12">
            <input id="birthday" name="birthday" type="date" class="validate">
            <label for="birthday">Data de nascimento</label>
          </div>
          <!-- <div class="input-field col s12">
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
                    </div> -->
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


<div id="modalPerfil" class="modal modal-fixed-footer">
  <div class="modal-content" id="modalPerfilContent">
    <img id="image">
  </div>
  <div class="modal-footer">
    <button class="waves-effect waves-green btn-flat" onclick="enviarFoto()">Salvar</button>
    <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Fechar</a>
  </div>
</div>

@endsection
<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

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

    $('#modalPerfil').modal({
      dismissible: false, // Modal can be dismissed by clicking outside of the modal
      onCloseEnd: function() { // Callback for Modal close
        cropper.destroy();
        cropper = null;
        var imagem = document.getElementById('image');
        imagem.src = false;
      }
    });

  });

  function enviarFoto() {
    $('#modalLoad').modal('open');

    canvas = cropper.getCroppedCanvas({
      width: 980
    });

    canvas.toBlob(function(blob) {
      url = URL.createObjectURL(blob);
      var reader = new FileReader();
      reader.readAsDataURL(blob);
      reader.onloadend = function() {
        var base64data = reader.result;
        let $url = "<?= URL::route('update_my_picture') ?>";
        $.ajax({
          type: "POST",
          dataType: "json",
          url: $url,
          data: {
            '_token': $('meta[name="_token"]').attr('content'),
            'photo': base64data
          },
          complete: function(data,canvas) {
            $('#modalLoad').modal('close');
            closeModalMessage(data.responseText, "modalPerfil");
            window.location.href = window.location.href;
          }
        });
      }
    });
  }

  function open_file() {
    $(".image").click();
  }

  var image = document.getElementById('image');
  var cropper;

  $(document).on('change', '.image', function(e) {
    var imagem = document.getElementById('image');
    var files = e.target.files;
    var done = function(url) {
      imagem.src = url;
      cropper = new Cropper(imagem, {
        aspectRatio: 1,
        viewMode: 3
      });
      $('#modalPerfil').modal('open');
    };

    var reader;
    var file;
    var url;

    if (files && files.length > 0) {
      file = files[0];

      if (URL) {
        done(URL.createObjectURL(file));
      } else if (FileReader) {
        reader = new FileReader();
        reader.onload = function(e) {
          done(reader.result);
        };
        reader.readAsDataURL(file);
      }
    }
  });

  $(".image").on("change", ".image", function(e) {
    var files = e.target.files;
    var done = function(url) {
      image.src = url;

      $('#modalPerfil').modal('open');
    };
    var reader;
    var file;
    var url;

    if (files && files.length > 0) {
      file = files[0];

      if (URL) {
        done(URL.createObjectURL(file));
      } else if (FileReader) {
        reader = new FileReader();
        reader.onload = function(e) {
          done(reader.result);
        };
        reader.readAsDataURL(file);
      }
    }
  });

  function formOldUser() {
    if ($("#old").val() != 1) {
      this.clean();
    } else {
      $("#old").val(0);

      let $data = [];

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

      $data['password'] = '<?= old('password'); ?>' != '' ? '<?= old('password'); ?>' : '';

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
    $old = "<?= old('name') ?>";


  function askDeletePhoto(id) {
    $('#modalDeletePhoto').modal('open');
    $("#deleteInputPhoto").val(id);
  }
</script>