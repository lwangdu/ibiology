<?php

/*
 Title: Custom Fields Box
 Post Type: page 
 */

piklist('field', array(
    'type' => 'textarea',
    'scope' => 'post_meta',
    'field' => 'page_desc',
    'label' => 'Short Description',
    'description' => 'Page excerpt for search results',
    'value' => '',
    'attributes' => array(
        'class' => 'text'
        ),
    'position' => 'wrap'
    )
);

