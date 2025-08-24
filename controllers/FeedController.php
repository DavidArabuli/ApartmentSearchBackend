<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../models/Favorite.php';
require_once __DIR__ . '/../parsers/RssParser.php';
require_once __DIR__ . '/../models/Listing.php';
require_once __DIR__ . '/../config/dbh.inc.php';
require_once __DIR__ . '/../services/FavoriteMatcher.php';
require_once __DIR__ . '/../controllers/NotificationController.php';

// require_once __DIR__ . '/../vendor/autoload.php';
// require_once __DIR__ . '/../models/Favorite.php';
// require '../parsers/RssParser.php';
// require "../models/Listing.php";
// require '../config/dbh.inc.php';
// require __DIR__ . '/../services/FavoriteMatcher.php';
// require_once __DIR__ . '/../controllers/NotificationController.php';

class FeedController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function updateFeed($feed_url)
    {

        $parser   = new RssParser($feed_url);
        $listings = $parser->parse();

        // Normalizing listings into consistent form 
        $normalizedListings = array_map(function ($l) {
            return [
                'district' => $l['pagasts'] ?? null,
                'floor'    => isset($l['stavs']) ? (int)$l['stavs'] : null,
                'price'    => isset($l['cena']) ? (float)$l['cena'] : null,
                'm2'       => isset($l['m2']) ? (float)$l['m2'] : null,
                'rooms'    => isset($l['istabas']) ? (int)$l['istabas'] : null,
                'title'    => $l['title']   ?? null,
                'pubDate'  => $l['pubDate'] ?? null,
                'link'     => $l['link']    ?? null,
                'series'   => $l['serija']  ?? null,
                'street'   => $l['iela']    ?? null,
                'imgSrc'   => $l['imgSrc']  ?? null,
                'hash'     => $l['hash']    ?? null,
            ];
        }, $listings);


        $favoritesFromDB = (new Favorite($this->pdo, 'favorites'))->getAll();


        $matcher = new FavoriteMatcher($favoritesFromDB);


        $matched = $matcher->match($normalizedListings);


        $listingModel = new Listing($this->pdo);
        foreach ($normalizedListings as $listing) {
            $listingModel->insertInDb($listing);
        }


        $notifications = [];
        foreach ($matched as $favId => $pairs) {
            if (empty($pairs)) {
                continue;
            }


            $favorite = $pairs[0]['favorite'];
            $email    = $favorite['email'] ?? null;

            if ($email) {
                $links = array_map(fn($p) => $p['listing']['link'], $pairs);

                $notifications[$favId] = [
                    'email' => $email,
                    'links' => $links,
                ];
            }
        }

        $notifier = new NotificationController($this->pdo);
        $notifier->send($notifications);
    }
}
