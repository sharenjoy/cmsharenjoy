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

    public function success($message)
    {
        $this->merge(['success' => $message])->flash();
    }

    public function error($message)
    {
        $this->merge(['error' => $message])->flash();
    }

    public function info($message)
    {
        $this->merge(['info' => $message])->flash();
    }

    public function warning($message)
    {
        $this->merge(['warning' => $message])->flash();
    }

    /**
     * Output some message and status
     * @param  string $status  success, error, warning
     * @param  string $message This is message wants to output
     * @param  array  $date
     * @return array
     */
    public function json($status, $message, $data = null)
    {
        return array(
            'status'  => $status,
            'message' => $message,
            'data'    => $data
        );
    }
}