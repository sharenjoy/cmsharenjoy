<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>@yield('title')</title>

        @section('css')
        <!-- Bootstrap core CSS -->        
        <link rel="stylesheet" href="{{ asset('packages/davzie/cmsharenjoy/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css') }}">
        <link rel="stylesheet" href="{{ asset('packages/davzie/cmsharenjoy/css/font-icons/entypo/css/entypo.css') }}">
        <link rel="stylesheet" href="{{ asset('packages/davzie/cmsharenjoy/css/font-icons/font-awesome/css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('packages/davzie/cmsharenjoy/css/font-icons/entypo/css/animation.css') }}">
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
        <link rel="stylesheet" href="{{ asset('packages/davzie/cmsharenjoy/css/neon.css') }}">
        <link rel="stylesheet" href="{{ asset('packages/davzie/cmsharenjoy/css/custom.css') }}">
        <link rel="stylesheet" href="{{ asset('packages/davzie/cmsharenjoy/css/skins/white.css') }}">
        @show

        <script src="{{ asset('packages/davzie/cmsharenjoy/js/jquery-1.10.2.min.js') }}"></script>

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

    </head>

    <!-- page-body Starts -->
    <body class="page-body skin-white">
        
        <!-- page-container Starts -->
        <div class="page-container">
        <!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->
            
            <!-- sidebar-menu Starts -->
            <div class="sidebar-menu">

                <header class="logo-env">
            
                    <!-- logo -->
                    <div class="logo">
                        <a href="{{ url( $urlSegment ) }}">
                            <!-- <img src="assets/images/logo.png" alt="" /> -->
                            <span>{{ $app_name }}</span>
                        </a>
                    </div>
                    
                    <!-- logo collapse icon -->
                    <div class="sidebar-collapse">
                        <a href="#" class="sidebar-collapse-icon with-animation"><!-- add class "with-animation" if you want sidebar to have animation during expanding/collapsing transition -->
                            <i class="entypo-menu"></i>
                        </a>
                    </div>
                    
                    <!-- open/close menu icon (do not remove if you want to enable menu on mobile devices) -->
                    <div class="sidebar-mobile-menu visible-xs">
                        <a href="#" class="with-animation"><!-- add class "with-animation" to support animation -->
                            <i class="entypo-menu"></i>
                        </a>
                    </div>
                    
                </header>

                @if($menu_items)
                <!-- main-menu Starts -->
                <ul id="main-menu" class="">
                <!-- add class "multiple-expanded" to allow multiple submenus to open -->
                <!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->

                    <!-- Search Bar -->
                    <!-- <li id="search">
                        <form method="get" action="">
                            <input type="text" name="q" class="search-input" placeholder="Search something..."/>
                            <button type="submit">
                                <i class="entypo-search"></i>
                            </button>
                        </form>
                    </li> -->

                    <li class="{{ Request::is( "$urlSegment" ) ? 'active' : '' }}">
                        <a href="{{ url( $urlSegment ) }}">
                            <i class="entypo-gauge"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    
                    @foreach($menu_items as $url => $item)
                        @if( $item['top'] )
                            <li class="{{ Request::is( "$urlSegment/$url*" ) ? 'active' : '' }}">
                                <a href="{{ url( $urlSegment.'/'.$url ) }}">
                                    <i class="entypo-chart-bar"></i>
                                    <span>{{ $item['name'] }}</span>
                                </a>
                            </li>
                        @endif
                    @endforeach

                </ul>
                <!-- main-menu Ends -->
                @endif

            </div>
            <!-- sidebar-menu Ends -->

            
            <!-- main-content Starts -->
            <div class="main-content">
        
                <div class="row">
    
                    <!-- Profile Info and Notifications -->
                    <div class="col-md-6 col-sm-8 clearfix">
                        
                        <ul class="user-info pull-left pull-none-xsm">
                        
                            <!-- Profile Info -->
                            <li class="profile-info dropdown"><!-- add class "pull-right" if you want to place this from right -->
                                
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    @if($user->img != '')
                                        <img src="{{ storage_path( $user->img ) }}" alt="" class="img-circle" />
                                    @else
                                        <img src="{{ asset('packages/davzie/cmsharenjoy/images/thumb-1.png') }}" alt="" class="img-circle" />
                                    @endif
                                    {{ $user->first_name.' '.$user->last_name }}
                                    <b class="caret"></b>
                                </a>
                                
                                <ul class="dropdown-menu">
                                    
                                    <!-- Reverse Caret -->
                                    <li class="caret"></li>
                                    
                                    <!-- Profile sub-links -->
                                    <li>
                                        <a href="#">
                                            <i class="entypo-user"></i>
                                            Edit Profile
                                        </a>
                                    </li>
                                    
                                    <li>
                                        <a href="mailbox.html">
                                            <i class="entypo-mail"></i>
                                            Inbox
                                        </a>
                                    </li>
                                    
                                    <li>
                                        <a href="extra-calendar.html">
                                            <i class="entypo-calendar"></i>
                                            Calendar
                                        </a>
                                    </li>
                                    
                                    <li>
                                        <a href="#">
                                            <i class="entypo-clipboard"></i>
                                            Tasks
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    
                    
                    <!-- Raw Links -->
                    <div class="col-md-6 col-sm-4 clearfix hidden-xs">
                        
                        <ul class="list-inline links-list pull-right">
                            <!-- <li>
                                <a href="#">Live Site</a>
                            </li>
                            
                            <li class="sep"></li>
                            
                            <li>
                                <a href="#" data-toggle="chat" data-animate="1" data-collapse-sidebar="1">
                                    <i class="entypo-chat"></i>
                                    Chat
                                    
                                    <span class="badge badge-success chat-notifications-badge is-hidden">0</span>
                                </a>
                            </li>
                            
                            <li class="sep"></li> -->
                            
                            <li>
                                <a href="{{ url( $urlSegment.'/logout' ) }}">
                                    {{ Lang::get('cmsharenjoy::admin.logout') }} <i class="entypo-logout right"></i>
                                </a>
                            </li>
                        </ul>
                        
                    </div>
                    
                </div>

                <hr />

                <!-- <ol class="breadcrumb bc-3">
                    <li>
                        <a href="index.html"><i class="entypo-home"></i>Home</a>
                    </li>
                    <li>
                        <a href="ui-panels.html">UI Elements</a>
                    </li>
                    <li class="active">
                        <strong>Blockquotes</strong>
                    </li>
                </ol> -->
                    
                <!-- content Starts -->
                @yield('content')
                <!-- content Ends -->

                <!-- footer Starts -->
                <footer class="main">
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            &copy; {{ date('Y') }} <strong>{{ $app_name }}</strong>
                        </div>

                        <div class="col-xs-6 col-sm-6 col-md-6">
                        
                            <ul class="list-inline links-list pull-right">
                                <li>
                                    {{ Form::open( array('method'=>'post', 'id'=>'language-form') ) }}
                                        {{ Form::select(
                                            'language', 
                                            array(
                                                'en' => 'English',
                                                'tw' => '繁體中文'
                                            ), 
                                            $active_language,
                                            array('class'=>'form-control', 'id'=>'language')
                                        )}}
                                    {{ Form::close() }}
                                </li>

                                <!-- <li class="sep"></li>

                                <li>
                                    <a href="{{ url( $urlSegment.'/logout' ) }}">
                                        Log Out <i class="entypo-logout right"></i>
                                    </a>
                                </li> -->
                            </ul>
                            
                        </div>
                    </div>
                    
                </footer>
                <!-- footer Ends -->

            </div>
            <!-- main-content Ends -->

        </div>
        <!-- page-container Ends -->

        @section('scripts')
            <script src="{{ asset('packages/davzie/cmsharenjoy/js/gsap/main-gsap.js') }}"></script>
            <script src="{{ asset('packages/davzie/cmsharenjoy/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js') }}"></script>
            <script src="{{ asset('packages/davzie/cmsharenjoy/js/bootstrap.min.js') }}"></script>
            <script src="{{ asset('packages/davzie/cmsharenjoy/js/joinable.js') }}"></script>
            <script src="{{ asset('packages/davzie/cmsharenjoy/js/resizeable.js') }}"></script>
            <script src="{{ asset('packages/davzie/cmsharenjoy/js/neon-api.js') }}"></script>
            <script src="{{ asset('packages/davzie/cmsharenjoy/js/neon-chat.js') }}"></script>
            <script src="{{ asset('packages/davzie/cmsharenjoy/js/neon-custom.js') }}"></script>
            <script src="{{ asset('packages/davzie/cmsharenjoy/js/custom.js') }}"></script>
        @show
    </body>
</html>