<?php

class ListingController
{

    private Listing $listing;
    private PDO $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->listing = new Listing($pdo);
    }
}
