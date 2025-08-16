<?php

// SS.lv RSS feed parser

function dd($data)
{
    echo '<pre>';
    die(var_dump($data));
    echo '</pre>';
}
class RssParser
{

    private $object;

    public function __construct($feed_url)
    {
        $this->object = new DOMDocument();
        $this->object->load($feed_url);
    }


    public function parse()
    {

        $items = [];

        $content = $this->object->getElementsByTagName('item');

        foreach ($content as $row) {

            $title = trim($row->getElementsByTagName('title')->item(0)->nodeValue);
            $description = $row->getElementsByTagName("description")->item(0)->nodeValue;
            $pubDateRaw = $row->getElementsByTagName("pubDate")->item(0)->nodeValue;
            $pubDate = (new DateTime($pubDateRaw))->format('Y-m-d H:i:s');
            // $pubDate = $row->getElementsByTagName("pubDate")->item(0)->nodeValue;
            $link = $row->getElementsByTagName("link")->item(0)->nodeValue;

            // Replace <br> and <br/> with newlines - because of specifics of description field
            $description = str_replace(['<br>', '<br/>'], "\n", $description);

            // Split description by newline characters
            $lines = explode("\n", $description);
            // print_r($title);
            echo '<pre>';
            var_dump($lines);
            echo '</pre>';

            $data = [
                // 'description' => $description,
                'title' => $title,
                'pubDate' => $pubDate,
                'link' => $link,
                'pagasts' => '',
                'stavs' => '',
                'serija' => '',
                'cena' => '',
                'm2' => '',
                'istabas' => '',
                'iela' => '',
                'imgSrc' => '',
                'hash' => '',
            ];

            // dd($data);

            foreach ($lines as $line) {
                if (strpos($line, 'Pagasts:') !== false) {

                    $data['pagasts'] = strip_tags(trim($this->extractValue($line, 'Pagasts:')));
                }
                if (strpos($line, 'Stāvs:') !== false) {

                    $data['stavs'] = strip_tags(trim($this->extractValue($line, 'Stāvs:')));
                }

                if (strpos($line, 'Sērija:') !== false) {

                    $data['serija'] = strip_tags(trim($this->extractValue($line, 'Sērija:')));
                }

                if (strpos($line, 'Cena:') !== false) {

                    $rawPrice = strip_tags(trim($this->extractValue($line, 'Cena:')));

                    // Remove any non-numeric characters except comma and dot 
                    $cena = preg_replace('/[^\d,\.]/', '', $rawPrice);


                    $data['cena'] = str_replace(',', '', $cena);
                }


                if (strpos($line, 'm2:') !== false) {

                    $data['m2'] = strip_tags(trim($this->extractValue($line, 'm2:')));
                }
                if (strpos($line, 'Ist.:') !== false) {

                    $data['istabas'] = strip_tags(trim($this->extractValue($line, 'Ist.:')));
                }
                if (strpos($line, 'Iela:') !== false) {

                    $data['iela'] = $this->removeDuplicateWords(strip_tags(trim($this->extractValue($line, 'Iela:'))));
                }
                if (strpos($line, '<img') !== false) {
                    $data['imgSrc'] = $this->extractImageSrc($line);
                }
            }


            $data['hash'] = $this->createHash($title, $pubDate);
            $items[] = $data;
        }
        // dd($items);
        return $items;

        /**
         * Helper functions
         */
    }
    public function createHash($title, $pubDate)
    {
        $data = $title . $pubDate;
        $hashedValue = hash('crc32', $data);
        return $hashedValue;
    }
    // helper func to extract specific items
    public function extractValue($line, $key)
    {
        $pos = strpos($line, $key);
        if ($pos !== false) {
            return substr($line, $pos + strlen($key));
        }
        return '';
    }
    public function removeDuplicateWords($string)
    {

        $words = explode(' ', $string);


        $uniqueWords = array_unique($words);


        $cleanedString = implode(' ', $uniqueWords);

        return $cleanedString;
    }
    public function extractImageSrc($html)
    {
        $doc = new DOMDocument();
        @$doc->loadHTML($html);

        $imgTags = $doc->getElementsByTagName('img');

        if ($imgTags->length > 0) {
            return $imgTags->item(0)->getAttribute('src');
        }

        return '';
    }
}
