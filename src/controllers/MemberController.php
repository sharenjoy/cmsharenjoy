<?php namespace Sharenjoy\Cmsharenjoy\Controllers;

use Sharenjoy\Cmsharenjoy\Repo\Member\MemberInterface;
use Sentry, Mail, Config, Message, Redirect, Str, Hash, Poster, Input, App, Notify;

class MemberController extends ObjectBaseController {

    protected $functionRules = [
        'list'          => true,
        'create'        => true,
        'update'        => true,
        'delete'        => true,
        'resetpassword' => true,
        'sendmessage'   => true,
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
            Message::output('flash', 'errors', trans('cmsharenjoy::app.some_wrong'));
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
                    ->subject(trans('cmsharenjoy::app.reset_password'));
            $message->to($user->email);
        });

        Message::output('flash', 'success', trans('cmsharenjoy::app.sent_reset_code'));
        return Redirect::to($this->objectUrl);
    }


    public function getSendmessage($id)
    {
        $this->layout->with('id', $id);
    }

    public function postSendmessage()
    {
        $data = Input::all();
        $user = Poster::showById($data['id'])->toArray();

        if ($user['mobile'] != '' && $data['message'] != '')
        {
            Notify::to('+886939025412')->notify('齊味', $data['message']);
            Message::output('flash', 'success', '簡訊寄送成功');
        }
        else
        {
            Message::output('flash', 'errors', '簡訊寄送失敗');
        }

        return Redirect::to($this->objectUrl);

    }

}