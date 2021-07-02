<div class="navbar @if(($configData['isNavbarFixed'])=== true){{'navbar-fixed'}} @endif">
    <nav class="{{$configData['navbarMainClass']}} @if($configData['isNavbarDark']=== true) {{'navbar-dark'}} @elseif($configData['isNavbarDark']=== false) {{'navbar-light'}} @elseif(!empty($configData['navbarBgColor'])) {{$configData['navbarBgColor']}} @else {{$configData['navbarMainColor']}} @endif">
        <div class="nav-wrapper">
            <ul class="navbar-list right">
                <li class="dropdown-language">
                    <a class="waves-effect waves-block waves-light translation-button" href="#" data-target="translation-dropdown">
                        <span class="flag-icon flag-icon-gb"></span>
                    </a>
                </li>
                <li>
                    <a class="waves-effect waves-block waves-light profile-button" href="javascript:void(0);" data-target="profile-dropdown">
                        Sair
                    </a>
                </li>
            </ul>
            <!-- translation-button-->
            <ul class="dropdown-content" id="translation-dropdown">
                <li class="dropdown-item">
                    <a class="grey-text text-darken-1" href="{{url('lang/pt')}}" data-language="pt">
                        <i class="flag-icon flag-icon-br"></i>
                        Português
                    </a>
                </li>
                <li class="dropdown-item">
                    <a class="grey-text text-darken-1" href="{{url('lang/en')}}" data-language="en">
                        <i class="flag-icon flag-icon-us"></i>
                        English
                    </a>
                </li>

            </ul>

            <!-- profile-dropdown-->
            <ul class="dropdown-content" id="profile-dropdown">
                <li>
                    <a class="grey-text text-darken-1" href="{{ URL::route('logout') }}">
                        <i class="material-icons">keyboard_tab</i>
                        Logout
                    </a>
                </li>
            </ul>
        </div>
        <nav class="display-none search-sm">
            <div class="nav-wrapper">
                <form id="navbarForm">
                    <div class="input-field search-input-sm">
                        <input class="search-box-sm mb-0" type="search" required="" placeholder='Explore Materialize' id="search" data-search="template-list">
                        <label class="label-icon" for="search">
                            <i class="material-icons search-sm-icon">search</i>
                        </label>
                        <i class="material-icons search-sm-close">close</i>
                        <ul class="search-list collection search-list-sm display-none"></ul>
                    </div>
                </form>
            </div>
        </nav>
    </nav>
</div>