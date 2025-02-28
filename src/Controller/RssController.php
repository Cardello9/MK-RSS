<?php
/**
 * Displays front views.
 * php version 8.2.4
 * 
 * @category Controller
 * @package  MK\rss\Controller
 * @author   Maciej Kardel <maciej.kardel@gmail.com>
 * @license  MIT License
 * @link     https://github.com/Cardello9/MK-RSS
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\BaseController;
use App\Service\CategoryService;
use App\Service\RssService;
use App\Service\ConfigurationService;

/**
 * Shows news from RSS,
 *
 * @category Controller
 * @package  MK\rss\Controller
 * @author   Maciej Kardel <maciej.kardel@gmail.com>
 * @license  MIT License
 * @link     https://github.com/Cardello9/MK-RSS
 */
class RssController extends BaseController
{
    /**
     * Display homepage.
     * 
     * @return Response
     */
    #[Route('/', name: 'homepage_show')]
    public function displayHome(): Response
    {
        $homePageNewsPerCategory = ConfigurationService::get(
            'homepage_news_per_category'
        );

        $highlitedCategoryData = $this->categories[
            array_key_first($this->categories)
        ];
        $highlitedNews = RssService::getNewsFromUrls(
            $highlitedCategoryData['urls'], 
            $homePageNewsPerCategory
        );

        $standardCategories = $this->categories;
        array_shift($standardCategories);

        $standardCategoriesNews = [];
        foreach ($standardCategories as $standardCategoryName => $standardCategory) {
            $categoryNews = RssService::getNewsFromUrls(
                $standardCategory['urls'], 
                $homePageNewsPerCategory
            );

            $standardCategoriesNews[$standardCategoryName] = $categoryNews;
        }

        return $this->render(
            'home.html.twig', [
            'categories' => $this->categories,
            'highlitedCategory' => $highlitedCategoryData,
            'highlitedCategoryName' => array_key_first($this->categories),
            'highlitedNews' => $highlitedNews,
            'standardCategories' => $standardCategories,
            'standardCategoriesNews' => $standardCategoriesNews,
            ]
        );
    }

    /**
     * Display RSS feed.
     * 
     * @param Request $request      request data
     * @param string  $categoryName name of category
     * 
     * @return Response
     */
    #[Route('/category/{categoryName}', name: 'category_show')]
    public function displayRss(Request $request, string $categoryName): Response
    {
        $perPage = ConfigurationService::get('category_per_page');
        $limit = $perPage * ConfigurationService::get('category_pages_number');
        $pageNum = $request->query->getInt('p', 1);

        $categories = $this->categories;

        $selectedCategoryData = $this->categories[$categoryName];

        $allNews = RssService::getNewsFromUrls(
            $selectedCategoryData['urls'], 
            $limit
        );

        $newsCount = count($allNews);
        $pagesCount = ceil($newsCount / $perPage);

        // Split news in pages.
        $allNews = array_chunk($allNews, $perPage);

        if (array_key_exists($pageNum-1, $allNews)) {
            $allNews = $allNews[$pageNum-1];
        } else {
            $allNews = null;
        }
        
        return $this->render(
            'rss.html.twig', [
            'allNews' => $allNews,
            'categories' => $categories,
            'selectedCategory' => $selectedCategoryData,
            'selectedCategoryName' => $categoryName,
            'pagesCount' => $pagesCount,
            'pageNum' => $pageNum,
            ]
        );
    }
}
