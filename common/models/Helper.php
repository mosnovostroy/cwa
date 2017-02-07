<?php

namespace common\models;

class Helper
{
    // Естественное округление расстояний - возвращает значение с указанием единицы измерения
    public static function formatDist($dist)
    {
        if (!$dist) {
            return '';
        } else if ($dist < 1000) {
            return $dist.' м';
        } else if ($dist < 20000) {
            return (round($dist/1000, 1)).' км';
        } else if ($dist < 200000) {
            return (round($dist/1000, 0)).' км';
        } else {
            return (round($dist/1000, 1)).' км';
        }
    }
}

?>
