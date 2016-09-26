<?php

  $url = 'https://graph.facebook.com/v2.5/881196305317844/picture?width=100&height=100';
  $file = '/var/www/html/cwa/frontend/web/upload/1.jpg';

  $im = imagecreatefromstring(file_get_contents($url));
  imagejpeg($im, $file, 80);

  // if ($im !== false) {
  //     header('Content-Type: image/png');
  //     imagepng($im);
  //     imagedestroy($im);
  // }
  // else {
  //     echo 'Произошла ошибка.';
  // }

  //file_put_contents($file, 'hi');

 ?>
