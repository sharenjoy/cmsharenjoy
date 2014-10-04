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

    protected function mergeMessage($type, $messages)
    {
        if (is_array($messages))
        {
            foreach ($messages as $message)
            {
                $this->merge([$type => $message])->flash();
            }
        }
        elseif (is_string($messages))
        {
            $this->merge([$type => $messages])->flash();
        }
    }

    /**
     * Output some message and status
     * @param  string $status  success, error, warning
     * @param  string $message This is message wants to output
     * @param  mixed  $date
     * @return array
     */
    public function result($status, $message, $data = null)
    {
        return [
            'status'  => $status,
            'message' => $message,
            'data'    => $data
        ];
    }

    public function __call($name, $args)
    {
        if (count($args) == '1')
        {
            $this->mergeMessage($name, $args[0]);
        }
        else
        {
            throw new \Exception("It doesn't have right arguments");
        }
    }
}