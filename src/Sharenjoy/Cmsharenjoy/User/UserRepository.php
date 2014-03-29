<?php namespace Sharenjoy\Cmsharenjoy\User;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseRepository;

class UserRepository extends EloquentBaseRepository implements UserInterface
{

    /**
     * Construct Shit
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->model = $user;
    }

}