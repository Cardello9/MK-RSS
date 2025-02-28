<?php
/**
 * Service to get configuration.
 * php version 8.2.4
 * 
 * @category Service
 * @package  MK\rss\Controller
 * @author   Maciej Kardel <maciej.kardel@gmail.com>
 * @license  MIT License
 * @link     https://github.com/Cardello9/MK-RSS
 */

namespace App\Service;

use Symfony\Component\Yaml\Yaml;

/**
 * Service to get parameters from configuration.
 * 
 * @category Service
 * @package  MK\rss\Controller
 * @author   Maciej Kardel <maciej.kardel@gmail.com>
 * @license  MIT License
 * @link     https://github.com/Cardello9/MK-RSS
 */
class ConfigurationService
{
    /**
     * Get configuration by name.
     * 
     * @param string $name name of configuration value
     * 
     * @return mixed
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
