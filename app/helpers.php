<?php

declare(strict_types = 1);

function formatDollarAmount(float $amount): string {
    $isNegative = $amount < 0;
    return ($isNegative ? '-' : '') . '$' . number_format(abs($amount), 2);
}

function dateFormat(string $date): string {
    return date('M j, Y', strtotime($date));
}

function amountColor(float $amount): string {
    return $amount > 0 ? 'green' : ($amount < 0 ? 'red' : 'black');
}
