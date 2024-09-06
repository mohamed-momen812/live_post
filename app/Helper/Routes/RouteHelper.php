<?php

namespace App\Helper\Routes;

class RouteHelper
{
    public static function includeRouteFiles(string $folder) {

        // iterare thru the v1 folder recursively, instead of muanlly require all file
        $dirIterator = new \RecursiveDirectoryIterator($folder);

        /**
         * @var \RecursiveDirectoryIterator
         * @var \RecursiveIteratorIterator $it
         * */
        $iterator = new \RecursiveIteratorIterator($dirIterator); // it walks through all files and subdirectories within the v1 folder.

        while($iterator->valid()) {
            if(!$iterator->isDot()
                && $iterator->isFile()
                && $iterator->isReadable()
                && $iterator->current()->getExtension() == "php") {
                require $iterator->key(); // require the file
            }
            $iterator->next(); // move to the next iterator
        }

    // require __DIR__ . "/api/v1/users.php";
    // require __DIR__ . "/api/v1/posts.php";
    // require __DIR__ . "/api/v1/comments.php";
    }
}
