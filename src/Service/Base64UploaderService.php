<?php

namespace App\Service;

use Exception;

class Base64UploaderService
{
    public static function handle(string $base64Image, string $dir): string
    {
        $uploadDir = __DIR__ . '/../../public/assets'. $dir;

        // Check if the directory exists; if not, try to create it
        if (!is_dir($uploadDir)) {
                throw new Exception ('Directory does not exists : ' . $uploadDir);
        }
        // Check if the directory is writable
        if (!is_writable($uploadDir)) {
            throw new Exception('Directory is not writable: ' . $uploadDir);
        }

        // Extract image data and extension from base64 string
        $data = explode(',', $base64Image);
        if (count($data) !== 2) {
            throw new Exception('Invalid base64 image data.');
        }

        $imageData = base64_decode($data[1]);
        if ($imageData === false) {
            throw new Exception('Base64 decoding failed.');
        }

        // Determine the image extension
        $finfo = finfo_open();
        $mime_type = finfo_buffer($finfo, $imageData, FILEINFO_MIME_TYPE);
        finfo_close($finfo);

        $extensions = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp'
        ];

        if (!isset($extensions[$mime_type])) {
            throw new Exception('Unsupported image format: ' . $mime_type);
        }

        $extension = $extensions[$mime_type];

        // Generate a unique file name
        $fileName = uniqid() . '.' . $extension;
        $filePath = $uploadDir . '/' . $fileName;

        // Save the image to the file system
        if (file_put_contents($filePath, $imageData) === false) {
            throw new Exception('Failed to write file: ' . $filePath);
        }
            return $fileName;
    }
}
