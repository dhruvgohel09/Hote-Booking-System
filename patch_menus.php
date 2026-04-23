<?php
$files = ['users.php', 'settings.php', 'rooms.php', 'facilities.php', 'bookings.php'];

foreach ($files as $file) {
    $path = __DIR__ . '/admin/' . $file;
    if (file_exists($path)) {
        $content = file_get_contents($path);
        
        // Skip if already has user_queries.php 
        if (strpos($content, 'user_queries.php') !== false) {
            continue;
        }

        $pattern = '/(<a href="facilities\.php"[^>]*>.*?(?:<\/a>\s*))/is';
        
        $replacement = "$1" . <<<HTML
            <a href="user_queries.php">
                <i class="bi bi-envelope"></i>
                <span>User Queries</span>
            </a>
HTML;
        
        $newContent = preg_replace($pattern, $replacement, $content, 1);
        file_put_contents($path, $newContent);
        echo "Updated $file\n";
    }
}
echo "Done";
