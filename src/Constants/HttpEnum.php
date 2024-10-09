<?php
declare(strict_types=1);

namespace Exewen\Http\Constants;

class HttpEnum
{
    const TYPE_QUERY = 'query';
    const TYPE_HEADERS = 'headers';
    const TYPE_JSON = 'json';
    const TYPE_FORM = 'form_params';
    const TYPE_MULTIPART = 'multipart';
    const TYPE_BODY = 'body';
}