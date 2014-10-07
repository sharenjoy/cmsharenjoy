@extends('cmsharenjoy::layouts.login')

@section('title')
{{pick_trans('app.forgot_password')}} - {{$brandName}}
@stop

@section('content')

    {{Form::open(array('url'=>$urlSegment.'/remindpassword', 'method'=>'POST', 'role'=>'form'))}}
                    
        @include('cmsharenjoy::partials.messaging')

        <div class="form-group">
            
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="entypo-user"></i>
                </div>
                
                {{Form::text('email', Input::old('email'), array('placeholder'=>'Your Email Address', 'class'=>'form-control'))}}
            </div>
            
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block btn-login">
                <i class="entypo-login"></i>
                {{pick_trans('buttons.continue')}}
            </button>
        </div>

        <div class="form-group">
            <a href="{{url($urlSegment.'/login')}}" class="link">{{pick_trans('app.back_login')}}</a>
        </div>
                     
    {{Form::close()}}

@stop