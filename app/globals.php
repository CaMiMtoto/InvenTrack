<?php

use Sqids\Sqids;

function encodeId($id): string
{
//    return base64_encode($id);
     $sqids = new Sqids('', 10);
     return $sqids->encode([$id]);
}

function decodeId(string $id): int
{
//    return base64_decode($id);
    $sqids = new Sqids('', 10);
    return $sqids->decode($id)[0];
}

function formatToken(string $token): array|string
{
    // format electricity token by separating every 4 characters with a space like this: 1234 5678 9012 3456
    return preg_replace('/(.{4})/', '$1 ', $token);
}

/**
 * Formats a number into a compact, human-readable string (e.g., 1500 -> "1.5K").
 *
 * @param float|int $num The number to format.
 * @param int $precision The number of decimal places.
 * @return string The formatted string.
 */
function formatNumberToShort(float|int $num, int $precision = 1): string
{
    if ($num < 1000) {
        return (string)$num;
    }

    $suffixes = ['', 'K', 'M', 'B', 'T'];
    $power = floor(log($num, 1000));

    $formattedNumber = round($num / (1000 ** $power), $precision);

    // Remove unnecessary trailing zeros and decimal points (e.g., 1.0 -> 1)
    $formattedNumber = (str_contains($formattedNumber, '.')) ? rtrim(rtrim($formattedNumber, '0'), '.') : $formattedNumber;

    return $formattedNumber . $suffixes[$power];
}
