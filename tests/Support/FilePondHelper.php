<?php

declare(strict_types=1);

namespace Yii\Service\Tests\Support;

use JsonException;

use function base64_decode;
use function fclose;
use function fopen;
use function fwrite;
use function is_string;
use function json_decode;
use function pathinfo;
use function preg_replace;

/**
 * FilePondHelper provides helper methods for working with FilePond.
 */
final class FilePondHelper
{
    /**
     * @throws JsonException
     */
    public static function save(array $files, string $path): void
    {
        /** @psalm-var array<array-key, string|null> $files */
        foreach ($files as $file) {
            if (is_string($file) && $file !== '') {
                /** @psalm-var object|false|null $file */
                $file = json_decode($file, false, 512, JSON_THROW_ON_ERROR);
            }

            if (is_object($file) && is_string($file->data) && is_string($file->name)) {
                self::writeFile(
                    $path,
                    base64_decode($file->data),
                    self::sanitizeFilename($file->name)
                );
            }
        }
    }

    private static function sanitizeFilename(string $filename): string
    {
        $extension = '';
        $name = '';

        $info = pathinfo($filename);

        if (isset($info['filename'])) {
            $name = self::sanitizeFilenamePart($info['filename']);
        }

        if (isset($info['extension'])) {
            $extension = self::sanitizeFilenamePart($info['extension']);
        }

        return ($name !== '' ? $name : '_') . '.' . $extension;
    }

    private static function sanitizeFilenamePart(string $str): string
    {
        return preg_replace("/[^a-zA-Z0-9\_\s]/", '', $str);
    }

    private static function writeFile(string $path, string $data, string $filename): void
    {
        $handle = fopen($path . DIRECTORY_SEPARATOR . $filename, 'wb');
        fwrite($handle, $data);
        fclose($handle);
    }
}
