<?php

namespace App\Services;

use App\Tweet;
use Aws\Comprehend\ComprehendClient;

class ComprehendService
{
    private $tweet;
    private $client;

    public function __construct(Tweet $tweet)
    {
        $this->tweet = $tweet;

        $this->client = new ComprehendClient([
            'credentials' => [
                'key' => config('services.comprehend.key'),
                'secret' => config('services.comprehend.secret'),
            ],
            'region' => 'us-east-1',
            'version' => 'latest',
        ]);
    }

    public function analyse()
    {
        $this->detectSentiment();
        $this->detectKeywords();
    }

    private function detectSentiment()
    {
        $result = $this->client->batchDetectSentiment([
            'LanguageCode' => 'en',
            'TextList' => [$this->tweet->text],
        ]);

        $sentiment = $result['ResultList'][0]['Sentiment'];
        $score = 0;

        foreach ($result['ResultList'][0]['SentimentScore'] as $key => $value) {
            if (strtolower($key) === strtolower($sentiment)) {
                $score = $value;
            }
        }

        $this->tweet->sentiment = $sentiment;
        $this->tweet->sentiment_score = $score;
        $this->tweet->save();

        return true;
    }

    private function detectKeywords()
    {
        $result = $this->client->batchDetectKeyPhrases([
            'LanguageCode' => 'en',
            'TextList' => [$this->tweet->text],
        ]);

        $keywords = collect();
        foreach ($result['ResultList'][0]['KeyPhrases'] as $keyPhrase) {
            if (strlen($keyPhrase['Text']) <= 3) {
                return true;
            }

            $keywords->push(['keyword' => $keyPhrase['Text']]);
        }

        $this->tweet->keywords()->createMany($keywords);

        return true;
    }
}
