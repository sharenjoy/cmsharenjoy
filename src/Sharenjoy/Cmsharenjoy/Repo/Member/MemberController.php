<?php namespace Sharenjoy\Cmsharenjoy\Repo\Member;

use Sharenjoy\Cmsharenjoy\Controllers\ObjectBaseController;
use Message, Redirect, Input, Notify;

class MemberController extends ObjectBaseController {

    protected $functionRules = [
        'list'           => true,
        'create'         => true,
        'update'         => true,
        'delete'         => true,
        'remindpassword' => true,
        'sendmessage'    => true,
    ];

    protected $listConfig = [
        'name'         => ['name'=>'name',         'align'=>'',       'width'=>''   ],
        'phone'        => ['name'=>'phone',        'align'=>'',       'width'=>''   ],
        'mobile'       => ['name'=>'mobile',       'align'=>'',       'width'=>''   ],
        'email'        => ['name'=>'email',        'align'=>'',       'width'=>''   ],
        'created_at'   => ['name'=>'created',      'align'=>'center', 'width'=>'20%'],
    ];

    public function __construct(MemberInterface $repo)
    {
        $this->repo = $repo;
        parent::__construct();
    }

    public function getRemindpassword($id)
    {
        $member = $this->repo->showById($id);
        $result = $this->repo->passwordRemind(['email'=>$member->email], '這是您的重設信');

        $result['status'] ? Message::success($result['message'])
                          : Message::error($result['message']);
        
        return Redirect::to($this->objectUrl);
    }


    public function getSendmessage($id)
    {
        $this->layout->with('id', $id);
    }

    public function postSendmessage()
    {
        $input = Input::all();
        $user = $this->repo->showById($input['id'])->toArray();

        if ($user['mobile'] != '' && $input['message'] != '')
        {
            Notify::to('+886939025412')->notify('齊味', $input['message']);
            Message::success('簡訊寄送成功');
        }
        else
        {
            Message::error('簡訊寄送失敗');
        }

        return Redirect::to($this->objectUrl);
    }

}