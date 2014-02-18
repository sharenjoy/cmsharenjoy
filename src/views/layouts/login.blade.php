<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="Neon Admin Panel" />
        <meta name="author" content="" />
        
        @section('css')
        <link rel="stylesheet" href="{{ asset('packages/davzie/cmsharenjoy/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css') }}">
        <link rel="stylesheet" href="{{ asset('packages/davzie/cmsharenjoy/css/font-icons/entypo/css/entypo.css') }}">
        <link rel="stylesheet" href="{{ asset('packages/davzie/cmsharenjoy/css/font-icons/entypo/css/animation.css') }}">
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
        <link rel="stylesheet" href="{{ asset('packages/davzie/cmsharenjoy/css/neon.css') }}">
        <link rel="stylesheet" href="{{ asset('packages/davzie/cmsharenjoy/css/custom.css') }}">

        <script src="{{ asset('packages/davzie/cmsharenjoy/js/jquery-1.10.2.min.js') }}"></script>
        @show

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <title>@yield('title')</title>
        
    </head>
    <body class="page-body login-page login-form-fall">

        <div class="login-container">
            
            <div class="login-header login-caret">
                
                <div class="login-content">
                    
                    <a href="#" class="logo">
                        <img src="{{ asset('packages/davzie/cmsharenjoy/images/logo.png') }}" alt="" />
                    </a>
                    
                    <p class="description">Dear user, log in to access the admin area!</p>
                    
                    <!-- progress bar indicator -->
                    <div class="login-progressbar-indicator">
                        <h3>43%</h3>
                        <span>logging in...</span>
                    </div>
                </div>
                
            </div>
            
            <div class="login-progressbar">
                <div></div>
            </div>
            
            <div class="login-form">
                
                <div class="login-content">
                    
                    {{ Form::open(array( 'url'=>$urlSegment.'/login' , 'method'=>'POST' , 'role'=>'form' )) }}
                    <!-- {{ Form::open(array( 'url'=>$urlSegment.'/login' , 'method'=>'POST' , 'id'=>'form_login' , 'role'=>'form' )) }} -->

                        @include('cmsharenjoy::partials.messaging')
                        
                        <div class="form-group">
                    
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="entypo-user"></i>
                                </div>
                                
                                {{ Form::text('email', Input::old('email') , array( 'placeholder'=>'Your Email Address' , 'class'=>'form-control' ) ) }}
                            </div>
                            
                        </div>

                        <div class="form-group">
                    
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="entypo-key"></i>
                                </div>
                                
                                {{ Form::password('password', array( 'placeholder'=>'Your Password' , 'class'=>'form-control' ) ) }}
                                
                            </div>
                        </div>
                        
                        <div class="form-group">

                            <button type="submit" class="btn btn-primary btn-block btn-login">
                                Login In 
                                <i class="entypo-login"></i>
                            </button>

                        </div>

                    {{ Form::close() }}
                    
                    
                    <div class="login-bottom-links">
                        
                        <a href="#" class="link">Forgot your password?</a>
                        
                        <br />
                        
                        <a href="#">ToS</a>  - <a href="#">Privacy Policy</a>
                        
                    </div>
                    
                </div>
                
            </div>
            
        </div>

        @section('scripts')
        <!-- Bottom Scripts -->
        <script src="{{ asset('packages/davzie/cmsharenjoy/js/gsap/main-gsap.js') }}"></script>
        <script src="{{ asset('packages/davzie/cmsharenjoy/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js') }}"></script>
        <script src="{{ asset('packages/davzie/cmsharenjoy/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('packages/davzie/cmsharenjoy/js/joinable.js') }}"></script>
        <script src="{{ asset('packages/davzie/cmsharenjoy/js/resizeable.js') }}"></script>
        <script src="{{ asset('packages/davzie/cmsharenjoy/js/neon-api.js') }}"></script>
        <script src="{{ asset('packages/davzie/cmsharenjoy/js/jquery.validate.min.js') }}"></script>
        <script src="{{ asset('packages/davzie/cmsharenjoy/js/neon-login.js') }}"></script>
        <script src="{{ asset('packages/davzie/cmsharenjoy/js/neon-custom.js') }}"></script>
        @show

    </body>
</html>