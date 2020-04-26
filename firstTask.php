<?php

$pattern = "/'[0-9]+'/m";

echo 'Write the line: ';
$line = trim(fgets(STDIN));

echo "Inputed line: $line\n";

$changedLine = preg_replace_callback(
    $pattern,
    function($matches) {
        $number = str_replace("'", "", $matches[0]);
        return "'" . $number * 2 . "'";
    },
    $line
);

echo "Changed line: $changedLine\n";