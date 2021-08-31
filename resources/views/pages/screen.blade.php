{{-- layout --}}
@extends('layouts.fullCopyLayoutMaster')

{{-- page title --}}
@section('title','Tela')

{{-- page content --}}
@section('content')
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Antonio:wght@700&display=swap" rel="stylesheet">
<style>
    body,
    html {
        margin: 0px;
        height: 100%;
        width: 100%;
    }

    * {
        font-family: 'Antonio', sans-serif;
    }


    .responsive {
        width: 100%;
        height: auto;
    }

    body {
        height: 100%;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        animation-duration: 4s;
        /* box-shadow: inset 0 0 0 2000px rgb(0 0 0 / 26%); */
        min-height: 100vh;
        flex-direction: column;
        overflow: hidden;
    }

    .truncateContent {
        margin: 0 auto;
        position: relative;
        display: block;
        width: 100%;
        word-wrap: break-word;
    }


    .logo {
        width: 15em;
        height: auto;
    }

    .qrCode {
        width: 15em;
    }

    .carousel {
        margin-top: -5em !important;
        min-height: 900px;
    }

    .carousel-item {
        min-width: 25em !important;
        height: 100% !important;
        visibility: hidden !important;
    }

    .contentMessage {
        font-size: 2em;
    }

    .userImage {
        max-width: 8em !important;
    }

    .nameUser {
        font-size: 1.6em;
    }

    .carousel-item.active {
        visibility: visible !important;
    }

    .carousel .carousel-item>img {
        width: 210%;
        margin: -50%;
        margin-top: -12%;
        max-width: 550px;
    }

    .name {
        background: rgba(0, 0, 0, 0.5);
        padding: 10px;
        margin: 0;
    }

    .page-footer {
        background-color: transparent;
        box-shadow: inset 0 0 0 2000px rgb(0 0 0 / 56%);
        max-height: 15em;
    }

    img.avtar {
        width: 4em;
        height: 4em;
        margin-right: .75rem;
    }

    .ml1 {
        font-weight: 1000;
        font-size: 4.5em;
    }

    .ml1 .letter {
        display: inline-block;
        line-height: 1em;
    }

    .ml1 .text-wrapper {
        position: relative;
        display: inline-block;
        padding-top: 0.1em;
        padding-right: 0.05em;
        padding-bottom: 0.15em;
    }

    .ml1 .line {
        opacity: 0;
        position: absolute;
        left: 0;
        height: 3px;
        width: 100%;
        background-color: #fff;
        transform-origin: 0 0;
    }

    .ml1 .line1 {
        top: 0;
    }

    .ml1 .line2 {
        bottom: 0;
    }


    @keyframes floatText {
        to {
            transform: translateX(-100%);
        }
    }


    @media screen and (max-width: 2500px) {

        .contentMessage {
            font-size: 1.6em;
        }

        .userImage {
            max-width: 5em !important;
        }

        .carousel .carousel-item>img {
            max-width: 400px;
            margin-top: 10%;
        }

        .ml1 {
            font-weight: 1000;
            font-size: 3.5em;
        }
    }

    @media screen and (max-width: 1787px) {
        .page-footer {
            max-height: 12em;
        }

        .qrCode {
            width: 6em;
        }

        .contentMessage {
            font-size: 0.6em;
        }
    }


    /* TODO  media queries*/
    @media screen and (max-height: 787px) {
        .carousel .carousel-item>img {
            margin-top: 0;
            margin-left: 0;
            max-width: 250px;
        }

        .page-footer {
            max-height: 5em;
        }

        .qrCode {
            width: 3em;
        }
    }

    @media screen and (max-width: 600px) {

        .titleLogo {
            margin-top: 15%;
        }

        .ml1 {
            font-size: 1.5em;
        }

        .carousel .carousel-item>img {
            margin-top: 0;
            margin-left: 0;
            max-width: 250px;
        }

        #dayMounth {
            font-size: 0.5em;
        }

        #time {
            font-size: 0.8em;
        }

        .truncateContent {
            width: 13em;
        }

        .contentMessage {
            font-size: 0.6em;
        }

        .nameUser {
            font-size: 0.6em;
        }

        .qrCode {
            margin-left: 100%;
            margin-left: -3em;
        }

        .userImage {}
    }

    @media screen and (max-width: 480px) {
        .truncateContent {
            width: 10em;
        }
    }
