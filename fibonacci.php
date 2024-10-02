<?php
function fibonacci(int $n): string
{
    // check if the input is between 1 and 20
    if ($n < 1 || $n > 20) {
        return "Input must be an integer between 1 and 20.";
    }

    $fib = [];

    
    $fib[0] = 0;
    if ($n > 1) {
        $fib[1] = 1;
    }

    // generate fib sequence
    for ($i = 2; $i < $n; $i++) {
        $fib[$i] = $fib[$i - 1] + $fib[$i - 2];
    }

    // return as string
    return implode(", ", array_slice($fib, 0, $n));
}


$input = 5;
$output = fibonacci($input);
echo "Input: $input, Output: $output";
