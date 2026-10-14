<?php


namespace Localpanel\Controllers;

use Localpanel\Models\SiteModel;
use React\Http\Message\Response;
use Localpanel\View;
use function React\Async\await;


class HomeController extends PageController
{
    public function render(): Response
    {
        $html = View::page('pages/home', [
            'nonce' => $this->nonce,
            'sites' => await(SiteModel::class::getAllSites()),
        ]);

        return new Response(200, [
            'Content-Type' => 'text/html',
            "Content-Security-Policy: default-src 'self'; script-src 'nonce-$this->nonce';",
        ], $html);
    }
}


?>