<?php
/**
 * Service to get categories.
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
 * Service enables getting categories from config.
 * 
 * @category Service
 * @package  MK\rss\Controller
 * @author   Maciej Kardel <maciej.kardel@gmail.com>
 * @license  MIT License
 * @link     https://github.com/Cardello9/MK-RSS
 */
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
