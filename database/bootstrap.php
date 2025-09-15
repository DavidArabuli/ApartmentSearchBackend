<?php


$scripts = [
    'create_tables.php',
    'create_table_favorites.php',
    'create_table_notifications.php',
];

foreach ($scripts as $script) {
    $path = __DIR__ . '/' . $script;
    if (file_exists($path)) {
        echo "Running $script...\n";
        include $path;
        echo "$script done.\n";
    } else {
        echo "Warning: $script not found!\n";
    }
}

echo "All bootstrap scripts completed.\n";
