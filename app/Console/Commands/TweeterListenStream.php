<?php

namespace App\Console\Commands;

use App\Events\NewTweetEvent;
use Illuminate\Console\Command;
use TwitterStreamingApi;


class TweeterListenStream extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tweeter:listen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Listen to the twitter public stream';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        TwitterStreamingApi::publicStream()
            ->whenHears('#covid19', function (array $tweet) {
                event(new NewTweetEvent($tweet));
            })
            ->startListening();
    }
}
