<?php

namespace App\Contracts\Common;

interface SchemasContract
{
    /**
     * Gets filled order view fields schema
     *
     * @param array $data Input data
     *
     * @return array
     */
    public static function getSchema(array $data = []): array;
}
