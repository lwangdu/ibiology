<?php

/*
 Title: Custom Fields Box
 Post Type: post 
 */

/*piklist('field', array(
    'type' => 'textarea',
    'scope' => 'post_meta',
    'field' => 'short_descr',
    'label' => 'Short Description',
    'description' => 'Post excerpt for search results',
    'value' => '',
    'attributes' => array(
        'class' => 'text'
        ),
    'position' => 'wrap'
    )
);*/

piklist('field', array(
    'type' => 'text',
    'scope' => 'post_meta',
    'field' => 'duration',
    'label' => 'Time',
    'description' => '',
    'value' => '',
    'attributes' => array(
        'class' => 'text'
        ),
    'position' => 'wrap'
    )
);

?>
