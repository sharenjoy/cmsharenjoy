<?php namespace Sharenjoy\Cmsharenjoy\User;

use Sharenjoy\Cmsharenjoy\Controllers\ObjectBaseController;
use Sentry, Mail, Config, Message, Redirect, Str;

class UserController extends ObjectBaseController {

    protected $functionRules = [
        'list'          => true,
        'create'        => true,
        'update'        => true,
        'delete'        => true,
        'resetpassword' => true,
    ];

    protected $listConfig = [
        'name'         => ['name'=>'name',         'align'=>'',       'width'=>''   ],
        'phone'        => ['name'=>'cellphone',    'align'=>'',       'width'=>''   ],
        'email'        => ['name'=>'email',        'align'=>'',       'width'=>''   ],
        'created_at'   => ['name'=>'created',      'align'=>'center', 'width'=>'20%'],
    ];

    public function __construct(UserInterface $repo)
    {
        $this->repository = $repo;
        parent::__construct();
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
                Message::error(trans('cmsharenjoy::app.some_wrong'));
                return Redirect::to($this->urlSegment.'/login');
            }

            // Get the password reset code
            $resetCode = $user->getResetPasswordCode();

            $datas = array(
                'id'        => $user->id,
                'username'  => $user->name,
                'code'      => $resetCode,
                'password'  => $password
                
            );

            // send email
            Mail::queue('cmsharenjoy::emails.auth.user-reset-password', $datas, function($message) use ($user)
            {
                $message->from(Config::get('mail.from.address'), Config::get('mail.from.name'))
                        ->subject(trans('cmsharenjoy::app.reset_password'));
                $message->to($user->email);
            });
        }
        catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            Message::error(trans('cmsharenjoy::app.user_not_found'));
            return Redirect::to($this->objectUrl);
        }

        Message::success(trans('cmsharenjoy::app.sent_reset_code'));
        return Redirect::to($this->objectUrl);
    }

}