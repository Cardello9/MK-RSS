<?php

namespace App\Service;

use Symfony\Component\Yaml\Yaml;

class CategoryService
{
    /**
     * Get categories from configuration.
     */
    public static function getCategories(): array
    {
        $yaml = Yaml::parse(file_get_contents(__DIR__ .'/../../config/categories.yaml'));
        return $yaml;
    }
}
