<?php

namespace Caffeine\Console\Command\Question;

use Exception;

class OAuthQuestion implements QuestionInterface
{

    public function getValidator()
    {
        return function ($value) {
            if (strlen($value) <= 0) {
                throw new Exception('value must not be empty.');
            }

            return $value;
        };
    }

    public function getQuestion()
    {
        return '<question>Please enter the OAuth token for the bot. (http://goo.gl/mBNASR): </question>';
    }
}
