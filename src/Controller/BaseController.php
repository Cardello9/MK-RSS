<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\CategoryService;

class BaseController extends AbstractController
{
    protected $categories;

    function __construct()
    {
        $this->categories = CategoryService::getCategories();
    }
}