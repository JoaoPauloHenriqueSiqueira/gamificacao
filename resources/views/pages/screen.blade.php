{{-- layout --}}
@extends('layouts.fullCopyLayoutMaster')

{{-- page title --}}
@section('title','ExibeTV')

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
        min-height: 100vh;
        flex-direction: column;
        overflow: hidden;
    }

    .msgUser {
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
        width: 5em;
    }

    .carousel {
        margin-top: -5em !important;
    }

    .carousel-item {
        min-width: 25em !important;
        height: 100% !important;
        visibility: hidden !important;
        width: 100% !important;
    }

    .contentMessage {
        font-size: 2em;
    }

    .userImage {
        max-width:6em!important;
        max-height:6em !important;

    }

    .userIcon {
        font-size: 5em;
    }

   

    .carousel-item.active {
        visibility: visible !important;
    }

    .carousel .carousel-item>img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        object-position: unset;
    }

    .name {
        margin: 1em;
        background-color: transparent;
    }

    .page-footer {
        background-color: transparent;
        box-shadow: inset 0 0 0 2000px rgb(0 0 0 / 26%);
    }

    img.avtar {
        width: 4em;
        height: 4em;
        margin-right: .75rem;
    }
  
    .nameAlbumCampaign .letter {
        display: inline-block;
        line-height: 1em;
    }

    .nameAlbumCampaign .text-wrapper {
        position: relative;
        display: inline-block;
        padding-top: 0.1em;
        padding-right: 0.05em;
        padding-bottom: 0.15em;
    }

    .nameAlbumCampaign .line {
        opacity: 0;
        position: absolute;
        left: 0;
        height: 3px;
        width: 100%;
        background-color: #fff;
        transform-origin: 0 0;
    }

    .nameAlbumCampaign .line1 {
        top: 0;
    }

    .nameAlbumCampaign .line2 {
        bottom: 0;
    }
    .nameUser {
        font-size: 1.6em;
    }

    .titleLogo {
        margin-top: -5%;
    }

    .texts{
        color:#fff;
    }

    .nameUser {
        font-size: 1.6em;
        color:#fff;
    }

    .nameAlbumCampaign {
        font-weight: 1000;
        font-size: 4.5em;
        color:#fff;
    }

    @keyframes floatText {
        to {
            transform: translateX(-100%);
        }
    }

    @media screen and (orientation: portrait) {
        .titleLogo {
            margin-top: 15%;
        }

        .nameAlbumCampaign {
            font-size: 1.5em;
        }

        .qrCode {
            width: 100%;
        }
    }

    @media screen and (orientation: landscape) and (max-width: 1800px) {
        .logo {
            max-width: 5em;
        }

        .carousel .carousel-item>img {
            max-width: 80%;
            max-height: 75%;
        }
    }


    @media screen and (orientation: landscape) and (max-width: 1000px) {
        .titleLogo {
            margin-top: -5%;
        }

        .nameAlbumCampaign {
            font-size: 1.5em;
        }

        #dayMounth {
            position: relative;
            font-size: 0.6em;
        }

        #time {
            position: relative;
            top: -1em;
            font-size: 0.6em;
        }

        .carousel .carousel-item>img {
            max-width: 80%;
            max-height: 45%;
            object-fit: cover;
            object-position: unset;
        }

        .titleLogo {
            margin-top: -10%;
        }

        .logo {
            max-width: 10em;
        }
    }

    @media screen and (orientation: landscape) and (max-width: 800px) {
        .titleLogo {
            margin-top: 0;
        }


        .userIcon {
            font-size: 2.5em;
        }

        .contentMessage {
            font-size: 1em;
        }

        .nameUser {
            font-size: 1em;
        }

        .logo {
            max-width: 5em;
            margin-top: 4%;
        }

        .carousel .carousel-item>img {
            max-width: 80%;
            max-height: 30%;
        }
    }

    @media screen and (orientation: portrait) and (max-width: 800px) {
        .titleLogo {
            margin-top: 5%;
        }

        .userIcon {
            font-size: 3em;
        }

        .contentMessage {
            font-size: 0.8em;
        }

        .nameUser {
            font-size: 0.7em;
        }

        .logo {
            max-width: 5em;
            margin-top: -4%;
        }

        #dayMounth {
            font-size: 0.6em;

        }

        #time {
            font-size: 0.8em;
        }

        .carousel .carousel-item>img {
            max-width: 100%;
            max-height: 90%;
        }
    }
</style>

