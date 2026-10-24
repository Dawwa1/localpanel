<?php

namespace Localpanel\Models;

use React\Stream\ReadableResourceStream;
use React\Stream\ThroughStream;

class RunModel {

    private static string $baseCommand = __DIR__ . '/../../bin/localpanel.php';

    private static function run(string $cmd): ThroughStream {
        set_time_limit(0);

        // PHP-FPM starts an output buffer from php.ini. Remove every active
        // buffer so SapiHandler's flush() can forward each stream chunk.
        while (ob_get_level() > 0) {
            ob_end_flush();
        }
        ob_implicit_flush(true);

        $descriptors = [
            0 => ['pipe', 'r'], // stdin
            1 => ['pipe', 'w'], // stdout
            2 => ['pipe', 'w'], // stderr
        ];

        $process = proc_open($cmd, $descriptors, $pipes);

        if (!is_resource($process)) {
            throw new \RuntimeException("Failed to execute command: $cmd");
        }

        fclose($pipes[0]);

        $output = new ThroughStream();
        $streamsOpen = 2;
        $closeStream = function () use (&$streamsOpen, $output, $process): void {
            $streamsOpen--;
            if ($streamsOpen === 0) {
                proc_close($process);
                $output->end();
            }
        };

        foreach ([$pipes[1], $pipes[2]] as $pipe) {
            $stream = new ReadableResourceStream($pipe);
            $stream->on('data', [$output, 'write']);
            $stream->on('end', $closeStream);
            $stream->on('error', function (\Throwable $error) use ($output): void {
                $output->write("\n[command stream error] " . $error->getMessage() . "\n");
                $output->close();
            });
        }

        return $output;
    }

    public static function createCmd(string $name, string $ipv4, string $domain, ?string $doc_root_path = null): ThroughStream
    {
        $cmd = sprintf(
            'sudo %s create %s %s %s',
            escapeshellarg(self::$baseCommand),
            escapeshellarg($name),
            escapeshellarg($ipv4),
            escapeshellarg($domain)
        );
        if (isset($doc_root_path)) {
            $cmd .= ' ' . escapeshellarg($doc_root_path);
        }

        return self::run($cmd);
    }

    public static function deleteCmd(string $ip, bool $delRoot = false): ThroughStream
    {
        $cmd = sprintf('sudo %s delete %s', escapeshellarg(self::$baseCommand), escapeshellarg($ip));
        if ($delRoot) {
            $cmd .= ' --full-delete';
        }
        return self::run($cmd);
    }

    public static function pingTest(string $ip): ThroughStream
    {
        $cmd = sprintf('ping -c 4 %s', escapeshellarg($ip));
        return self::run($cmd);
    }
}