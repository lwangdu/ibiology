<?php
/**
 * iBiology in.
 *
 * This file adds the speakers page template to the iBiology Theme.
 *
 * @package iBiology
 * @author  Lobsang Wangdu
 * @license GPL-2.0+
 * @link    http://www.ibiology/
 */

//* Template Name: Speakers 
?>

<?php the_field( 'first_name' ); ?>
<?php the_field( 'last_name' ); ?>
<?php the_field( 'speaker_affiliation' ); ?>
<?php the_field( 'speaker_talk_title' ); ?>
<?php

//* Run the Genesis loop
genesis();
