<?php

namespace App\Service;

class RssService
{
    /**
     * Downloads news from RSS channels.
     */
    public static function getNewsFromUrls(array $urls, int $limit = 36): array
    {
        $allNews = [];
        $allNewsCount = 0;
        $rssLimit = ConfigurationService::get('rss_limit');

        foreach($urls as $url) {
            $rssFeed = simplexml_load_file($url);
            foreach ($rssFeed->channel->item as $feedItem) {
                
                if ($allNewsCount >= $rssLimit) {
                    break;
                }

                $attributes =  $feedItem->children('media', true)->content->attributes();

                if ($attributes) {
                    $news['imageUrl'] = $attributes->url;
                } else {
                    $news['imageUrl'] = null;
                }

                $news['feedItem'] = $feedItem;
                $news['copyright'] = $rssFeed->channel->copyright;
                
                $allNews[] = $news;
                $allNewsCount += 1;
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

        return array_slice($allNews, 0, $limit);
    }
}