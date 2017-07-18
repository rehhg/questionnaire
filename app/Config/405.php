<?php  header("HTTP/1.0 405 Method Not Allowed"); ?>
<!DOCTYPE html>
<html><head>
<title>405 Not Allowed</title>
</head><body>
<h1>Not Allowed</h1>
<p>The requested method <?= $_SERVER["PHP_SELF"]; ?> is not allowed on this page.</p>
<hr>
<address>Server software is <?= $_SERVER['SERVER_SOFTWARE']; ?> and host is &quot;<?= $_SERVER['SERVER_NAME']; ?>&quot;</address>
</body></html>