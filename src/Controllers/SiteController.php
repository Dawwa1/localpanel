<?php

namespace Localpanel\Controllers;

use Localpanel\View;
use Localpanel\Models\SiteModel;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

class SiteController{

    public function create(ServerRequestInterface $request): \React\Promise\PromiseInterface
    {
        $data = json_decode((string)$request->getBody(), true);

        if (!isset($data['name'], $data['ip'], $data['domain'], $data['hidden'], $data['root_path'], $data['conf_path'])) {
            throw new \InvalidArgumentException('Missing required fields in request body');
        }

        return SiteModel::createSite(
            $data['name'],
            $data['ip'],
            $data['domain'],
            (bool)$data['hidden'],
            $data['root_path'],
            $data['conf_path']
        )->then(function ($site) {
            return new Response(201, ['Content-Type' => 'application/json'], json_encode([
                'message' => 'Site created successfully',
                'site' => [
                    'name' => $site->name,
                    'ip' => $site->ip,
                    'domain' => $site->domain,
                    'hidden' => $site->hidden,
                    'root_path' => $site->root_path,
                    'conf_path' => $site->conf_path,
                    'created_at' => $site->created_at->format('Y-m-d H:i:s'),
                ],
            ], JSON_PRETTY_PRINT));
        });
    }

    public function get(ServerRequestInterface $request): \React\Promise\PromiseInterface
    {
        $identifier = $request->getQueryParams()['id'] ?? null;
        $value = $request->getQueryParams()['value'] ?? null;

        if ($identifier === null || $value === null) {
            throw new \InvalidArgumentException('Missing required query parameters: id and value');
        }
        return SiteModel::getSite($identifier, $value)->then(function ($site) {
            if ($site === null) {
                return ['error' => 'Site not found'];
            }

            $jsonData = json_encode([
                'name' => $site->name,
                'ip' => $site->ip,
                'domain' => $site->domain,
                'hidden' => $site->hidden,
                'root_path' => $site->root_path,
                'conf_path' => $site->conf_path,
                'created_at' => $site->created_at->format('Y-m-d H:i:s'),
            ], JSON_PRETTY_PRINT);
            return new Response(200, ['Content-Type' => 'application/json'], $jsonData);
        });
    }
}