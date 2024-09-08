<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="container">
        <p>this site for viewing appartments sales</p>
        <a href="feed.php">click</a>
        <?php
        require_once 'dbh.inc.php';
        $query = "SELECT title FROM ss_rss_riga WHERE pagasts = 'centrs';";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo '<pre>';
        var_dump($results);
        echo '</pre>';

        ?>
    </div>
</body>

</html>