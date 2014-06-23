<?php namespace Sharenjoy\Cmsharenjoy\Service\Message;

use Illuminate\Support\MessageBag;
use Illuminate\Session\Store;

class FlashMessageBag extends MessageBag {

    protected $session_key = 'flash_messages';
    protected $session;

    public function __construct(Store $session, $messages = array())
    {
        $this->session = $session;

        if ($session->has($this->session_key))
        {
            $messages = array_merge_recursive(
                $session->get($this->session_key),
                $messages
            );
        }

        parent::__construct($messages);
    }

    public function flash()
    {
        $this->session->flash($this->session_key, $this->messages);

        return $this;
    }

    /**
     * Output some message and status
     * @param  string $type    The type of message
     * @param  string $status  success, errors, warning
     * @param  string $message This is message wants to output
     * @param  array  $date
     * @return mixed
     */
    public function output($type, $status, $message, $data = null)
    {
        switch ($type)
        {
            case 'flash':
                $this->merge(array($status => $message))->flash();
                break;

            case 'json':
                return array(
                    'status'  => $status,
                    'message' => $message,
                    'data'    => $data
                );
                break;

            default:
                break;
        }
    }
}