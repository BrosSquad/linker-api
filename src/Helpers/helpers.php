<?php

namespace BrosSquad\Linker\Api\Helpers;

function env(string $env, $default = null)
{
    $value = getenv($env);
    if ('' === $value) {
        return $default;
    }

    return $value;
}
