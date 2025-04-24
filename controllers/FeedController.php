<?php

require '../parsers/RssParser.php';
require "../models/Listing.php";
require '../config/dbh.inc.php';

// function dd($data)
// {
//     echo '<pre>';
//     die(var_dump($data));
//     echo '</pre>';
// }
// dd($pdo);

class FeedController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
    public function updateFeed($feed_url)
    {
        $parser = new RssParser($feed_url);
        $listings = $parser->parse();

        foreach ($listings as $listing) {

            (new Listing($listing, $this->pdo))->insertInDb();
        }
    }
}

$controller = new FeedController($pdo);
$controller->updateFeed('../data.xml');