<div class="row titleLogo">
    <div class="col s3">
        <img id="logo" class="logo center">
    </div>
    <div class="col s9">
        <h1 class="nameAlbumCampaign right">
            <span class="text-wrapper">
                <span class="line line1"></span>
                <span class="letters right" id="title"></span>
                <span class="line line2"></span>
            </span>
        </h1>
    </div>
</div>

<div class="carousel " id="carousel">
</div>

@if($company['chat'])
<footer class="page-footer" style="position:fixed;bottom:0;left:0;width:100%;padding-left: 2%;
    padding-right: 2%;">
    <div class="row valign-wrapper">
        <div class="col s2">
            <h3 class="texts"><span id="dayMounth"></span></h3>
            <h5 class="texts"><span id="time"></span>
            </h5>
        </div>

        <div class="col s8">
            <div class="row">
                <div id='greeting'></div>
            </div>
        </div>

        <div class="col s2">
            <img class="qrCode right responsive-img" src="{{$qrCode}}">
        </div>
    </div>
</footer>
@else
<footer class="" style="position:fixed;bottom:0;left:0;width:100%;">
    <div class="row valign-wrapper">
        <div class="col s12 left">
            <h3 class="texts"><span id="dayMounth"></span></h3>
            <h5 class="texts"><span id="time"></span>
            </h5>
        </div>
    </div>
</footer>
@endif

@endsection
<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
<script src="{{ asset('js/moment.js') }}"></script>
<script src="{{ asset('js/moment-with-locales.js') }}"></script>
<script src="{{ asset('js/anime.min.js') }}"></script>
<script src="{{ asset('js/pusher.min.js') }}"></script>


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
                            <div class="carousel-content">
                                <button class="btn pulse name">${element.name}</button>
                            </div>
                            <img class="responsive responsive-img" src="${urlAws}${element.photo}">
                        </a>`
                    );
                    qtdSlides++;
                } else if (element.photo && !element.name) {
                    $("#carousel").append(
                        `<a class="carousel-item center">
                                <img class="responsive responsive-img" src="${urlAws}${element.photo}">
                            </a>`
                    );
                    qtdSlides++;
                } else if (!element.photo && element.name) {
                    $("#carousel").append(
                        `<a class="carousel-item center">
                            <h1 class="center-text text-center center name texts">${element.name}</h1>
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
            var textWrapper = document.querySelector('.nameAlbumCampaign .letters');
            textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");
            anime.timeline({
                    loop: true
                })
                .add({
                    targets: '.nameAlbumCampaign .letter',
                    scale: [0.3, 1],
                    opacity: [0, 1],
                    translateZ: 0,
                    easing: "easeOutExpo",
                    duration: 600,
                    delay: (el, i) => 70 * (i + 1)
                }).add({
                    targets: '.nameAlbumCampaign .line',
                    scaleX: [0, 1],
                    opacity: [0.5, 1],
                    easing: "easeOutExpo",
                    duration: 700,
                    offset: '-=875',
                    delay: (el, i, l) => 80 * (l - i)
                }).add({
                    targets: '.nameAlbumCampaign',
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
                h + ":" + m;

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
                    <div class="col s2 media-image online pr-0 ">
                        <div class="row">
                            <img src="${urlAws}${user.photo}" alt="" class="circle z-depth-2 responsive-img userImage right">
                        </div>
                    </div>
                    <div class="col s9">
                        <p class="nameUser m-0">${user.name}:</p>
                        <div class="m-0 chat-text msgUser"><div class="contentMessage">${msg.text}</div></div>
                    </div>
                    `;
            } else if (user.hasOwnProperty('name')) {
                return `
                    <div class="col s3 media-image online pr-0">
                        <i class="material-icons circle userIcon circle z-depth-2 responsive-img right">account_circle</i>
                    </div>
                    <div class="col s9">
                        <p class="nameUser m-0">${user.name}:</p>
                        <div class="m-0 chat-text msgUser"><div class="contentMessage">${msg.text}</div></div>
                    </div>
                    `;
            } else {
                return `
                    <div class="col s3 media-image online pr-0">
                        <i class="material-icons circle userIcon circle z-depth-2 responsive-img right">account_circle</i>
                    </div>
                    <div class="col s9">
                        <p class="nameUser m-0">Usuário:</p>
                        <div class="m-0 chat-text msgUser"><div class="contentMessage">${msg.text}</div></div>
                    </div>
                    `;
            }
        }

        function drawMessage() {

            let messages = JSON.parse(sessionStorage.getItem('messages') || "[]");

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

                    sessionStorage.setItem('msg', mensagem['id']);
                    $("#greeting").html(mountMesssage(mensagem['user'], mensagem));
                }
            }

           setTimeout(drawMessage, 20000);
        }
    });
</script>