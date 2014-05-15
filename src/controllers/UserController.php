<?php namespace Sharenjoy\Cmsharenjoy\Controllers;

use Sharenjoy\Cmsharenjoy\User\UserInterface;
use Sentry, Mail, Config, Message, Redirect, Str;

class UserController extends ObjectBaseController {

    protected $appName = 'user';

    protected $functionRules = [
        'list'   => true,
        'create' => true,
        'update' => true,
        'delete' => true,
        'resetpassword' => true,
    ];

    protected $listConfig = [
        'user_name'    => ['name'=>'userName' ,    'align'=>'',       'width'=>''   ],
        'phone'        => ['name'=>'cellphone',    'align'=>'',       'width'=>''   ],
        'email'        => ['name'=>'email',        'align'=>'',       'width'=>''   ],
        'created_at'   => ['name'=>'created',      'align'=>'center', 'width'=>'20%'],
    ];

    public function __construct(UserInterface $user)
    {
        $this->repository = $user;
        parent::__construct();
    }

    protected function controllerFinalProcess($model = null)
    {
        $model = $model ?: $this->repository->getModel();
        
        switch ($this->onAction) {
            case 'get-index':
                foreach ($model as $key => $value)
                {
                    $model[$key]->user_name = $value->account->first_name.' '.$value->account->last_name;
                    $model[$key]->phone     = $value->account->phone;
                }
                break;
            case 'get-update':
                $model->first_name  = $model->account->first_name;
                $model->last_name   = $model->account->last_name;
                $model->phone       = $model->account->phone;
                $model->description = $model->account->description;
                break;
            
            default:
                break;
        }

        return $model;
    }

    public function getResetpassword($id)
    {
        try
        {
            $user = Sentry::findUserById($id);

            $password = Str::random(8);
            $user->password = $password;

            if ( ! $user->save())
            {
                Message::merge(array('errors' => trans('cmsharenjoy::admin.some_wrong')))->flash();
                return Redirect::to($this->urlSegment.'/login');
            }

            // Get the password reset code
            $resetCode = $user->getResetPasswordCode();

            $userName = $user->account->first_name.' '.$user->account->last_name;
            $datas = array(
                'id'        => $user->id,
                'username'  => $userName,
                'code'      => $resetCode,
                'password'  => $password
                
            );

            // send email
            Mail::queue('cmsharenjoy::mail.user-reset-password', $datas, function($message) use ($user)
            {
                $message->from(Config::get('mail.from.address'), Config::get('mail.from.name'))
                        ->subject(trans('cmsharenjoy::admin.reset_password'));
                $message->to($user->email);
            });
        }
        catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            Message::merge(array('errors' => trans('cmsharenjoy::admin.user_not_found')))->flash();
            return Redirect::to($this->objectUrl);
        }

        Message::merge(array('success' => trans('cmsharenjoy::admin.sent_reset_code')))->flash();
        return Redirect::to($this->objectUrl);
    }

}