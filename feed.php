<?php


// https://www.ss.lv/lv/real-estate/flats/riga/sell/rss/
$feed_url = "data.xml";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="container">
        <br>
        <h2>RSS feed</h2>
        <br>
        <?php
        require_once "insert.php";
        // require_once "dbh.inc.php";
        require_once __DIR__ . '/../config/dbh.inc.php';

        require_once "RssParser.php";
        $gather = new RssParser($feed_url);
        $gather->gatherData();
        ?>
    </div>
</body>

</html>