<?php


if (! function_exists('log_api_call')) {
    function log_api_call() {
        $console = new Symfony\Component\Console\Output\ConsoleOutput();

        $timestamp = date('D M d H:i:s Y');
        $url = url()->current();
        $console->writeln("[$timestamp] $url" );

    }
}
