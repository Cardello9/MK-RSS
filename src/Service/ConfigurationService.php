<?php

namespace App\Service;

use Symfony\Component\Yaml\Yaml;

class ConfigurationService
{
    /**
     * Get configuration by name.
     */
    public static function get(string $name): mixed
    {
        $configFile = file_get_contents(__DIR__ .'/../../config/appConfig.yaml');

        if (! $configFile) {
            return null;
        }

        $yaml = Yaml::parse($configFile);

        if (array_key_exists($name, $yaml)) {
            return $yaml[$name];
        }

        return null;
    }
}
