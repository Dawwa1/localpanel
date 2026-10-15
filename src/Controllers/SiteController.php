<?php

namespace Localpanel\Controllers;

use Localpanel\JsonResponse;
use Localpanel\Models\RunModel;
use Localpanel\Models\SiteModel;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use React\Promise\PromiseInterface;

class SiteController{

    public function test(ServerRequestInterface $request): Response
    {
        return new Response(200, [
            'Content-Type' => 'text/plain; charset=utf-8',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
        ], RunModel::pingTest('google.com'));
    }

    public function create(ServerRequestInterface $request): PromiseInterface
    {
        $data = json_decode((string)$request->getBody(), true);

        return SiteModel::createSite(
            $data['name'],
            $data['ip'],
            $data['domain'],
            (bool)$data['hidden'],
            $data['root_path'],
            $data['conf_path']
        )->then(function ($site) use ($data) {
            if (!isset($data['name'], $data['ip'], $data['domain'], $data['hidden'], $data['root_path'], $data['conf_path'])) {
                return JsonResponse::error('Missing required fields', 400, 'MISSING_FIELDS');
            }

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

        return SiteModel::getSite($identifier, $value)->then(function ($site) {
            if ($site === null) {
                return JsonResponse::error(404, 'Site not found', 'SITE_NOT_FOUND');
            }

            if ($identifier === null || $value === null) {
                return JsonResponse::error('Missing required query parameters: id and value', 400, 'MISSING_PARAMS');
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