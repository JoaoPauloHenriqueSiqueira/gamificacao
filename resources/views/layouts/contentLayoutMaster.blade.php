{{-- pageConfigs variable pass to Helper's updatePageConfig function to update page configuration  --}}
@isset($pageConfigs)
{!! Helper::updatePageConfig($pageConfigs) !!}
@endisset

<!DOCTYPE html>
@php
// confiData variable layoutClasses array in Helper.php file.
$configData = Helper::applClasses();
@endphp
<!--
Template Name: Materialize - Material Design Admin Template
Author: PixInvent
Website: http://www.pixinvent.com/
Contact: hello@pixinvent.com
Follow: www.twitter.com/pixinvents
Like: www.facebook.com/pixinvents
Purchase: https://themeforest.net/item/materialize-material-design-admin-template/11446068?ref=pixinvent
Renew Support: https://themeforest.net/item/materialize-material-design-admin-template/11446068?ref=pixinvent
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.

-->
<html class="loading" lang="@if(session()->has('locale')){{session()->get('locale')}}@else{{$configData['defaultLanguage']}}@endif" data-textdirection="{{ env('MIX_CONTENT_DIRECTION') === 'rtl' ? 'rtl' : 'ltr' }}">
<!-- BEGIN: Head-->

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title') | Materialize - Material Design Admin Template</title>
  <link rel="apple-touch-icon" href="../../images/favicon/apple-touch-icon-152x152.png">
  <link rel="shortcut icon" type="image/x-icon" href="../../images/favicon/favicon-32x32.png">
  <!-- <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" type="text/css"> -->

 <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css">
 <link href="{{ asset('css/select2-materialize.css') }}" rel="stylesheet" type="text/css">


  <style>

    .modal{
      overflow-x: hidden;   
     }
     
    #modalList {
      max-height: 88%;
      height: 88% !important;
    }

    #modalAdd {
      max-height: 40%;
      height: 40% !important;
    }

    #modal {
      max-height: 70%;
    }
  </style>
  {{-- Include core + vendor Styles --}}
  @include('panels.styles')

</head>
<!-- END: Head-->

{{-- @isset(config('custom.custom.mainLayoutType'))
@endisset --}}
@if(!empty($configData['mainLayoutType']) && isset($configData['mainLayoutType']))
@include(($configData['mainLayoutType'] === 'horizontal-menu') ? 'layouts.horizontalLayoutMaster':
'layouts.verticalLayoutMaster')
@else
{{-- if mainLaoutType is empty or not set then its print below line  --}}
<h1>{{'mainLayoutType Option is empty in config custom.php file.'}}</h1>
@endif

@if ($errors->any())
<div class="alert alert-danger">
  <ul>
    @foreach ($errors->all() as $error)
    <script>
      M.toast({
        html: '{{$error}}'
      }, 5000);
    </script>
    @endforeach
  </ul>
</div>
@endif

@if(session()->has('message'))
<div class="alert alert-success">
  <script>
    M.toast({
      html: '{{ session()->get("message")}}'
    }, 5000);
  </script>
</div>
@endif

<!-- <script src="{{ asset('js/select2.min.js') }}"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
    $("#modalAdd").modal();
    $("#modalList").modal();
    $('#modalDelete').modal();
    $('#modalDeletePhoto').modal();
    $('#modalDeletePhotoAlbum').modal();
    $('#modalDeletePhotoBackground').modal();
    $('#modalPhotos').modal();
    $('#modalList').modal({
      dismissible: false, // Modal can be dismissed by clicking outside of the modal
    });
  });
</script>


<script src="https://js.pusher.com/5.0/pusher.min.js"></script>
<script>
  var pusher = new Pusher('05ebbb87aba66fb09125', {
    cluster: 'us2'
  });
  var company = "<?= session()->get('company') ?>";
  var channel = pusher.subscribe(`paymentEvent.${company}`);

  channel.bind('paymentEvent', function(data) {
    if (company == data.company) {
      verifyPayment(data.status);
    }
  });

  function verifyPayment(status) {
    if (
      status == "CANCELLED_BY_RECEIVER" ||
      status == "CANCELLED_BY_SENDER" ||
      status == "SUSPENDED" ||
      status == "CANCELLED") {
      createCreditCard('Plano - Desativado');
    } else if (status == 'PAYMENT_METHOD_CHANGE') {
      updateCreditCard('Plano - Pagamento pendente. Atualize a forma de pagamento');
    } else if (status == "ACTIVE") {
      updateCreditCard('Plano - Ativo');
    } else if (status == "PENDING") {
      updateCreditCard('Plano - Aguardando Aprovação');
    } else {
      createCreditCard('Plano - Desativado');
    }
  }

  function createCreditCard($status) {
    $(".creditCard").show();
    $(".optionSave").show();
    $(".creditCardData").show();
    $(".optionsUpdate").hide();
    $(".optionUpdateCard").hide();
    $("#status").html($status);
  }

  function updateCreditCard($status, show) {
    if (!show) {
      $(".creditCard").hide();
      $(".optionsUpdate").hide();
      $(".optionUpdateCard").hide();
      $(".optionSave").hide();
      $(".creditCardData").hide();
    } else {
      $(".creditCard").show();
      $(".optionsUpdate").show();
      $(".optionUpdateCard").hide();
      $(".optionSave").hide();
      $(".creditCardData").hide();
    }
    $("#status").html($status);
  }
