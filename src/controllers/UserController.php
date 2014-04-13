<?php namespace Sharenjoy\Cmsharenjoy\Controllers;

use Sharenjoy\Cmsharenjoy\User\UserInterface;

class UserController extends ObjectBaseController {

    protected $appName = 'user';

    protected $functionRules = [
        'list'   => true,
        'create' => true,
        'order'  => false
    ];

    protected $listConfig = [
        'user_name' => [
            'name'  => 'userName',
            'align' => '',
            'width' => ''
        ],
        'phone' => [
            'name'  => 'cellphone',
            'align' => '',
            'width' => ''
        ],
        'email' => [
            'name'  => 'email',
            'align' => '',
            'width' => ''
        ],
        'created_at' => [
            'name'  => 'created',
            'align' => 'center',
            'width' => '20%'
        ],
    ];

    public function __construct(UserInterface $user)
    {
        $this->repository = $user;
        parent::__construct();
    }

}