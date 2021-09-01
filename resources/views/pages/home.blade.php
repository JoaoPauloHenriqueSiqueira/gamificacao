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
                        <li id="geraisTab" class="tab col s3"><a class="active" href="#gerais">Configurações</a></li>
                        <li id="enderecoTab" class="tab col s3"><a href="#endereco">Endereço</a></li>
                        <li id="pagamentosTab" class="tab col s3"><a href="#pagamentos">Assinatura</a></li>
                        <li id="ordensPagamentoTab" class="tab col s3"><a href="#ordensPagamento">Pagamentos</a></li>
                    </ul>
                    <div id="gerais" class="col s12">
                        <br>
                        <div class="row">
                            <h5 class="center text-center">Configurações da Tela</h5>
                        </div>
                        <h4 class="indigo-text center"></h4>
                        @if($data['name'] != "")
                        <div class="row">
                            <a target="_blank" href="{{ URL::route('screen',$data['name']) }}" class="btn-large green waves-effect pulse right">Ir para minha tela</a><br>
                        </div>
                        <div class="row">
                            <label class="right red-text">
                                @if(!$card || $card->status != 3)
                                Sua assinatura precisa estar Ativa
                                @endif
                            </label>
                        </div>
                        @endif
                        <form method="POST" action="{{ URL::route('update_company') }}" enctype="multipart/form-data">
                            <div class="row">
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
                                        <h5 class="center">Remover Logo <a class="waves-effect waves-light btn" onclick="askDeletePhoto()"><i class="white-text material-icons">delete</i></a>
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
                                                <span>Background</span>
                                                <input type="file" name="background_default" accept="image/*">
                                            </div>
                                            <div class="file-path-wrapper">
                                                <input class="file-path validate" type="text">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row " id="background_default_load" <?php if (!$data['background_default']) { ?> style="display:none" <?php } ?>>
                                    <h5 class="center">Remover Background <a class="waves-effect waves-light btn" onclick="askDeletePhotoBackground()"><i class="white-text material-icons">delete</i></a>
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
                        <div id="planNotActive">
                            <div class="row">
                                <h5 class="center text-center" id="status">Plano</h5>
                                <p class="center">*A assinatura do plano pode ser cancelada a qualquer momento</p>
                                <div class="col s12">
                                    <div class="card animate center fadeLeft">
                                        <div class="card-content  gradient-45deg-indigo-purple white-text">
                                            <p class="card-stats-title">Mensal</p>
                                            <h4 class="card-stats-number white-text">R$9,99</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="creditCard">
                                <div class="creditCardData">
                                    <div class="row">
                                        <h5 class="center text-center">Cartão de Crédito</h5>
                                        <p class="center">Cartões aceitos: American Express, Aura, Banese Card, Cabal, Diners, Elo, FortBrasil, Grand Card, Hiper, Hipercard, Mais, Mastercard, Personal Cardo, Soro Cred, Vale Card, Up Brasil, Visa e Vólus.</p>
                                    </div>
                                    <div class="row ">
                                        <div class="row">
                                            <div class="input-field col s6">
                                                <input name="cardNumber" id="cardNumberToActive" class="cardNumber" oninput="getBrand()" type="text" class="validate" required>
                                                <label for="cardNumberToActive">Número Cartão</label>
                                            </div>
                                            <div class="input-field col s3">
                                                <input name="expirationMonth" class="expirationMonth" oninput="validMonth()" id="expirationMonthToActive" type="text" class="validate" required>
                                                <label for="expirationMonthToActive">Mês Expiração</label>
                                            </div>
                                            <div class="input-field col s3">
                                                <input name="expirationYear" class="expirationYear" id="expirationYearToActive" type="text" class="validate" required>
                                                <label for="expirationYearToActive">Ano Expiração</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="input-field col s8">
                                                <input name="name" id="nameCardToActive" type="text" class="validate" required>
                                                <label for="nameCardToActive">Nome Cartão</label>
                                            </div>

                                            <div class="input-field col s2">
                                                <input id="cvvToActive" name="cvv" type="text" class="validate" required>
                                                <label for="cvvToActive">CVV</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row optionsUpdate">
                                    <div class='col s12 center' id="numberCard">
                                    </div>
                                    <div class="col s6 center ">
                                        <button class="btn-small waves-effect" onclick="showUpdateCard()">Alterar Cartão</button>
                                    </div>
                                    <div class="col s6 center">
                                        <form method="POST" action="{{ URL::route('delete_plan') }}">
                                            <button class="btn-small waves-effect blue">Cancelar Assinatura</button>
                                        </form>
                                    </div>
                                </div>

                                <div class="row optionSave">
                                    <div class="s12 center">
                                        <button class="btn-small waves-effect pulse" onclick="getCreditCardToken()">Efetuar Assinatura</button>
                                    </div>
                                </div>

                                <div class="row optionUpdateCard">
                                    <div class="s12 center">
                                        <button class="btn-small waves-effect pulse" onclick="getCreditCardToken(true)">Atualizar Assinatura</button>
                                    </div>
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
                                        <input name="postalCode" id="postalCode" type="text" data-characters='8' class="validate" oninput="verifyAddress(this)" value="{{$data['postalCode']}}">
                                        <label for="postalCode">CEP</label>
                                    </div>
                                    <div class="input-field col s5">
                                        <input name="city" id="city" type="text" class="validate" data-characters='2' oninput="verifyAddress(this)" value="{{$data['city']}}">
                                        <label for="city">Cidade</label>
                                    </div>
                                    <div class="input-field col s2">
                                        <input name="state" id="state" type="text" class="validate" data-characters='2' oninput="verifyAddress(this)" value="{{$data['state']}}">
                                        <label for="state">Estado</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s5">
                                        <input name="street" id="street" type="text" class="validate" data-characters='8' oninput="verifyAddress(this)" value="{{$data['street']}}">
                                        <label for="street">Rua</label>
                                    </div>

                                    <div class="input-field col s2">
                                        <input name="number" id="number" type="text" class="validate" data-characters='1' oninput="verifyAddress(this)" value="{{$data['number']}}">
                                        <label for="number">Número</label>
                                    </div>

                                    <div class="input-field col s5">
                                        <input name="district" id="district" type="text" class="validate" data-characters='3' oninput="verifyAddress(this)" value="{{$data['district']}}">
                                        <label for="name">Bairro</label>
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

