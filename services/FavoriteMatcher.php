<?php

class FavoriteMatcher
{
    private array $indexedFavorites = [];

    public function __construct(array $favoritesFromDB)
    {
        $this->indexFavorites($favoritesFromDB);
    }

    // indexing favorites
    private function indexFavorites(array $favorites)
    {
        foreach ($favorites as $fav) {
            $district = $fav['district'] ?? null;
            $rooms    = $fav['rooms'] ?? null;

            if ($district === null) {
                continue;
            }

            $this->indexedFavorites[$district][$rooms][] = $fav;
        }
    }


    //  Match listings against favorites

    public function match(array $listings): array
    {
        $matched = []; // structure: [favorite_id => [ ['favorite'=>..., 'listing'=>...], ... ] ]

        foreach ($listings as $listing) {
            $district = $listing['district'] ?? null;
            $rooms    = $listing['rooms'] ?? null;

            if ($district === null) {
                continue;
            }


            $roomKeys = [$rooms, null];
            foreach ($roomKeys as $rk) {
                if (!isset($this->indexedFavorites[$district][$rk])) {
                    continue;
                }

                foreach ($this->indexedFavorites[$district][$rk] as $fav) {
                    if ($this->matchesFavorite($listing, $fav)) {
                        $favId = $fav['id'];

                        $matched[$favId][] = [
                            'favorite' => $fav,
                            'listing'  => $listing,
                        ];

                        break 2;
                    }
                }
            }
        }

        return $matched;
    }

    //    range check
    private function matchesFavorite(array $listing, array $fav): bool
    {
        $m2    = $listing['m2']    ?? null;
        $floor = $listing['floor'] ?? null;
        $price = $listing['price'] ?? null;

        if ($m2 === null || $floor === null || $price === null) {
            return false;
        }

        $m2Min    = $fav['m2_min']    ?? PHP_INT_MIN;
        $m2Max    = $fav['m2_max']    ?? PHP_INT_MAX;
        $floorMin = $fav['floor_min'] ?? PHP_INT_MIN;
        $floorMax = $fav['floor_max'] ?? PHP_INT_MAX;
        $priceMin = $fav['price_min'] ?? 0;
        $priceMax = $fav['price_max'] ?? PHP_INT_MAX;

        return ($m2    >= $m2Min   && $m2    <= $m2Max)
            && ($floor >= $floorMin && $floor <= $floorMax)
            && ($price >= $priceMin && $price <= $priceMax);
    }
}
