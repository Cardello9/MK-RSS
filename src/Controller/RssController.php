<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\BaseController;
use App\Service\CategoryService;
use App\Service\RssService;

class RssController extends BaseController
{
    /**
     * Display homepage.
     */
    #[Route('/', name: 'homepage_show')]
    public function displayHome(): Response
    {
        $highlitedCategoryData = $this->categories[array_key_first($this->categories)];
        $highlitedNews = RssService::getNewsFromUrls($highlitedCategoryData['urls'], 3);

        $standardCategories = $this->categories;
        array_shift($standardCategories);

        $standardCategoriesNews = [];
        foreach ($standardCategories as $standardCategoryName => $standardCategory) {
            $categoryNews = RssService::getNewsFromUrls($standardCategory['urls'], 3);

            $standardCategoriesNews[$standardCategoryName] = $categoryNews;
        }

        return $this->render('home.html.twig', [
            'categories' => $this->categories,
            'highlitedCategory' => $highlitedCategoryData,
            'highlitedCategoryName' => array_key_first($this->categories),
            'highlitedNews' => $highlitedNews,
            'standardCategories' => $standardCategories,
            'standardCategoriesNews' => $standardCategoriesNews,
        ]);
    }

    /**
     * Display RSS feed.
     */
    #[Route('/category/{categoryName}', name: 'category_show')]
    public function displayRss(Request $request, string $categoryName): Response
    {
        $perPage = 12;
        $limit = $perPage * 3;
        $pageNum = $request->query->getInt('p', 1);

        $categories = $this->categories;

        $selectedCategoryData = $this->categories[$categoryName];

        $allNews = RssService::getNewsFromUrls($selectedCategoryData['urls'], $limit);

        $newsCount = count($allNews);
        $pagesCount = ceil($newsCount / $perPage);

        // Split news in pages.
        $allNews = array_chunk($allNews, $perPage);

        if (array_key_exists($pageNum-1, $allNews)) {
            $allNews = $allNews[$pageNum-1];
        } else {
            $allNews = null;
        }
        
        return $this->render('rss.html.twig', [
            'allNews' => $allNews,
            'categories' => $categories,
            'selectedCategory' => $selectedCategoryData,
            'selectedCategoryName' => $categoryName,
            'pagesCount' => $pagesCount,
            'pageNum' => $pageNum,
        ]);
    }
}