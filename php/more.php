<?php
namespace SIMTHEME;

//Only show read more on home and news page
add_filter( 'excerpt_more', function ( $more ) {
	$url	= get_the_permalink();

	return "<div class='moretag-wrapper'><a class='moretag' href='$url'><span>Read More »</span></a></div>";
}, 999  );