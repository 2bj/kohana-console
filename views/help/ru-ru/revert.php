<?php defined('SYSPATH') or die('No direct script access.');?>
Increase performance by compose often used classes in one file and replace index.php. Save old index.php as index_old.php. This command can be reverted using -r

usage:
lite [-r]

options:
-r - revert old index.php file
