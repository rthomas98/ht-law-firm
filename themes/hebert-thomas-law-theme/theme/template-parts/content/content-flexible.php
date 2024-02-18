<?php
/**
 * Template part for displaying acf flexible content
 *
 * @package Empuls3_Theme
 */

?>

<?php


if ( get_field( 'blocks' ) ) :

	while ( has_sub_field( 'blocks', get_the_ID() ) ) :


	endwhile;
endif;
?>
