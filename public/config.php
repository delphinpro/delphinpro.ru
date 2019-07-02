<?php

if (!function_exists('pre')) {
    function pre(...$var)
    {
        $title = '';
        if (count($var) > 1 && is_string($var[0])) {
            $title = $var[0];
            array_shift($var);
        }
        if (is_string($var)) {
            $var = htmlspecialchars($var);
        }
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT | DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        echo '<pre style="border:1px solid #ccc;background:#fff;color:#000;">';
        if ($title) {
            echo '<div style="padding:0.5rem;background:#eee;">'.$title.'</div>';
        }
        echo '<div style="padding:0.5rem;">';
        foreach ($var as $item) {
            print_r($item);
        }
        echo '</div>';
        echo '<div style="padding:0.5rem;border-top:1px solid #ccc;color:#ccc">'.$trace[0]['file'].':'
            .$trace[0]['line'].'</div>';
        echo '</pre>';
    }
}

return [
    'core'    => realpath(__DIR__.'/core'),
    'manager' => realpath(__DIR__.'/manager'),
    'root'    => realpath(__DIR__.'/'),
];
