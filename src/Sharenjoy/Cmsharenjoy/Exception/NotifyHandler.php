<?php namespace Sharenjoy\Cmsharenjoy\Exception;

use Sharenjoy\Cmsharenjoy\Service\Notification\NotifierInterface;
use Exception, Config;

class NotifyHandler implements HandlerInterface {

    protected $notifier;

    public function __construct(NotifierInterface $notifier)
    {
        $this->notifier = $notifier;
    }

    /**
     * Handle Sharenjoy Exceptions
     *
     * @param \Sharenjoy\Cmsharenjoy\Exception\SharenjoyException
     * @return void
     */
    public function handle(SharenjoyException $exception)
    {
        $this->sendException($exception);
    }

    /**
     * Send Exception to notifier
     * @param  \Exception $exception Send notification of exception
     * @return void
     */
    protected function sendException(Exception $e)
    {
        $this->notifier->to(Config::get('cmsharenjoy::twilio.to'))
                       ->notify('Error: '.get_class($e), $e->getMessage());
    }

}
