<?php

function encodeId($id): string
{
    return base64_encode($id);
}

function decodeId($id): int
{
    return base64_decode($id);
}

function formatToken(string $token): array|string
{
    // format electricity token by separating every 4 characters with a space like this: 1234 5678 9012 3456
    return preg_replace('/(.{4})/', '$1 ', $token);
}
