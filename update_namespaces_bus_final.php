<?php

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('diurne_api/src/MobileAppApi/Bus'));

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        $newContent = preg_replace_callback(
            '/use App\\\\MobileAppApi\\\\Bus\\\\(Command|Query)\\\\MobileApp\\\\([a-zA-Z]+)\\\\(?!([a-zA-Z]+)\\\\)([a-zA-Z]+)(Command|Query|Response|Handler);/',
            function ($matches) {
                $type = $matches[1]; 
                $entity = $matches[2]; 
                $class = $matches[4] . $matches[5]; 
                $folder = $matches[4]; 
                
                return "use App\\MobileAppApi\\Bus\\$type\\MobileApp\\$entity\\$folder\\$class;";
            },
            $content
        );
        
        if ($newContent !== $content) {
            file_put_contents($file->getPathname(), $newContent);
            echo "Updated " . $file->getPathname() . "\n";
        }
    }
}
