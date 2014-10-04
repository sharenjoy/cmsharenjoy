@extends('cmsharenjoy::layouts.login')

@section('title')
{{trans('cmsharenjoy::app.reset_password')}} - {{$brandName}}
@stop

@section('content')

    {{ Form::open(array( 'url'=>$urlSegment.'/resetpassword' , 'method'=>'POST' , 'role'=>'form' )) }}
                    
        @include('cmsharenjoy::partials.messaging')

        <div class="form-group">
            
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="entypo-key"></i>
                </div>
                
                {{Form::text('email', '', array('placeholder'=>'Your email address.', 'class'=>'form-control'))}}
            </div>
            
        </div>

        <div class="form-group">
            
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="entypo-key"></i>
                </div>
                
                {{ Form::password('password' , array( 'placeholder'=>'This is new password you want to reset.' , 'class'=>'form-control' ) ) }}
            </div>
            
        </div>

        <div class="form-group">
            
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="entypo-key"></i>
                </div>
                
                {{ Form::password('password_confirmation' , array( 'placeholder'=>'To confirm new password.' , 'class'=>'form-control' ) ) }}
            </div>
            
        </div>
        
        <div class="form-group">
            {{Form::hidden('code', $code)}}
            <button type="submit" class="btn btn-primary btn-block btn-login">
                <i class="entypo-login"></i>
                {{trans('cmsharenjoy::buttons.reset')}}
            </button>
        </div>

        <div class="form-group">
            <a href="{{url($urlSegment.'/login')}}" class="link">{{trans('cmsharenjoy::app.back_login')}}</a>
        </div>
                     
    {{ Form::close() }}

@stop