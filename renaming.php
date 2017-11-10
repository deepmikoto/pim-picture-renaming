<?php

$mapping = [];

$file = fopen('ean_parentid.csv', 'r');
while (($line = fgetcsv($file, null, ';')) !== false) {
    $mapping['ean_' . $line[0]] = $line[1];
}

fclose($file);

$inputFiles = scandir('input/', 1);
chmod('output', 0777);

$outputFiles = scandir('output/');
foreach ($outputFiles as $outputFile) {
    if (!is_file($outputFile)) {
        continue;
    }
    unlink('output/' . $outputFile);
}

chmod('output', 0777);

foreach ($inputFiles as $inputFile) {
    $fileEan = explode('_', $inputFile);
    $fileEan = reset($fileEan);
    $fileExt = explode('.', $inputFile);
    $fileExt = $fileExt[count($fileExt) - 1];
    if (array_key_exists('ean_' . $fileEan, $mapping)) {
        $newFileName = $mapping['ean_' . $fileEan] . '-' . (false !== strpos($inputFile, 'Front') ? '2' : '3') . '.' . $fileExt;

        $originalFile = file_get_contents('input/' . $inputFile);

        copy('input/' . $inputFile, 'output/' . $newFileName);


    } else {
        echo 'No mapping found for ean: ' . $fileEan . PHP_EOL;
    }
}

echo 'Done';
