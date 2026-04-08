<?php
echo "PHP работает!<br>";
echo "PHP-FPM статус: " . (function_exists('phpinfo') ? "OK" : "ERROR");
phpinfo();
?>
