<?php

namespace MiniBox\Http\Response;

class JsonResponse extends HttpResponse
{
    public function send(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        parent::send();
    }
}