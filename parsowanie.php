<?php
$file = fopen('paginiaurii2.csv','r');

$filesize = $filesize('paginiaurii2.csv');
echo $filesize;
$zawartosc = fread($file, 8192);
echo $zawartosc;

fclose($file);