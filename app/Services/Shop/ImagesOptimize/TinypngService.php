<?php

namespace App\Services\Shop\ImagesOptimize;

use App\Contracts\Shop\ImagesOptimize\ImagesOptimizeContract;
use Tinify\ClientException;
use Tinify\Source;
use Tinify\Tinify;

class TinypngService implements ImagesOptimizeContract
{
    protected string $apikey;
    protected Tinify $client;

    public function __construct()
    {
        $this->apikey = config('services.tinypng.apikey');
        if(!$this->apikey) {
            throw new \InvalidArgumentException('Please set TINYPNG_APIKEY environment variables.');
        }
        $this->client = new Tinify();
        $this->client->setKey($this->apikey);
    }

    /**
     * Get image from file path
     *
     * @param string $path File path
     *
     * @return Source
     */
    public function getImageFromFile(string $path): Source
    {
        return Source::fromFile($path);
    }

    /**
     * Get image from buffer
     *
     * @param string $name File name
     *
     * @return Source
     */
    public function getImageFromBuffer(string $name): Source
    {
        return Source::fromBuffer($name);
    }

    /**
     * Get image from URL
     *
     * @param string $url File URL
     *
     * @return Source
     */
    public function getImageFromUrl(string $url): Source
    {
        return Source::fromUrl($url);
    }

    /**
     * Save image to path
     *
     * @param Source $file File
     * @param string $path File path
     *
     * @return bool
     */
    public function saveImageToPath(Source $file, string $path): bool
    {
        return $file->toFile($path);
    }

    public function validate() {
        try {
            $this->client->getClient()->request("post", "/shrink");
        } catch (ClientException $e) {
            return true;
        }
    }
}
