<?php
echo "<pre>";
print_r(scandir(__DIR__));
echo "\n\napi/:\n";
print_r(scandir(__DIR__ . '/api'));
echo "\n\napi/quotes/:\n";
print_r(scandir(__DIR__ . '/api/quotes'));
echo "</pre>";
