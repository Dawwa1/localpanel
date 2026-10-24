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

    public function create(ServerRequestInterface $request): Response
    {
        $data = $request->getParsedBody();

        $name = $data['site-name'] ?? null;
        $ip = $data['site-ip'] ?? null;
        $domain = $data['site-domain'] ?? null;
        $dbStatus = $data['create-db'] === 'on';

        $dbData = null;
        if ($dbStatus && isset($dbName, $dbUser, $dbPass)){
            $dbData = [
                'db_name' => $data['db-name'],
                'db_user' => $data['db-user'],
                'db_pass' => $data['db-pass'],
            ];
        }

        $dbSite = SiteModel::createSite($name, $ip, $domain, $dbData);

        if (is_array($dbSite)) {
            return JsonResponse::error('Failed to create site', 500, 'SITE_CREATION_FAILED');
        } else {
            return new Response(200, [
                'Content-Type' => 'text/plain; charset=utf-8',
                'Cache-Control' => 'no-cache',
                'X-Accel-Buffering' => 'no',
            ], $dbSite);
        }
    }

    public function get(ServerRequestInterface $request): \React\Promise\PromiseInterface
    {
        $identifier = $request->getQueryParams()['id'] ?? null;
        $value = $request->getQueryParams()['value'] ?? null;

        return SiteModel::getSite($identifier, $value)->then(function ($site) use ($identifier, $value) {
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

    public function delete(ServerRequestInterface $request): PromiseInterface
    {
        $ip = $request->getQueryParams()['ip'] ?? null;

        return SiteModel::deleteSite($ip)->then(function ($result) use ($ip) {
            if ($result === false) {
                return JsonResponse::error('Failed to delete site', 500, 'SITE_DELETION_FAILED');
            }
            if ($ip === null) {
                return JsonResponse::error('Missing required query parameter: ip', 400, 'MISSING_PARAMS');
            }

            return JsonResponse::success(null, 'Site deleted successfully', 200);
        });
    }

    public function update(ServerRequestInterface $request): PromiseInterface
    {
        $identifier = $request->getQueryParams()['id'] ?? null;
        $value = $request->getQueryParams()['value'] ?? null;
        $data = $request->getParsedBody();

        return SiteModel::updateSite($identifier, $value, $data)->then(function ($result) use ($identifier, $value, $data) {
            if ($result === false) {
                return JsonResponse::error('Failed to update site', 500, 'SITE_UPDATE_FAILED');
            }

            return JsonResponse::success(null, 'Site updated successfully', 200);
        });
    }
}