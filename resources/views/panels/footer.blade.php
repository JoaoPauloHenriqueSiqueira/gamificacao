<!-- BEGIN: Footer-->
<footer
  class="{{$configData['mainFooterClass']}} @if($configData['isFooterFixed']=== true){{'footer-fixed'}}@else {{'footer-static'}} @endif @if($configData['isFooterDark']=== true) {{'footer-dark'}} @elseif($configData['isFooterDark']=== false) {{'footer-light'}} @else {{$configData['mainFooterColor']}} @endif">
  <div class="footer-copyright">
    <div class="container">
      <span>&copy;Todos os direitos reservados
      </span>
      <span class="right hide-on-small-only">
        Desenvolvido e Mantido por <a href="https://inn9.net">Inn9</a>
      </span>
    </div>
  </div>
</footer>

<!-- END: Footer-->