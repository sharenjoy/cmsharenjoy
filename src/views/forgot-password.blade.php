@extends('cmsharenjoy::layouts.login')

@section('title')
    Forgot Password
@stop

@section('content')

    {{ Form::open(array( 'url'=>$urlSegment.'/forgotpassword' , 'method'=>'POST' , 'role'=>'form' )) }}
                    
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
            <button type="submit" class="btn btn-primary btn-block btn-login">
                <i class="entypo-login"></i>
                Continue
            </button>
        </div>

        <div class="form-group">
            <a href="{{url($urlSegment.'/login')}}" class="link">Back to login in.</a>
        </div>
                     
    {{ Form::close() }}

@stop