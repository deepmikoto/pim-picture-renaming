<?php

$mapping = [];

$file = fopen('ean_parentid.csv', 'r');
while (($line = fgetcsv($file, null, ';')) !== false) {
    $mapping['ean_' . $line[0]] = $line[1];
}

fclose($file);

chmod('output', 0777);

$outputFiles = scandir('output/');
foreach ($outputFiles as $outputFile) {
    if (!is_file($outputFile)) {
        continue;
    }
    unlink('output/' . $outputFile);
}

chmod('output', 0777);


$inputFiles = scandir('input/', 1);

foreach ($inputFiles as $inputFile) {
    $fileEan = explode('_', $inputFile);
    $fileEan = reset($fileEan);
    $fileExt = explode('.', $inputFile);
    $fileExt = $fileExt[count($fileExt) - 1];
    if (array_key_exists('ean_' . $fileEan, $mapping)) {
        $newFileName = $mapping['ean_' . $fileEan] . '-' . (false !== strpos($inputFile, 'Front') ? '2' : '3') . '.' . $fileExt;

        $originalFile = file_get_contents('input/' . $inputFile);

        echo 'Renaming ' . $inputFile . ' tot ' . $newFileName . PHP_EOL;
        copy('input/' . $inputFile, 'output/' . $newFileName);
    } else {
        if (in_array($fileEan, ['.', '..', '.gitkeep'])) continue;

        echo 'No mapping found for ean: ' . $fileEan . PHP_EOL;
    }
}

$inputFiles_fullBody = scandir('input_fullbody/', 1);

foreach ($inputFiles_fullBody as $inputFile) {
    $fileEan = explode('_', $inputFile);
    $fileEan = reset($fileEan);
    $fileExt = explode('.', $inputFile);
    $fileExt = $fileExt[count($fileExt) - 1];
    if (array_key_exists('ean_' . $fileEan, $mapping)) {
        $newFileName = $mapping['ean_' . $fileEan] . '-' . (false !== strpos($inputFile, 'Front') ? '9' : '10') . '.' . $fileExt;

        $originalFile = file_get_contents('input_fullbody/' . $inputFile);

        echo 'Renaming ' . $inputFile . ' to ' . $newFileName . PHP_EOL;
        copy('input_fullbody/' . $inputFile, 'output/' . $newFileName);
    } else {
        if (in_array($fileEan, ['.', '..', '.gitkeep'])) continue;

        echo 'No mapping found for ean: ' . $fileEan . PHP_EOL;
    }
}

echo 'Done';
