<?php

namespace Caffeine\Console\Command\Question;

interface QuestionInterface
{
    public function getValidator();

    public function getQuestion();
}
