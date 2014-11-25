@extends('cmsharenjoy::layouts.login')

@section('title')
{{pick_trans('app.reset_password')}} - {{$brandName}}
@stop

@section('content')

    {{ Form::open(array( 'url'=>$accessUrl.'/resetpassword' , 'method'=>'POST' , 'role'=>'form' )) }}
                    
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
                {{pick_trans('buttons.reset')}}
            </button>
        </div>

        <div class="form-group">
            <a href="{{url($accessUrl.'/login')}}" class="link">{{pick_trans('app.back_login')}}</a>
        </div>
                     
    {{ Form::close() }}

@stop