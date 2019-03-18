<?php

$plik = fopen('format.csv', 'r');

$result = fgets($plik);
echo $result;
fclose($plik);