</style>

<div class="row titleLogo">
    <div class="col s3">
        <img id="logo" class="logo center">
    </div>
    <div class="col s9">
        <h1 class="ml1 right">
            <span class="text-wrapper">
                <span class="line line1"></span>
                <span class="letters right title white-text" id="title"></span>
                <span class="line line2"></span>
            </span>
        </h1>
    </div>
</div>

<div class="carousel" id="carousel">
</div>

@if($company['chat'])
<footer class="page-footer" style="position:fixed;bottom:0;left:0;width:100%;">
    <div class="row valign-wrapper">
        <div class="col s2">
            <h3 class="white-text"><span id="dayMounth"></span></h3>
            <h5 class="white-text"><span id="time"></span>
            </h5>
        </div>

        <div class="col s8">
            <div class="row valign-wrapper">
                <div id='greeting'></div>
            </div>
        </div>


        <div class="col s2">
            <img class="qrCode" src="{{$qrCode}}">
        </div>
    </div>

    <div class="footer-copyright">
    </div>
</footer>
@else
<footer class="" style="position:fixed;bottom:0;left:0;width:100%;">
    <div class="row valign-wrapper">
        <div class="col s12 left">
            <h3 class="white-text"><span id="dayMounth"></span></h3>
            <h5 class="white-text"><span id="time"></span>
            </h5>
        </div>
    </div>

    <div class="footer-copyright">
    </div>
</footer>
@endif

@endsection
<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
<script src="{{ asset('js/moment.js') }}"></script>
<script src="{{ asset('js/moment-with-locales.js') }}"></script>
<script src="{{ asset('js/anime.min.js') }}"></script>
<script src="{{ asset('js/pusher.min.js') }}"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/2.0.2/anime.min.js"></script> -->
<!-- <script src="https://js.pusher.com/5.0/pusher.min.js"></script> -->


