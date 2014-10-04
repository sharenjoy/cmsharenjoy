<?php namespace Sharenjoy\Cmsharenjoy\Repo\Member;

use Sharenjoy\Cmsharenjoy\Core\EloquentBaseRepository;
use Sharenjoy\Cmsharenjoy\Service\Validation\ValidableInterface;
use Redirect, Message, Password, Hash;

class MemberRepository extends EloquentBaseRepository implements MemberInterface {

    public function __construct(Member $model, ValidableInterface $validator)
    {
        $this->validator = $validator;
        $this->model     = $model;

        parent::__construct();
    }

    public function passwordRemind($email, $subject)
    {
        $response = Password::remind($email, function($mail, $user, $token) use ($subject) {
            $mail->subject($user->name.' '.$subject);
        });

        switch ($response)
        {
            case Password::INVALID_USER:
                return Message::result(false, trans($response));

            case Password::REMINDER_SENT:
                return Message::result(true, trans($response));
        }
    }

    public function passwordReset($credentials)
    {
        $response = Password::reset($credentials, function($user, $password)
        {
            $user->password = Hash::make($password);
            $user->save();
        });

        switch ($response)
        {
            case Password::INVALID_PASSWORD:
            case Password::INVALID_TOKEN:
            case Password::INVALID_USER:
                return Message::result(false, trans($response));

            case Password::PASSWORD_RESET:
                return Message::result(true, trans($response));
        }
    }

}