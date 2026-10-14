<?php

namespace Localpanel\Controllers;

use Localpanel\View;
use React\Http\Message\Response;

// Responsible for displaying /bin commands from RunModel
class RunController extends PageController{

    public function render(): Response
    {
        $html = View::page('pages/create', ['nonce' => $this->nonce]);

        return new Response(200, ['Content-Type' => 'text/html',
            "Content-Security-Policy: default-src 'self'; script-src 'nonce-$this->nonce';"], $html);
    }
}