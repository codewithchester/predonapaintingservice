<?php
namespace App\Core;

final class Uploader
{
    public static function image(array $file, string $subdirectory = 'projects'): ?string
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) return null;
        if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) throw new \RuntimeException('The image could not be uploaded. Please try again.');
        if (($file['size'] ?? 0) > 5 * 1024 * 1024) throw new \RuntimeException('Images must be 5MB or smaller.');
        $tmp = $file['tmp_name'] ?? '';
        if (!is_uploaded_file($tmp)) throw new \RuntimeException('Invalid upload.');
        $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($tmp);
        $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        if (!isset($extensions[$mime]) || @getimagesize($tmp) === false) throw new \RuntimeException('Only JPG, PNG, and WebP images are allowed.');
        $isPrivate = str_starts_with($subdirectory, 'private-enquiries');
        $filename = bin2hex(random_bytes(16)) . '.' . $extensions[$mime];
        if ($isPrivate) {
            $relative = 'private/enquiries/' . $filename;
            $destination = dirname(__DIR__, 2) . '/storage/' . $relative;
        } else {
            $relative = trim($subdirectory, '/') . '/' . $filename;
            $destination = dirname(__DIR__, 2) . '/public/uploads/' . $relative;
        }
        $directory = dirname($destination);
        if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) throw new \RuntimeException('Upload directory could not be created.');
        if (!move_uploaded_file($tmp, $destination)) throw new \RuntimeException('Image could not be saved.');
        return $relative;
    }

    public static function delete(?string $relative): void
    {
        if (!$relative || str_contains($relative, '..')) return;
        $base = str_starts_with($relative, 'private/enquiries/')
            ? dirname(__DIR__, 2) . '/storage/'
            : dirname(__DIR__, 2) . '/public/uploads/';
        $path = $base . ltrim($relative, '/');
        if (is_file($path)) @unlink($path);
    }
}
