<?php

if (!function_exists('logApiCall')) {
    function logApiCall($message = "") {
        $console = new Symfony\Component\Console\Output\ConsoleOutput();

        date_default_timezone_set(env('APP_TIMEZONE', 'UTC'));
        $timestamp = date('D M d h:i:s Y');
        $url = url()->current();
        $console->writeln("[$timestamp] $url $message");

    }
}