<script>
    $(document).ready(function() {
        animateTitle();
        var urlAws = "<?= $urlAws ?>";
        var company = <?= $company ?>;

        var pusher = new Pusher('05ebbb87aba66fb09125', {
            cluster: 'us2'
        });

        let id = company.id;
        var channel = pusher.subscribe(`screenEvent.${id}`);
        channel.bind('screenEvent', function(data) {
            if (id == data.company) {
                location.reload();
            }
        });

        var channelScrap = pusher.subscribe(`scrapEvent.${id}`);
        channelScrap.bind('scrapEvent', function(data) {
            if (id == data.company) {
                getMessages(id);
            }
        });

        startTime();
        changeLogo()
        runCampaigns(true);
        getMessages(id);
        drawMessage();

        /**
         * Verifica se página já foi recarregada no dia
         * se não foi, recarrega 
         * @return void
         */
        function reloadPage() {
            let date = sessionStorage.getItem('date');
            var today = new Date();

            if (date == null) {
                sessionStorage.setItem('date', today.getDay() + "/" + today.getMonth());
            } else if (date != today.getDay() + "/" + today.getMonth()) {
                sessionStorage.removeItem('date');
                window.location.href = window.location.href;
            }
        }

        function getMessages(id) {
            let $url = "<?= URL::route('list_messages') ?>";
            $.ajax({
                type: 'GET',
                url: $url,
                data: {
                    "id": id
                },
                success: function(data) {
                    sessionStorage.setItem("messages", JSON.stringify(data));
                }
            });
        }

        function runCampaigns(start) {
            reloadPage();

            if (start) {
                sessionStorage.removeItem('campaign')
            }

            let campaigns = <?= $campaigns ?>;
            console.log(campaigns);

            let campaign = sessionStorage.getItem('campaign')
            let invalids = sessionStorage.getItem('invalid_campaigns');

            // if ((campaigns.length == 1 || !campaign)) {
            if ((campaigns.length == 1)) {
                campaign = campaigns[0];
                sessionStorage.setItem('campaign', campaign['id']);
                drawCampaign(campaign, company, urlAws);
                drawCarousel(campaign);
                return;
            }

            if (Array.isArray(campaigns) && campaigns.length > 0) {
                let index = campaigns.findIndex(x => x.id == campaign);
                if (index >= campaigns.length - 1) {
                    sessionStorage.setItem('campaign', false);
                    return runCampaigns();
                }

                index += 1;
                campaign = campaigns[index];
                sessionStorage.setItem('campaign', campaign['id']);
                drawCampaign(campaign, company, urlAws);
                drawCarousel(campaign);
            }
        }

        function drawCampaign(campaign, company, urlAws) {
            if (campaign.background) {
                url = urlAws + campaign.background
            } else {
                url = urlAws + company.background_default
            }
            changeBackground(url);
            changeTitle(campaign.name);
        }

        // function verifyValidCampaign(campaign) {
        //     let today = moment(new Date(), "MM-DD-YYYY");

        //     if (campaign['is_continuous']) {
        //         if (campaign.days_week.includes(`${today.day()}`)) {
        //             return true;
        //         }
        //         return false;
        //     } else {
        //         let start = moment(campaign['valid_at'], "MM-DD-YYYY");
        //         let end = moment(campaign['valid_from'], "MM-DD-YYYY")

        //         if (today.isSameOrAfter(start) && today.isSameOrBefore(end)) {
        //             return true;
        //         }

        //         return false;
        //     }

        // }

        function drawCarousel(campaign) {
            $("#carousel").empty();
            let timeCampaign = 0;
            let qtdSlides = 0;

            campaign.slides.forEach(element => {
                if (element.name && element.photo) {
                    $("#carousel").append(
                        `<a class="carousel-item center">
                                <h6 class="center-text text-center center name white-text">${element.name}</h6>
                                <img class="responsive" src="${urlAws}${element.photo}">
                                </a>`
                    );
                    qtdSlides++;
                } else if (element.photo && !element.name) {
                    $("#carousel").append(
                        `<a class="carousel-item center">
                                <img class="responsive" src="${urlAws}${element.photo}">
                            </a>`
                    );
                    qtdSlides++;
                } else if (!element.photo && element.name) {
                    $("#carousel").append(
                        `<a class="carousel-item center">
                            <h1 class="center-text text-center center name white-text">${element.name}</h1>
                        </a>`
                    );
                    qtdSlides++;
                }
            });

            timeCampaign = (campaign.duration_frames * qtdSlides);
            $('.carousel').carousel();

            autoplay(campaign.duration_frames * 1000, qtdSlides);
            cronomCampaign(timeCampaign);
        }

        function cronomCampaign(timeCampaign) {
            var timeleft = timeCampaign;
            var downloadTimer = setInterval(function() {
                if (timeleft <= 0) {
                    clearInterval(downloadTimer);
                    runCampaigns();
                }
                timeleft -= 1;
            }, 1000);
        }

        function animateTitle() {
            var textWrapper = document.querySelector('.ml1 .letters');
            textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");
            anime.timeline({
                    loop: true
                })
                .add({
                    targets: '.ml1 .letter',
                    scale: [0.3, 1],
                    opacity: [0, 1],
                    translateZ: 0,
                    easing: "easeOutExpo",
                    duration: 600,
                    delay: (el, i) => 70 * (i + 1)
                }).add({
                    targets: '.ml1 .line',
                    scaleX: [0, 1],
                    opacity: [0.5, 1],
                    easing: "easeOutExpo",
                    duration: 700,
                    offset: '-=875',
                    delay: (el, i, l) => 80 * (l - i)
                }).add({
                    targets: '.ml1',
                    opacity: 0,
                    duration: 1000,
                    easing: "easeOutExpo",
                    delay: 1000
                });
        }

        function startTime() {
            var today = new Date();
            var h = today.getHours();
            var m = today.getMinutes();
            var s = today.getSeconds();
            m = checkTime(m);
            s = checkTime(s);
            document.getElementById('time').innerHTML =
                h + ":" + m + ":" + s;

            var monName = new Array("Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez")
            var day = today.getDate();
            if (day < 10) {
                day = "0" + day;
            }
            dayMounth.textContent = day + "/" + monName[today.getMonth()];
            var t = setTimeout(startTime, 1000);
        }

        function checkTime(i) {
            if (i < 10) {
                i = "0" + i
            };
            return i;
        }

        function autoplay(time, qtdSlides) {
            var today = new Date();
            var h = today.getHours();
            var m = today.getMinutes();
            var s = today.getSeconds();
            m = checkTime(m);
            s = checkTime(s);

            let qtd = sessionStorage.getItem('qtd_slides');
            if (qtd == null) {
                qtd = parseInt(qtdSlides);
                sessionStorage.setItem('qtd_slides', qtd);
            } else {
                qtd = parseInt(qtdSlides) - 1;
                sessionStorage.setItem('qtd_slides', qtd);
            }

            if (qtd >= 0) {
                $('.carousel').carousel('next');
                setTimeout(autoplay, time, time, qtd);
            }
        }

        function changeBackground($background) {
            if ($background) {
                $('body').css('background-image', `url("${$background}")`);
            }
        }

        function changeTitle($title) {
            if ($title) {
                $("#title").removeAttr('style');
                $("#title").html($title);
            }
        }

        function changeLogo() {
            let logo = company.logo;
            if (logo) {
                $("#logo").attr("src", urlAws + logo);
            }
        }

        function mountMesssage(user, msg) {
            if (user.hasOwnProperty('photo')) {
                return `
                    <div class="col media-image online pr-0">
                        <img src="${urlAws}${user.photo}" alt="" class="userImage circle z-depth-2 responsive-img">
                    </div>
                    <div class="col">
                        <p class="nameUser m-0 blue-grey-text text-darken-4 font-weight-700">${user.name}</p>
                        <div class="m-0 chat-text truncateContent"><div class="contentMessage">${msg.text}</div></div>
                    </div>
                    `;
            } else if (user.hasOwnProperty('name')) {
                return `
                    <div class="col media-image online pr-0">
                        <i class="material-icons circle userImage circle z-depth-2 responsive-img">account_circle</i>
                    </div>
                    <div class="col">
                        <p class="nameUser m-0 blue-grey-text text-darken-4 font-weight-700">${user.name}</p>
                        <div class="m-0 chat-text truncateContent"><div class="contentMessage">${msg.text}</div></div>
                    </div>
                    `;
            } else {
                return `
                    <div class="col media-image online pr-0">
                        <i class="material-icons circle userImage circle z-depth-2 responsive-img">account_circle</i>
                    </div>
                    <div class="col">
                        <div class="m-0 chat-text truncateContent"><div class="contentMessage">${msg.text}</div></div>
                    </div>
                    `;
            }
        }

        function drawMessage() {

            let messages = JSON.parse(sessionStorage.getItem('messages') || "[]");
            console.log(messages);

            let msg = sessionStorage.getItem('msg')
            let dateNow = moment(new Date(), "MM-DD-YYYY").format('L');

            let result = false;

            if (messages !== "undefined" && Array.isArray(messages) && messages.length > 0) {

                result = messages.filter(d => {
                    var time = d.created_at;
                    return (moment(dateNow, "MM-DD-YYYY").isSame(moment(time, "MM-DD-YYYY")));
                });

            }

            if (result !== "undefined" && Array.isArray(result) && result.length > 0) {
                if (!msg) {
                    let mensagem = result[0];
                    $("#greeting").html(mountMesssage(mensagem['user'], mensagem));
                    sessionStorage.setItem('msg', mensagem['id']);
                } else {
                    let index = result.findIndex(x => x.id == msg);

                    if (index >= result.length - 1) {
                        sessionStorage.setItem('msg', false);
                        return drawMessage();
                    }

                    index += 1;
                    let mensagem = result[index];
                    console.log(mensagem);

                    sessionStorage.setItem('msg', mensagem['id']);
                    $("#greeting").html(mountMesssage(mensagem['user'], mensagem));
                }
            }

            setTimeout(drawMessage, 10000);
        }
    });
</script>