<header class="main-header">
    <!-- Logo -->
    <a href="{{url('dashboard')}}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>{{ Session::get('site_short_name') }}</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg">{{ Session::get('company_name') }}</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <?php
        if(!empty(Auth::user()->picture)) {
          $v = Auth::user()->picture; 
        }

        $flag = Session::get('dflt_lang').'.png';
      ?>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">


          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span><img class="flags"  src='{{url("public/img/flags/$flag")}}' ></span>
            </a>
            <ul class="dropdown-menu task-bar">
              <li class="header">Select Language</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menua">
                  <li>
                    <a href="#" class="lang" id='ar' >
                      <img src="{{url('public/img/flags/ar.png')}}" class="img"> Arabic
                    </a>
                  </li>
                  <li>
                    <a href="#" class="lang" id='ch' >
                      <img src="{{url('public/img/flags/ch.png')}}" class="img"> Chinese
                    </a>
                  </li>
                  <li>
                    <a href="#" class="lang" id='en' >
                      <img src="{{url('public/img/flags/en.png')}}" class="img"> English
                    </a>
                  </li>
                  <li>
                    <a href="#" class="lang" id='fr' >
                      <img src="{{url('public/img/flags/fr.png')}}" class="img"> French
                    </a>
                  </li>

                  <li>
                    <a href="#" class="lang" id='po' >
                      <img src="{{url('public/img/flags/po.png')}}" class="img"> Portuguese
                    </a>
                  </li>
                  <li>
                    <a href="#" class="lang" id='rs' >
                      <img src="{{url('public/img/flags/rs.png')}}" class="img"> Russian
                    </a>
                  </li>
                  <li>
                    <a href="#" class="lang" id='sp' >
                      <img src="{{url('public/img/flags/sp.png')}}" class="img"> Spanish
                    </a>
                  </li>

                  <li>
                    <a href="#" class="lang" id='tu' >
                      <img src="{{url('public/img/flags/tu.png')}}" class="img"> Turkish
                    </a>
                  </li>
                  
                </ul>
              </li>
            </ul>
          </li>


          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            @if(!empty($v))
              <img src='{{url("public/uploads/userPic/$v")}}' class="user-image" alt="User Image">
            @else
              <img src='{{url("public/uploads/userPic/avatar.jpg")}}' class="user-image" alt="User Image">
            @endif 
              <span class="hidden-xs">{{ Auth::user()->real_name }}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
              @if(!empty($v))
                <img src='{{url("public/uploads/userPic/$v")}}' class="img-circle" alt="User Image">
            @else
                <img src='{{url("public/uploads/userPic/avatar.jpg")}}' class="img-circle" alt="User Image">
            @endif 
                <p>
                  {{ Session::get('name') }}
                  <!-- <small>Member since Nov. 2012</small> -->
                </p>
              </li>
              <!-- Menu Body -->
              
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  {{--<a href="{{ url('profile') }}" class="btn btn-default btn-flat" id="profile">{{ trans('message.sidebar.profile') }}</a>--}}
                  <a href="{{ url('profile') }}" class="btn btn-default btn-flat" id="profile">{{ trans('message.sidebar.profile') }}</a>
                </div>
                <div class="pull-right">
                  <a href="{{ url('/logout') }}" class="btn btn-default btn-flat">{{ trans('message.form.sign_out') }}</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
