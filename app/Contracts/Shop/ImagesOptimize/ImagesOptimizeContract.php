<?php

namespace App\Contracts\Shop\ImagesOptimize;

use Tinify\Source;

interface ImagesOptimizeContract
{
    /**
     * Get image from file path
     *
     * @param string $path File path
     *
     * @return Source
     */
    public function getImageFromFile(string $path): Source;

    /**
     * Get image from buffer
     *
     * @param string $name File name
     *
     * @return Source
     */
    public function getImageFromBuffer(string $name): Source;

    /**
     * Get image from URL
     *
     * @param string $url File URL
     *
     * @return Source
     */
    public function getImageFromUrl(string $url): Source;

    /**
     * Save image to path
     *
     * @param Source $file File
     * @param string $path File path
     *
     * @return bool
     */
    public function saveImageToPath(Source $file, string $path): bool;
}
