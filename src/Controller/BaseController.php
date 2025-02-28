<?php
/**
 * Base Controller for front views.
 * php version 8.2.4
 * 
 * @category Controller
 * @package  MK\rss\Controller
 * @author   Maciej Kardel <maciej.kardel@gmail.com>
 * @license  MIT License
 * @link     https://github.com/Cardello9/MK-RSS
 */


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\CategoryService;

/**
 * Base Controller to extend.
 * 
 * @category Controller
 * @package  MK\rss\Controller
 * @author   Maciej Kardel <maciej.kardel@gmail.com>
 * @license  MIT License
 * @link     https://github.com/Cardello9/MK-RSS
 */
class BaseController extends AbstractController
{
    /**
     * News categories from configuration.
     *
     * @var mixed[] 
     */
    protected array $categories;

    /**
     * Prepare categories.
     */
    function __construct()
    {
        $this->categories = CategoryService::getCategories();
    }
}