</script>
<script>
  function openModal($edit) {
    this.clean();
    $("#dates").show();
    $(".contact-overlay").addClass("show");
    $(".contact-compose-sidebar").addClass("show");
    if ($edit) {
      $(".update-contact").show();
      $(".add-contact").hide();
    } else {
      $(".update-contact").hide();
      $(".add-contact").show();
    }
    M.updateTextFields()
  }


  function closeModal() {
    this.clean();
    $('#modalDelete').modal('close');
    $('#modalDeletePhoto').modal('close');
  }

  function editCampaign(validAt, validFrom, data) {
    console.log(data);
    $('#id').remove();
    if (data['id']) {
      openModal(true);
      $('<input>').attr({
        type: 'hidden',
        id: 'id',
        name: 'id',
        value: data['id']
      }).appendTo('#form');
    } else {
      openModal();
    }
    $("#name").val(data['name']);
    $("#duration_frames").val(data['duration_frames']);

    let active = data['active'];
    if (active) {
      $("#active").prop('checked', true);
    } else {
      $("#active").prop('checked', false);
    }

    if (data['is_birthday']) {
      $("#dates").hide();
    } else {
      $("#dates").show();
    }

    let is_continuos = data['is_continuous'];
    if (is_continuos) {
      $("#checkDates").prop('checked', false);
      $('input[name^="days_week"]').each(function() {
        $(this).prop('checked', false);
      });

      if (!Array.isArray(data['days_week']) && data['days_week'] != '' && data['days_week'] != null) {
        data['days_week'] = data['days_week'].split(",");
      }

      if (Array.isArray(data['days_week'])) {
        data['days_week'].forEach(element => {
          $(`#day${element}`).prop('checked', true);
        });
      }

    } else {
      $("#checkDates").prop('checked', true);
      $("#valid_at").val(validAt);
      $("#valid_from").val(validFrom);
    }

    changeTypeDate();
    M.updateTextFields()
  }

  function editUser(birthday, data) {
    $('#id').remove();
    if (data['id']) {
      openModal(true);
      $('<input>').attr({
        type: 'hidden',
        id: 'id',
        name: 'id',
        value: data['id']
      }).appendTo('#form');
    } else {
      openModal();
    }

    $("#name").val(data['name']);
    $("#email").val(data['email']);
    $("#password").val(data['password']);
    $("#birthday").val(birthday);


    let active = data['admin'];
    if (active) {
      $("#admin").prop('checked', true);
    } else {
      $("#admin").prop('checked', false);
    }

    M.updateTextFields()
  }

  function changeTypeDate() {
    if ($("#checkDates").is(":checked")) {
      $("#dates_start_end").show();
      $("#days_week").hide();
    } else {
      $("#days_week").show();
      $("#dates_start_end").hide();
    }
  }

  function closeForm() {
    $(".contact-overlay").removeClass("show");
    $(".contact-compose-sidebar").removeClass("show");
  }

  function askDelete(id) {
    $('#modalDelete').modal('open');
    $("#deleteInput").val(id);
  }

  function closeCleanModal(id, $data, $success) {
    if ($success) {
      $("#" + id).remove();
    }
    M.toast({
      html: $data
    }, 5000);
    $("#modalDelete").modal("close");
    $("#deleteInput").val('');
  }

  function deleteData($url) {
    $("#indeterminate").show();
    let id = $("#deleteInput").val();
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

  function closeCleanPhotoModal($data, $id) {

    if ($id != "") {
      $(`${$id}`).hide();
    }

    M.toast({
      html: $data
    }, 5000);
    $('#modalDeletePhoto').modal('close');
  }

  function deletePhoto($url) {
    console.log($url);
    $("#loading").show();
    id = $("#deleteInputPhoto").val();
    $.ajax({
      type: 'DELETE',
      url: $url,
      data: {
        "id": id
      },
      success: function(data) {
        closeCleanPhotoModal(data, $(`#photo${$id}`));
      },
      error: function(data) {
        closeCleanPhotoModal(data.responseText, '');
      },
      complete: function(data) {
        $("#loading").hide();
      }
    });
  }
</script>

</html>