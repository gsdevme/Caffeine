<?php

namespace Caffeine\Console\Command\Question;

use Exception;

class UsernameQuestion implements QuestionInterface
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

    public function getQuestion($default = null)
    {
        return sprintf('<question>Please enter the username for the bot [%s]: </question>', $default);
    }
}
