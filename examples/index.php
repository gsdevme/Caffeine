<?php

require_once realpath(__DIR__) . '/../vendor/autoload.php';

ini_set('display_errors', 'on');
error_reporting(-1);

$config = new Caffeine\Config([
    /**
     * The oauth token for the bots user
     */
    'auth' => 'oauth:xxxx',

    /**
     * The Bots username
     */
    'username' => 'CaffeineBot',

    /**
     * Mods of the channel do have higher rate limits, if in doubt set it to false
     */
    'mod' => true,

    /**
     * Allow $command add syntax
     */
    'allowCommands' => true,

    /**
     * Users which can add the commands
     */
    'allowedUsernames' => ['xx'],

    /**
     * API configuration
     */
    'api' => [
        'clientId' => 'xxxx'
    ]
]);
