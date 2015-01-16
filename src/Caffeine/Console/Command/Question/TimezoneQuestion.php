<?php

namespace Caffeine\Console\Command\Question;

use DateTimeZone;
use Exception;

class TimezoneQuestion implements QuestionInterface
{

    public function getValidator()
    {
        return function ($value) {
            $timezones = DateTimeZone::listIdentifiers();

            if (strlen($value) === 0) {
                return new DateTimeZone('Europe/London');
            }

            if (!in_array($value, $timezones)) {
                throw new Exception('Timezone isn\'t value');
            }

            return new DateTimeZone($value);
        };
    }

    public function getQuestion()
    {
        return '<question>Please enter the Timezone for the bot/stream[Europe/London]: </question>';
    }
}
