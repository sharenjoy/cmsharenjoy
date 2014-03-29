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
}