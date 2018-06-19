<?php

foreach (new DirectoryIterator(realpath('../db/migration_source')) as $fileInfo) {
    if ($fileInfo->isDot()) {
        continue;
    }

    $source = file_get_contents($fileInfo->getPathname());
    $update = str_replace('{$tblprefix}', 'TESTING___', $source);
    $new_file = substr('../db/migrations/'.$fileInfo->getFilename(), 0, -1);

    file_put_contents($new_file, $update);
}
