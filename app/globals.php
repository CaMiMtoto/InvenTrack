<?php

use Sqids\Sqids;

function encodeId($id): string
{
    $sqids = new Sqids('', 10);
    return $sqids->encode([$id]);
}

function decodeId(string $id): int
{
    $sqids = new Sqids('', 10);
    return $sqids->decode($id)[0];
}

function formatToken(string $token): array|string
{
    // format electricity token by separating every 4 characters with a space like this: 1234 5678 9012 3456
    return preg_replace('/(.{4})/', '$1 ', $token);
}
