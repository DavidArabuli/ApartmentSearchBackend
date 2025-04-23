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

        require __DIR__ . '/../core/Router.php';
        // require_once 'dbh.inc.php';
        // require_once __DIR__ . '/../config/dbh.inc.php';

        // $query = "SELECT title FROM listings WHERE district = 'centrs';";
        // $stmt = $pdo->prepare($query);
        // $stmt->execute();
        // $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // echo '<pre>';
        // var_dump($results);
        // echo '</pre>';

        ?>
    </div>
</body>

</html>