<?php
echo "PHP: " . PHP_VERSION . "<br>";
echo "ICU: " . INTL_ICU_VERSION . "<br>";
echo "ICU Data: " . INTL_ICU_DATA_VERSION . "<br>";

$options = ['a1' => true, 'a2' => [], 'a3' => false];

echo "options['a0']: ";
if (count($options['a0']))
    echo "count > 0";
else
    echo "count not > 0";
echo "<br>";


echo 'Starting... '.'<br>';

echo "options['a0']: ";
if ($options['a0'])
    echo "yes";
else
    echo "no";
echo "<br>";

echo "options['a1']: ";
if ($options['a1'])
    echo "yes";
else
    echo "no";
echo "<br>";

echo "options['a2']: ";
if ($options['a2'])
    echo "yes";
else
    echo "no";
echo "<br>";

echo "options['a3']: ";
if ($options['a3'])
    echo "yes";
else
    echo "no";
echo "<br>";


echo "options['a0']: ";
if (!$options['a0'])
    echo "yes";
else
    echo "no";
echo "<br>";

echo "options['a1']: ";
if (!$options['a1'])
    echo "yes";
else
    echo "no";
echo "<br>";

echo "options['a2']: ";
if (!$options['a2'])
    echo "yes";
else
    echo "no";
echo "<br>";

echo "options['a3']: ";
if (!$options['a3'])
    echo "yes";
else
    echo "no";
echo "<br>";



echo 'finish'.'<br>';


?>
