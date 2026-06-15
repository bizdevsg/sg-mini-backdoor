<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class OptimizedImageStorage
{
    public function store(UploadedFile $file, string $directory = 'produk-images'): string
    {
        $extension = $this->targetExtension();
        $path = trim($directory, '/').'/'.Str::uuid().'.'.$extension;

        if ($this->supportsImagick($extension)) {
            $this->storeWithImagick($file, $path, $extension);

            return $path;
        }

        $this->storeWithGd($file, $path, $extension);

        return $path;
    }

    public function delete(?string $path): void
    {
        if (blank($path) || Str::startsWith($path, ['http://', 'https://', '/'])) {
            return;
        }

        Storage::disk('public')->delete($path);
    }

    private function targetExtension(): string
    {
        if ($this->supportsImagick('avif') || function_exists('imageavif')) {
            return 'avif';
        }

        if ($this->supportsImagick('webp') || function_exists('imagewebp')) {
            return 'webp';
        }

        throw new RuntimeException('Server belum mendukung konversi gambar ke AVIF atau WebP.');
    }

    private function supportsImagick(string $extension): bool
    {
        return class_exists(\Imagick::class)
            && in_array(strtoupper($extension), \Imagick::queryFormats(), true);
    }

    private function storeWithImagick(UploadedFile $file, string $path, string $extension): void
    {
        $image = new \Imagick();
        $image->readImage($file->getRealPath());

        if (method_exists($image, 'autoOrient')) {
            $image->autoOrient();
        }

        $image->stripImage();
        $image->setImageFormat($extension);
        $image->setImageCompressionQuality($extension === 'avif' ? 55 : 82);

        $image->writeImage(Storage::disk('public')->path($path));
        $image->clear();
        $image->destroy();
    }

    private function storeWithGd(UploadedFile $file, string $path, string $extension): void
    {
        $image = imagecreatefromstring($file->get());

        if (! $image) {
            throw new RuntimeException('File gambar tidak dapat diproses.');
        }

        if (function_exists('imagepalettetotruecolor')) {
            imagepalettetotruecolor($image);
        }

        imagealphablending($image, true);
        imagesavealpha($image, true);

        ob_start();

        $success = $extension === 'avif'
            ? imageavif($image, null, 55)
            : imagewebp($image, null, 82);

        $binary = ob_get_clean();

        imagedestroy($image);

        if (! $success || $binary === false) {
            throw new RuntimeException('Gagal mengonversi file gambar.');
        }

        Storage::disk('public')->put($path, $binary);
    }
}
