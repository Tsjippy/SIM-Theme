<?php
namespace SIMTHEME;
use SIM;

use Github\Exception\ApiLimitExceedException;

// https://github.com/KnpLabs/php-github-api 	-- github api
// https://github.com/michelf/php-markdown		-- convert markdown to html


/**
 * Adds a custom description to the plugin in the plugin page
 */
add_filter( 'themes_api', function ( $res, $action, $args ) {
	// do nothing if you're not getting plugin information or this is not our plugin
	if( 'plugin_information' !== $action || 'sim-theme' !== $args->slug) {
		return $res;
	}

	$github					= new SIM\GITHUB\Github();
	return $github->pluginData(THEME_PATH, 'Tsjippy', 'sim-theme', [
		'active_installs'	=> 2, 
		'donate_link'		=> 'harmseninnigeria.nl', 
		'rating'			=> 5, 
		'ratings'			=> [4,5,5,5,5,5], 
		'banners'			=> [
			'high'	=> SIM\PICTURESURL."/banner-1544x500.jpg",
			'low'	=> SIM\PICTURESURL."/banner-772x250.jpg"
		], 
		'tested'			=> '6.6.2'		
	]);

}, 10, 3);

add_filter( 'pre_set_site_transient_update_themes', function($transient){
	$github			= new SIM\GITHUB\Github();

	$item			= $github->getVersionInfo(THEME_PATH, 'Tsjippy', 'sim-theme');

	// Git has a newer version
	if(isset($item->new_version)){
		$transient->response[SIM\PLUGIN]	= $item;
	}else{
		$transient->no_update[SIM\PLUGIN]	= $item;
	}

	return $transient;
});

add_action('admin_menu', function(){
	add_submenu_page('themes.php', 'Update', 'Update', 'edit_theme_options', 'update', function($test){
		$github		= new SIM\GITHUB\Github();
		$release	= $github->getLatestRelease('tsjippy', 'SIM-Theme', true);
		$theme		= wp_get_theme('sim-theme');

		if(version_compare($release['tag_name'], $theme->version)){
			$url  		= wp_nonce_url( admin_url( 'update.php?action=upgrade-theme&amp;theme=' . urlencode( 'sim-theme' ) ), 'upgrade-theme_sim-theme' );

			$link   = "<a href='$url' class='update-link'>Update to {$release['tag_name']}</a>";
			echo "Checking for update<br>Current version $theme->version<br>Remote version {$release['tag_name']}<br>$link";
		}else{
			echo "Checking for update<br>No update available";
		}
	});
});