<?php namespace Sharenjoy\Cmsharenjoy\Controllers;

use Sharenjoy\Cmsharenjoy\Repo\Member\MemberInterface;
use Sentry, Mail, Config, Message, Redirect, Str, Hash;

class MemberController extends ObjectBaseController {

    protected $appName = 'member';

    protected $functionRules = [
        'list'   => true,
        'create' => true,
        'update' => true,
        'delete' => true,
        'resetpassword' => true,
    ];

    protected $listConfig = [
        'name'         => ['name'=>'name',         'align'=>'',       'width'=>''   ],
        'phone'        => ['name'=>'phone',        'align'=>'',       'width'=>''   ],
        'mobile'       => ['name'=>'mobile',       'align'=>'',       'width'=>''   ],
        'email'        => ['name'=>'email',        'align'=>'',       'width'=>''   ],
        'created_at'   => ['name'=>'created',      'align'=>'center', 'width'=>'20%'],
    ];

    public function __construct(MemberInterface $member)
    {
        $this->repository = $member;
        parent::__construct();
    }

    public function getResetpassword($id)
    {
        $user = $this->repository->byId($id);

        $password = Str::random(8);
        $user->password = Hash::make($password);

        if ( ! $user->save())
        {
            Message::merge(array('errors' => trans('cmsharenjoy::admin.some_wrong')))->flash();
            return Redirect::to($this->urlSegment.'/login');
        }

        $datas = array(
            'id'        => $user->id,
            'username'  => $user->name,
            'password'  => $password
        );

        // send email
        Mail::queue('emails.auth.reset-password', $datas, function($message) use ($user)
        {
            $message->from(Config::get('mail.from.address'), Config::get('mail.from.name'))
                    ->subject(trans('cmsharenjoy::admin.reset_password'));
            $message->to($user->email);
        });

        Message::merge(array('success' => trans('cmsharenjoy::admin.sent_reset_code')))->flash();
        return Redirect::to($this->objectUrl);
    }

}