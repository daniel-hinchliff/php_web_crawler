<?php

namespace HDWallpapers;

class Navigator extends \Crawler\Navigator
{
    public function filter(&$url, $current_url)
    {
        return preg_match(WallpaperPagePattern, $url)
            && preg_match(ListingPagePattern, $current_url);
    }
}
