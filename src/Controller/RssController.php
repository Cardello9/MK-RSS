<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RssController extends AbstractController
{
    /**
     * Display RSS feed.
     */
    #[Route('/{category}', name: 'category_show')]
    public function displayRss(Request $request, string $category = "technology"): Response
    {
        $perPage = 12;
        $pageNum = $request->query->getInt('p', 1);

        // URL addresses of RSS feeds.
        switch ($category) {
            case "business":
                $urls = [
                    'https://rss.nytimes.com/services/xml/rss/nyt/Business.xml',
                    'https://fortune.com/feed/fortune-feeds/?id=3230629',
                ];
                break;
            case "usa":
                $urls = [
                    'https://rss.nytimes.com/services/xml/rss/nyt/US.xml',
                    'https://moxie.foxnews.com/google-publisher/us.xml',
                ];
                break;
            case "europe":
                $urls = [
                    'https://rss.nytimes.com/services/xml/rss/nyt/Europe.xml',
                    'https://euronews.com/rss?format=mrss&level=vertical&name=my-europe'

                ];
                break;
            default:
                $urls = [
                    'https://www.cnet.com/rss/news/',
                    'https://rss.nytimes.com/services/xml/rss/nyt/Technology.xml',
                    'https://moxie.foxnews.com/google-publisher/tech.xml',
                ];
        }

        // Get all news from feeds.
        $allNews = [];
        foreach ($urls as $url) {
            $rssFeed = simplexml_load_file($url);
            foreach ($rssFeed->channel->item as $feedItem) {
                $attributes =  $feedItem->children('media', true)->content->attributes();

                if ($attributes) {
                    $news['imageUrl'] = $attributes->url;
                } else {
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
            'category' => $category,
            'pagesCount' => $pagesCount,
            'pageNum' => $pageNum,
        ]);
    }
}