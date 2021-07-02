{{-- extend layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Home')
<style>
    .center .materialboxed,
    .center-align .materialboxed .responsive-img {
        margin: 0 auto;
        position: relative !important;
    }
</style>
{{-- page content --}}
@section('content')
<div class="container">
    <div class="row">
        <div class="col s12">
            <div class="container">
                <div class="section">
                    <!-- card stats start -->
                    <div id="card-stats" class="pt-0">
                        <div class="row">
                            <div class="col s12 m4 l4">
                                <div class="card animate fadeLeft">
                                    <div class="card-content cyan white-text">
                                        <p class="card-stats-title"><i class="material-icons">person_outline</i>Usuários</p>
                                        <h4 class="card-stats-number white-text">{{ $metrics['users'] }}</h4>
                                    </div>

                                </div>
                            </div>
                            <div class="col s12 m4 l4">
                                <div class="card animate fadeLeft">
                                    <div class="card-content red accent-2 white-text">
                                        <p class="card-stats-title"><i class="material-icons">attach_money</i>Campanhas (ativas)</p>
                                        <h4 class="card-stats-number white-text">{{ $metrics['campaigns'] }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col s12 m4 l4">
                                <div class="card animate fadeRight">
                                    <div class="card-content orange lighten-1 white-text">
                                        <p class="card-stats-title"><i class="material-icons">trending_up</i>Álbum (ativos)</p>
                                        <h4 class="card-stats-number white-text">{{ $metrics['albums'] }}</h4>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="content-overlay"></div>
            </div>
        </div>
    </div>

    <div class="col xl12 m12 s12">
        <div class="card invoice-action-wrapper mb-10">
            <div class="card-content">
                <div class="row">
                    <ul class="tabs">
                        <li class="tab col s3"><a class="active" href="#gerais">Configurações</a></li>
                        <li class="tab col s3"><a href="#pagamentos">Assinatura</a></li>
                        <li class="tab col s3"><a href="#endereco">Endereço</a></li>
                        <li class="tab col s3"><a href="#ordensPagamento">Pagamentos</a></li>
                    </ul>
                    <div id="gerais" class="col s12">
                        <br>
                        <div class="row">
                            <h5 class="center text-center">Configurações da Tela</h5>
                        </div>
                        <h4 class="indigo-text center"></h4>
                        @if($data['name'] != "")
                        <div class="row">
                            <a target="_blank" href="{{ URL::route('screen',$data['name']) }}" class="btn-large green waves-effect pulse right">Ir para minha tela</a>
                        </div>
                        @endif
                        <div class="row">
                            <form method="POST" action="{{ URL::route('update_company') }}" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="input-field col s12">
                                        <input name="name" id="name" type="text" class="validate" value="{{$data['name']}}">
                                        <label for="name">Nome Empresa
                                            @if($data['name'] == "")
                                            - Cadastre esse campo para ter acesso a sua tela de campanhas e álbuns
                                            @endif
                                        </label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <p>
                                            <label>
                                                <input type="checkbox" name="chat" id="chat" checked>
                                                <span>Ativar chat na tela</span>
                                            </label>
                                        </p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <input name="password_default" id="password_default" type="text" class="validate" value="{{$data['password_default']}}">
                                        <label for="password_default">Senha Padrão (Usuários)</label>
                                    </div>
                                </div>

                                <div class="row" id="logo" <?php if ($data['logo']) { ?> style="display:none" <?php } ?>>
                                    <div class="file-field input-field">
                                        <div class="btn blue">
                                            <span>Logo</span>
                                            <input type="file" name="logo" accept="image/*">
                                        </div>
                                        <div class="file-path-wrapper">
                                            <input class="file-path validate" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="section">
                                    <div class="row " id="logo_load" <?php if (!$data['logo']) { ?> style="display:none" <?php } ?>>
                                        <h5 class="center">Logo atual <a class="waves-effect waves-light btn" onclick="askDeletePhoto()"><i class="white-text material-icons">delete</i></a>
                                        </h5>
                                        <div class="col s12 m4 center">
                                            @if($data['logo'])
                                            <img class="materialboxed responsive-img center" width="550" src="<?= $urlAws . $data['logo'] ?>" />
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="section">
                                    <div class="row" id="background_default" <?php if ($data['background_default']) { ?> style="display:none" <?php } ?>>
                                        <div class="file-field input-field">
                                            <div class="btn blue">
                                                <span>Fundo Padrão</span>
                                                <input type="file" name="background_default" accept="image/*">
                                            </div>
                                            <div class="file-path-wrapper">
                                                <input class="file-path validate" type="text">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row " id="background_default_load" <?php if (!$data['background_default']) { ?> style="display:none" <?php } ?>>
                                    <h5 class="center">Fundo Padrão atual <a class="waves-effect waves-light btn" onclick="askDeletePhotoBackground()"><i class="white-text material-icons">delete</i></a>
                                    </h5>
                                    <div class="col s12 m4 center">
                                        @if($data['background_default'])
                                        <img class="materialboxed responsive-img" width="550" src="<?= $urlAws . $data['background_default'] ?>" />
                                        @endif

                                    </div>
                                </div>
                        </div>

                        <div class="row">
                            <div class="s12 center">
                                <button class="btn-small waves-effect" type="submit">Salvar</button>
                            </div>
                        </div>
                        </form>
                    </div>

                    <div id="pagamentos" class="col s12">
                        <br>
                        <div id="planNotActive" class="">
                            <div class="row">
                                <h5 class="center text-center">Plano (desativado)</h5>
                                <p class="center">*A assinatura do plano pode ser cancelada a qualquer momento</p>
                                <div class="col s12">
                                    <div class="card animate center fadeLeft">
                                        <div class="card-content  gradient-45deg-indigo-purple white-text">
                                            <p class="card-stats-title">Mensal</p>
                                            <h4 class="card-stats-number white-text">R$30,00</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <h5 class="center text-center">Cartão de Crédito</h5>
                            </div>
                            <div class="row">
                                <form method="POST" action="{{ URL::route('update_credit_card') }}">
                                    <div class="row">
                                        <div class="input-field col s8">
                                            <input name="cardNumber" id="cardNumberToActive" class="cardNumber" oninput="getBrand('cardNumberToActive')" type="text" class="validate" value="{{$card['postalCode']}}">
                                            <label for="cardNumberToActive">Número Cartão</label>
                                        </div>
                                        <div class="input-field col s2">
                                            <input name="expirationMonth" class="expirationMonth" oninput="validMonth()" id="expirationMonth" type="text" class="validate" value="{{$card['expirationMonth']}}">
                                            <label for="expirationMonth">Mês Expiração</label>
                                        </div>
                                        <div class="input-field col s2">
                                            <input name="expirationYear" class="expirationYear" id="expirationYear" type="text" class="validate" value="{{$card['expirationYear']}}">
                                            <label for="expirationYear">Ano Expiração</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col s8">
                                            <input name="name" id="nameCard" type="text" class="validate" value="{{$card['name']}}">
                                            <label for="nameCard">Nome Cartão</label>
                                        </div>

                                        <div class="input-field col s2">
                                            <input id="cvv" name="cvv" type="text" class="validate" value="{{$card['cvv']}}">
                                            <label for="cvv">CVV</label>
                                        </div>

                                        <input name="brand" type="hidden" class="brand validate" value="{{$card['brand']}}">
                                        <input name="brand" type="hidden" class="session">

                                    </div>
                                    <div class="row">
                                        <div class="s12 center">
                                            <button class="btn-small waves-effect pulse" type="submit">Efetuar Assinatura</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div id="planActive" class="hide">
                            <div class="row">
                                <h5 class="center text-center">Plano (ativo)</h5>

                                <div class="col s12">
                                    <div class="card animate center fadeLeft">
                                        <div class="card-content  gradient-45deg-indigo-purple white-text">
                                            <p class="card-stats-title">Mensal</p>
                                            <h4 class="card-stats-number white-text">R$30,00</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>

                            <div class="row center">
                                <div class="col s6 ">
                                    <button class="btn-small waves-effect">Alterar Cartão</button>
                                </div>
                                <div class="col s6 ">
                                    <button class="btn-small waves-effect blue">Cancelar Assinatura</button>
                                </div>
                            </div>

                            <div class="updateCreditCard hide">
                                <div class="row">
                                    <h5 class="center text-center">Cartão de Crédito</h5>
                                </div>
                                <div class="row">
                                    <form method="POST" action="{{ URL::route('update_credit_card') }}">
                                        <div class="row">
                                            <div class="input-field col s8">
                                                <input name="cardNumber" id="cardNumber" class="cardNumber" type="text" class="validate" value="{{$card['postalCode']}}">
                                                <label for="cardNumber">Número Cartão</label>
                                            </div>
                                            <div class="input-field col s2">
                                                <input name="expirationMonth" class="expirationMonth" id="expirationMonth" type="text" class="validate" value="{{$card['expirationMonth']}}">
                                                <label for="expirationMonth">Mês Expiração</label>
                                            </div>
                                            <div class="input-field col s2">
                                                <input name="expirationYear" class="expirationYear" id="expirationYear" type="text" class="validate" value="{{$card['expirationYear']}}">
                                                <label for="expirationYear">Ano Expiração</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-field col s8">
                                                <input name="name" id="nameCard" type="text" class="validate" value="{{$card['name']}}">
                                                <label for="nameCard">Nome Cartão</label>
                                            </div>

                                            <div class="input-field col s2">
                                                <input id="cvv" name="cvv" type="text" class="validate" value="{{$card['cvv']}}">
                                                <label for="cvv">CVV</label>
                                            </div>

                                            <input name="brand" type="hidden" class="brand validate" value="{{$card['brand']}}">
                                            <input name="brand" type="hidden" class="session">
                                        </div>
                                        <div class="row">
                                            <div class="s12 center">
                                                <button class="btn-small waves-effect pulse" type="submit">Efetuar Assinatura</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="endereco" class="col s12">
                        <br>
                        <div class="row">
                            <h5 class="center text-center">Endereço de Pagamento</h5>
                        </div>
                        <br>
                        <div class="row">
                            <form method="POST" action="{{ URL::route('update_company') }}">
                                <div class="row">
                                    <div class="input-field col s5">
                                        <input name="postalCode" id="postalCode" type="text" class="validate" value="{{$data['postalCode']}}">
                                        <label for="postalCode">CEP</label>
                                    </div>
                                    <div class="input-field col s5">
                                        <input name="city" id="city" type="text" class="validate" value="{{$data['city']}}">
                                        <label for="city">Cidade</label>
                                    </div>
                                    <div class="input-field col s2">
                                        <input name="state" id="state" type="text" class="validate" value="{{$data['state']}}">
                                        <label for="state">Estado</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s5">
                                        <input name="street" id="street" type="text" class="validate" value="{{$data['street']}}">
                                        <label for="street">Rua</label>
                                    </div>

                                    <div class="input-field col s5">
                                        <input name="district" id="district" type="text" class="validate" value="{{$data['district']}}">
                                        <label for="name">Bairro</label>
                                    </div>

                                    <div class="input-field col s2">
                                        <input name="number" id="number" type="text" class="validate" value="{{$data['number']}}">
                                        <label for="number">Número</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="s12 center">
                                        <button class="btn-small waves-effect" type="submit">Salvar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>


                    <div id="ordensPagamento" class="col s12">
                        <br>
                        <div class="row">
                            <h5 class="center text-center">Ordens de Pagamento</h5>
                        </div>
                        <div class="row">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal Structure -->
<div id="modalDeletePhoto" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h4 class="center red-text row">Deletar foto?</h4><br>
        <div class="row center">
            <input type="hidden" id="deleteInputPhoto">
            <a class="btn-flat " onclick="deleteLogo()">
                <i class="material-icons blue-text">
                    done
                </i>
            </a>
            <a class="btn-flat " onclick="closeModal()">
                <i class="material-icons red-text">
                    close
                </i>
            </a>
        </div>
        <br>
        <div class="row center">
            <div class=" preloader-wrapper big active center" style="display:none;" id="indeterminate">
                <div class="spinner-layer spinner-blue-only">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="gap-patch">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Structure -->
<div id="modalDeletePhotoBackground" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h4 class="center red-text row">Deletar foto?</h4><br>
        <div class="row center">
            <input type="hidden" id="deleteInputPhoto">
            <a class="btn-flat " onclick="deleteBackground()">
                <i class="material-icons blue-text">
                    done
                </i>
            </a>
            <a class="btn-flat " onclick="closeModal()">
                <i class="material-icons red-text">
                    close
                </i>
            </a>
        </div>
        <br>
        <div class="row center">
            <div class=" preloader-wrapper big active center" style="display:none;" id="indeterminateBackground">
                <div class="spinner-layer spinner-blue-only">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="gap-patch">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
<script src="{{ asset('js/moment.js') }}"></script>
<script src="{{ asset('js/moment-with-locales.js') }}"></script>

<script>
    $(document).ready(function($) {
        $(".cardNumber").mask("9999 9999 9999 9999");
        $(".expirationMonth").mask("99");
        $(".expirationYear").mask("9999");
        $(".cvv").mask("999");
    });
</script>
<script type="text/javascript" src="{{ PagSeguro::getUrl()['javascript'] }}"></script>
<script>
    $(document).ready(function() {
        $('.materialboxed').materialbox();

        let chat = <?= $data['chat']; ?>;

        if (chat) {
            $("#chat").prop('checked', true);
        } else {
            $("#chat").prop('checked', false);
        }
    });

    function validMonth() {
        let month = moment();
    }

    function getBrand($div) {
        $card = $(`#${$div}`).val();
        if ($card.length > 6) {
            PagSeguroDirectPayment.setSessionId('{{ PagSeguro::startSession() }}');


            PagSeguroDirectPayment.getBrand({
                cardBin: $card.replace(/\s/g, ''),
                success: function(response) {
                    let brand = response.brand;
                    let brandName = brand.name;
                    $(".brand").val(brandName);
                    $(".session").val(PagSeguroDirectPayment.getSenderHash());

                    return response;
                },
                error: function(response) {
                    $(`#${$div}`).val($card);
                    M.toast({
                        html: "O cartão é inválido"
                    }, 5000);
                    //tratamento do erro
                },
                complete: function(response) {
                    //tratamento comum para todas chamadas
                }
            })
        }
    }

    function clicou() {
        PagSeguroDirectPayment.setSessionId('{{ PagSeguro::startSession() }}'); //PagSeguroRecorrente tem um método identico, use o que preferir neste caso, não tem diferença.
        // console.log(PagSeguroDirectPayment.getSenderHash());
        PagSeguroDirectPayment.getPaymentMethods({
            amount: 20.00,
            success: function(response) {
                // Retorna os meios de pagamento disponíveis.
                console.log(response);
            },
            error: function(response) {
                // Callback para chamadas que falharam.
                M.toast({
                    html: "O cartão é inválido"
                }, 5000);
            },
            complete: function(response) {
                // Callback para todas chamadas.
            }
        });
    }

    function askDeletePhoto() {
        $('#modalDeletePhoto').modal('open');
    }

    function askDeletePhotoBackground() {
        $('#modalDeletePhotoBackground').modal('open');
    }


    function closeModal() {
        $('#modalDeletePhoto').modal('close');
        $('#modalDeletePhotoBackground').modal('close');
    }

    function closeCleanPhotoModal($data) {
        $("#indeterminate").hide();
        M.toast({
            html: $data
        }, 5000);
        $('#modalDeletePhoto').modal('close');
    }

    function closeCleanPhotoBackgroundModal($data) {
        $("#indeterminateBackground").hide();
        M.toast({
            html: $data
        }, 5000);
        $('#modalDeletePhotoBackground').modal('close');
    }


    function deleteLogo() {
        $("#indeterminate").show();
        let $url = "<?= URL::route('delete_logo') ?>";
        $.ajax({
            type: 'DELETE',
            url: $url,
            success: function(data) {
                $("#logo").show();
                $("#logo_load").hide();
                closeCleanPhotoModal(data);

            },
            error: function(data) {
                closeCleanPhotoModal(data.responseText);
            }
        });
    }

    function deleteBackground() {
        $("#indeterminateBackground").show();
        let $url = "<?= URL::route('delete_background') ?>";
        $.ajax({
            type: 'DELETE',
            url: $url,
            success: function(data) {
                $("#background_default").show();
                $("#background_default_load").hide();
                closeCleanPhotoBackgroundModal(data);
            },
            error: function(data) {
                closeCleanPhotoBackgroundModal(data.responseText);
            }
        });
    }
</script>