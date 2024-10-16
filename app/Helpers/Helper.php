<?php

if (!function_exists('formatSlug')) {
    function formatSlug($slug)
    {
        return ucwords(str_replace('_', ' ', $slug));
    }
}