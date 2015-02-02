<?php

namespace Caffeine\Console\Command\Question;

use Exception;

class OAuthQuestion implements QuestionInterface
{

    public function getValidator()
    {
        return function ($value) {
            if (strlen($value) <= 0) {
                throw new Exception('value must not be empty, you can create a token at http://goo.gl/mBNASR');
            }

            return $value;
        };
    }

    public function getQuestion($question)
    {
        return sprintf('<question>Please enter the OAuth token for the bot. [%s]: </question>', $question);
    }
}
