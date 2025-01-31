<?php

namespace App\Service;

use Symfony\Component\Yaml\Yaml;
use App\Class\NewsCategory;

class CategoryService
{
    /**
     * Get categories from configuration.
     */
    public static function getCategories(): array
    {
        return self::parseYaml();
    }

    /**
     * Gets single category by name.
     */
    public static function getCategoryByName(string $name): array
    {
        $categories = self::parseYaml();

        if (array_key_exists($name, $categories)) {
            return $categories[$name];
        }

        return [];
    }
    
    /**
     * Helper function to parse Yaml.
     */
    private static function parseYaml(): array
    {
        $yaml = Yaml::parse(file_get_contents(__DIR__ .'/../../config/categories.yaml'));
        return $yaml;
    }
}
