<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\CategoryService;
use App\Class\NewsCategory;

class RssController extends AbstractController
{
    /**
     * Display RSS feed.
     */
    #[Route('/{categoryName}', name: 'category_show')]
    public function displayRss(Request $request, string $categoryName = "technology"): Response
    {
        $perPage = 12;
        $pageNum = $request->query->getInt('p', 1);

        $categories = CategoryService::getCategories();

        $selectedCategory = new NewsCategory();
        $selectedCategoryData = CategoryService::getCategoryByName($categoryName);
        
        $selectedCategory->setTitle($selectedCategoryData['title']);
        $selectedCategory->setShortDescription($selectedCategoryData['shortDescription']);
        $selectedCategory->setFullDescription($selectedCategoryData['fullDescription']);

        // Get all news from feeds.
        $allNews = [];
        foreach($selectedCategoryData['urls'] as $url) {
            $rssFeed = simplexml_load_file($url);
            foreach ($rssFeed->channel->item as $feedItem) {
                $attributes =  $feedItem->children('media', true)->content->attributes();

                if ($attributes) {
                    $news['imageUrl'] = $attributes->url;
                } else {
                    // TO DO: Add placeholder image.
                    $news['imageUrl'] = null;
                }

                $news['feedItem'] = $feedItem;
                $allNews[] = $news;
            }
        }

        // Sort news by publication date.
        usort($allNews, function($a, $b) {
            $aPubDate = date('Y-m-d H:i:s', strtotime($a['feedItem']->pubDate));
            $bPubDate = date('Y-m-d H:i:s',strtotime($b['feedItem']->pubDate));
            if ($aPubDate < $bPubDate) {
                return 1;
            } else {
                return -1;
            }
        });

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
            'selectedCategory' => $selectedCategory,
            'selectedCategoryName' => $categoryName,
            'pagesCount' => $pagesCount,
            'pageNum' => $pageNum,
        ]);
    }
}