<div id="modalLoad" class="modal">
    <div class="modal-content gradient-45deg-indigo-purple white-text">
        <h5 class="center  white-text row">Aguarde, estamos processando sua assinatura 😍</h5><br>
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

<div id="modalError" class="modal">
    <div class="modal-content gradient-45deg-purple-deep-orange white-text">
        <h5 class="center  white-text row">Ops, ocorreu um erro</h5><br>
        <div class="row center">
            Tente novamente com outro cartão
        </div>
        <br>

        <div class="row center">
            <input type="hidden">
            <a class="btn-flat  modal-action modal-close pulse">
                <i class="material-icons white-text">
                    done
                </i>
            </a>
        </div>
    </div>
</div>

<!-- Modal Structure -->
<div id="modalDeletePhoto" class="modal">
    <div class="modal-content gradient-45deg-indigo-purple white-text">
        <h4 class="center white-text row">Deletar logo?</h4><br>
        <div class="row center">
            <input type="hidden" id="deleteInputPhoto">
            <a class="btn waves-effect waves-light white-text" onclick="deleteLogo()">
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

<div id="modalDeletePhotoBackground" class="modal">
    <div class="modal-content gradient-45deg-indigo-purple white-text">
        <h4 class="center white-text row">Deletar background?</h4><br>
        <div class="row center">
            <input type="hidden" id="deleteInputPhoto">
            <a class="btn waves-effect waves-light white-text" onclick="deleteBackground()">
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
            <div class="progress" id="loadingBackground" style="display:none">
                <div class="indeterminate"></div>
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
        $("#postalCode").mask("99999-999");
    });
