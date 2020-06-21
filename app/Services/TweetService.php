<?php

namespace App\Services;

use App\Tweet;
use Illuminate\Support\Facades\Validator;

class TweetService
{
    public function handle($tweet)
    {
        $validator = Validator::make($tweet, [
            'id' => ['required'],
            'text' => ['required'],
            'user.screen_name' => ['required'],
        ]);

        if ($validator->fails()) {
            return false;
        }

        $data = $validator->validated();
        $data['tweet_id'] = $data['id'];
        $data['username'] = $data['user']['screen_name'];

        unset($data['id'], $data['user']);

        $tweet = Tweet::create($data);

        $comprehend = new ComprehendService($tweet);
        $comprehend->analyse();

        return true;
    }
}
