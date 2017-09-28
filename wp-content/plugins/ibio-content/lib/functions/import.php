<?php

// functions for importing and comparing data.

function ibio_compare_data( $old, $new ){
	if ( empty( $new ) ){
		if ( empty( $old ) ){
			return 'n/a';
		} else {
			return $old;
		}
	}
	
	return $new;

}