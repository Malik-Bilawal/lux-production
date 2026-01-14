<?php

if (!function_exists('getYouTubeID')) {
    function getYouTubeID($url)
    {
        preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $url, $matches);
        return $matches[1] ?? null;
    }
}
