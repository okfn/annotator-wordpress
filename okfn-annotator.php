<?php

/**
 * @package OkfnAnnotator
 * @author Andrea Fiore
 * @author Nick Stenning
 *
 * Main plugin controller
 *
 */


/*
Plugin Name: Annotator
Plugin URI: https://github.com/okfn/annotator-wordpress
Description: Adds inline annotations to Wordpress using the amazing <a href="http://annotateit.org">Annotator</a> widget (by the Open Knowledge Foundation).
Version: 0.4
Author: Open Knowledge Foundation
Author URI: http://okfn.org/projects/annotator/
License: GPLv2 or later
*/

foreach (array(
             'lib/wp-pluggable',
             'vendor/Mustache',
             'vendor/php-jwt/src/JWT',
             'lib/okfn-utils',
             'lib/okfn-base',
             'lib/okfn-annot-settings',
             'lib/okfn-annot-content-policy',
             'lib/okfn-annot-injector',
             'lib/okfn-annot-factory',
         ) as $lib) require_once("${lib}.php");

$settings = new OkfnAnnotSettings;

//todo make configurable
$factory = new OkfnAnnotFactory($settings);
$content_policy = new OkfnAnnotContentPolicy($settings);

$init_factory = function () use ($factory, $content_policy, $settings){
    $options_values = $settings->get_options_values();
    $logged_in_only = $options_values["logged_in-only"] == "on";

    if (!$logged_in_only || is_user_logged_in()) {
        $injector = new OkfnAnnotInjector($factory, $content_policy);
        $injector->inject();
    }
};

//todo deniso make is_user_logged_in configurable
if (!is_admin()) {
    add_action('init', $init_factory);
}
?>
