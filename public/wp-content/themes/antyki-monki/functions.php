<?php

function wpAntykiOlxAcfSetSavePoint($path)
{
    $path = dirname(__FILE__) . '/inc/acf-json';
    return $path;
}

function wpAntykiOlxAcfSetLoadPoint($paths)
{
    $paths[0] = dirname(__FILE__) . '/inc/acf-json';
    return $paths;
}

add_filter('acf/settings/save_json', 'wpAntykiOlxAcfSetSavePoint');
add_filter('acf/settings/load_json', 'wpAntykiOlxAcfSetLoadPoint');
