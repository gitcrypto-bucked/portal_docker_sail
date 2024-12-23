<?php 

$target =__DIR__.'/storage/clientes';
$link = __DIR__.'/public/storage';
symlink($target, $link);
echo "Done";
