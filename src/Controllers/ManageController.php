<?php

namespace Localpanel\Controllers;

use Localpanel\Controllers\PageController;
use Localpanel\View;

class ManageController extends PageController
{

    public function render(): \React\Http\Message\Response
    {
        $html = View::page('pages/manage', ['nonce' => $this->nonce]);

        return new \React\Http\Message\Response(200, [
            'Content-Type' => 'text/html',
            "Content-Security-Policy: default-src 'self'; script-src 'nonce-$this->nonce';",
        ], $html);
    }
}