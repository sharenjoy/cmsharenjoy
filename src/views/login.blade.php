@extends('cmsharenjoy::layouts.login')

@section('title')
{{trans('cmsharenjoy::admin.login')}} - {{$brandName}}
@stop

@section('content')

    {{ Form::open(array( 'url'=>$urlSegment.'/login' , 'method'=>'POST' , 'role'=>'form' )) }}
                    
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
                
                {{ Form::password('password', array( 'placeholder'=>'Your Password' , 'class'=>'form-control' , 'autocomplete'=>'off' ) ) }}
                
            </div>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block btn-login">
                <i class="entypo-login"></i>
                {{trans('cmsharenjoy::admin.login')}}
            </button>
        </div>

        <div class="form-group">
            <a href="{{url($urlSegment.'/forgotpassword')}}">{{trans('cmsharenjoy::admin.forgot_password')}} ?</a>
        </div>
        
        <!-- Implemented in v1.1.4 -->              
        
        <!-- 
        <div class="form-group">
            <em>- or -</em>
        </div>
        
        <div class="form-group">
        
            <button type="button" class="btn btn-default btn-lg btn-block btn-icon icon-left facebook-button">
                Login with Facebook
                <i class="entypo-facebook"></i>
            </button>
            
        </div>
        
        
        
        You can also use other social network buttons
        <div class="form-group">
        
            <button type="button" class="btn btn-default btn-lg btn-block btn-icon icon-left twitter-button">
                Login with Twitter
                <i class="entypo-twitter"></i>
            </button>
            
        </div>
        
        <div class="form-group">
        
            <button type="button" class="btn btn-default btn-lg btn-block btn-icon icon-left google-button">
                Login with Google+
                <i class="entypo-gplus"></i>
            </button>
            
        </div> -->              
    {{ Form::close() }}

@stop