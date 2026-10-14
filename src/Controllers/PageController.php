<?php
namespace Localpanel\Controllers;

abstract class PageController{
    protected string $nonce;

    public function __construct()
    {
        $this->nonce = bin2hex(random_bytes(16));
    }

    abstract public function render(): \React\Http\Message\Response;
}