</script>
<script type="text/javascript" src="{{ PagSeguro::getUrl()['javascript'] }}"></script>
<script>
    $(document).ready(function() {
        $("#modalLoad").modal();
        $("#modalError").modal();
        $('ul.tabs').tabs();
        $('.materialboxed').materialbox();

        let chat = <?= $data['chat']; ?>;

        if (chat) {
            $("#chat").prop('checked', true);
        } else {
            $("#chat").prop('checked', false);
        }

        let card = '<?= $card ?>';
        if (card != '') {
            card = JSON.parse(card);
        }
        verifyPayment(card.plan_status);
        verifyAddress(false);
    });

    function verifyAddress(characters) {
        let postalCode = $("#postalCode").val();
        let city = $("#city").val();
        let state = $("#state").val();
        let street = $("#street").val();
        let district = $("#district").val();
        let number = $("#number").val();

        if (!postalCode || !city || !state || !street || !district || !number) {
            $('ul.tabs').tabs('select', 'endereco');
            $("#geraisTab").addClass('disabled');
            $("#pagamentosTab").addClass('disabled');
            $("#ordensPagamentoTab").addClass('disabled');
            if (!characters) {
                M.toast({
                    html: "Complete todos os dados de Endereço de Pagamento"
                }, 5000);
            }
        } else if (!characters) {
            $("#geraisTab").removeClass('disabled');
            $("#pagamentosTab").removeClass('disabled');
            $("#ordensPagamentoTab").removeClass('disabled');
        }
    }

    function getCreateCard() {
        let $brandToActive =  $("#brand").val();

        if(!$brandToActive){
            getBrand();
        }

        $brandToActive =  $("#brand").val();

        return {
            "name": $("#nameCardToActive").val(),
            "cardNumberToActive": $("#cardNumberToActive").val(),
            "expirationMonthToActive": $("#expirationMonthToActive").val(),
            "expirationYearToActive": $("#expirationYearToActive").val(),
            "brandToActive": $brandToActive,
            "cvvToActive": $("#cvvToActive").val()
        };
    }

    function showUpdateCard() {
        $(".optionsUpdate").hide('slow');
        $(".creditCard").show('slow');
        $(".optionUpdateCard").show();
        $(".creditCardData").show();
    }

    function validCard(card) {
        $valid = true;
        if (!card.name) {
            $valid = false;
        }

        return $valid;
    }

    function getCreditCardToken($update) {
        $('#modalLoad').modal('open');
        $hash = PagSeguroDirectPayment.getSenderHash();
        let card = getCreateCard();

        if (!validCard(card)) {
            $('#modalLoad').modal('close');
            M.toast({
                html: "Todos os dados do cartão precisam estar preenchidos"
            }, 5000);
            return;
        }

        PagSeguroDirectPayment.setSessionId('{{ PagSeguro::startSession() }}');
        PagSeguroDirectPayment.createCardToken({
            cardNumber: card.cardNumberToActive, // Número do cartão de crédito
            brand: card.brandToActive, // Bandeira do cartão
            cvv: card.cvvToActive, // CVV do cartão
            expirationMonth: card.expirationMonthToActive, // Mês da expiração do cartão
            expirationYear: card.expirationYearToActive, // Ano da expiração do cartão, é necessário os 4 dígitos.
            success: function(response) {
                card.token = response.card.token;

                let $url = "<?= URL::route('save_credit_card') ?>";

                if ($update) {
                    $url = "<?= URL::route('update_credit_card') ?>";
                }

                $.ajax({
                    type: 'POST',
                    url: $url,
                    data: {
                        hash: $hash,
                        name: card.name,
                        cardNumber: card.cardNumberToActive,
                        brand: card.brandToActive,
                        cvv: card.cvvToActive,
                        expirationMonth: card.expirationMonthToActive,
                        expirationYear: card.expirationYearToActive,
                        token: card.token
                    },
                    success: function(data) {
                        M.toast({
                            html: data
                        }, 5000);
                        updateCreditCard('Plano - Aguardando Aprovação');
                    },
                    error: function(data) {
                        M.toast({
                            html: data.responseText
                        }, 5000);
                    },
                    complete: function() {
                        $('#modalLoad').modal('close');
                    }
                });
            },
            error: function(response) {
                $('#modalLoad').modal('close');
                $('#modalError').modal('open');
            }
        });
    }

    function validMonth() {
        let month = moment();
    }

    function getBrand() {
        $card = $('#cardNumberToActive').val();
        $card = $card.replace(/\s/g, '');

        if ($card.length == 16) {
            $card = $card.slice(0, 6);
            PagSeguroDirectPayment.setSessionId('{{ PagSeguro::startSession() }}');
            PagSeguroDirectPayment.getBrand({
                cardBin: $card,
                success: function(response) {
                    let $brand = response.brand;
                    let $brandName = $brand.name;
                    $('<input>').attr({
                        type: 'hidden',
                        id: 'brand',
                        name: 'brand',
                        value: $brandName
                    }).appendTo('.creditCard');

                    let $hash = PagSeguroDirectPayment.getSenderHash();
                    $('<input>').attr({
                        type: 'hidden',
                        id: 'session',
                        name: 'session',
                        value: $hash
                    }).appendTo('.creditCard');

                    return response;
                },
                error: function(response) {
                    $(`#${$div}`).val('');
                    M.toast({
                        html: "O cartão é inválido"
                    }, 5000);
                },
                complete: function(response) {}
            })
        }
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
        M.toast({
            html: $data
        }, 5000);
        $('#modalDeletePhoto').modal('close');
    }

    function closeCleanPhotoBackgroundModal($data) {
        M.toast({
            html: $data
        }, 5000);
        $('#modalDeletePhotoBackground').modal('close');
    }


    function deleteLogo() {
        $("#loading").show();
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
            },
            complete: function(data) {
                $("#loading").hide();
            },
        });
    }

    function deleteBackground() {
        $("#loadingBackground").show();
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
            },
            complete: function(data) {
                $("#loadingBackground").hide();
            },
        });
    }
</script>