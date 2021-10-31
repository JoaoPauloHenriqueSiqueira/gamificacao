@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Campanhas')

@section('content')


{{-- page styles --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-sidebar.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/app-contacts.css')}}">
@endsection

<div class="content-area content-left">
    <div class="app-wrapper">
        <div class="datatable-search">
            <form action="{{ URL::route('search_public_campaigns') }}">
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
                            <hr>
                            <div class="row center">
                                <div class="switch">
                                    <label>
                                        <?php $list = collect($data['users'])->pluck('id')->toArray(); ?>
                                        <input type="checkbox" onchange="participar({{$data['id']}},this)" {{ in_array(Auth::user()->id,$list) ? 'checked': '' }}>
                                        <span class="lever"></span>
                                        Participar
                                    </label>
                                </div>
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

    function participar($campaign, $input) {
        let $url = "<?= URL::route('delete_my_user_campaign') ?>";

        if ($($input).is(':checked')) {
            $url = "<?= URL::route('add_my_user_campaign') ?>";
        }
        $.ajax({
            type: 'POST',
            url: $url,
            data: {
                campaign_id: $campaign,
            },
            success: function(data) {
                M.toast({
                    html: data
                }, 5000);
            },
            error: function(data) {
                M.toast({
                    html: data.responseText
                }, 5000);
            }
        });
    }

    function clean() {
        $('#form').get(0).setAttribute('method', 'POST');
        $("#id").remove();
        $("#idCampaign").remove();
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
</script>