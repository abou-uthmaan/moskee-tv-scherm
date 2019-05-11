<?php
$timestamp = time();
setlocale(LC_ALL, 'nl_NL');
echo ucfirst(strftime('%A %e %B %Y', $timestamp));
?>