<?php

namespace App\Listeners;

use App\Events\NewTweetEvent;
use App\Services\TweetService;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleNewTweet implements ShouldQueue
{
    private $tweetService;

    public function __construct(TweetService $tweetService)
    {
        $this->tweetService = $tweetService;
    }

    public function handle(NewTweetEvent $event)
    {
        $this->tweetService->handle($event->tweet);
    }
}
