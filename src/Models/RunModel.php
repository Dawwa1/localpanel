<?php

namespace Localpanel\Models;

use React\Stream\ReadableResourceStream;
use React\Stream\ThroughStream;

class RunModel {

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

    public static function pingTest(string $ip): ThroughStream
    {
        $cmd = sprintf('ping -c 4 %s', escapeshellarg($ip));
        return self::run($cmd);
    }
}