<?php
echo "PHP: " . PHP_VERSION . "<br>";
echo "ICU: " . INTL_ICU_VERSION . "<br>";
echo "ICU Data: " . INTL_ICU_DATA_VERSION . "<br>";

$res = mail("mosnovostroy@ya.ru", "My Subject", "mY TEXT");

if( $res == true )
    echo "Successfully sent<br>";
else
    echo "Not sent(<br>";

phpinfo();
?>
