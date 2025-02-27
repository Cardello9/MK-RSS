<?php

namespace App\Service;

use Symfony\Component\Yaml\Yaml;

class CategoryService
{
    /**
     * Get categories from configuration.
     * 
     * @return mixed[]
     */
    public static function getCategories(): array
    {
        $configFile = file_get_contents(__DIR__ .'/../../config/categories.yaml');

        if (!$configFile) {
            return [];
        }

        $yaml = Yaml::parse($configFile);
        return $yaml;
    }
}
