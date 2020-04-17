<?php

namespace BrosSquad\Linker\Api\Interfaces;

interface HttpStatusCodes
{
    public const OK = 200;
    public const CREATED = 201;
    public const NO_CONTENT = 204;
    public const MOVED_PERMANENTLY = 301;
    public const FOUND = 302;
    public const BAD_REQUEST = 400;
    public const UNAUTHORIZED = 401;
    public const FORBIDDEN = 403;
    public const NOT_FOUND = 404;
    public const IM_A_TEAPOT = 418;
    public const UNPROCESSABLE_ENTITY = 422;
    public const INTERNAL_SERVER_ERROR = 500;
    public const BAD_GATEWAY = 502;
    public const SERVICE_UNAVAILABLE = 503;
}
