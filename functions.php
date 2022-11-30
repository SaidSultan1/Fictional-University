<?php

function university_files() {
    wp_enqueue_style( 'font-family ','//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i' );
    wp_enqueue_style( 'font-awesome','//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
    wp_enqueue_style('style-sheet', get_theme_file_uri('/build/style-index.css'));
    wp_enqueue_style('extra-styles', get_theme_file_uri('/build/index.css'));
    wp_enqueue_script('extra-script', get_theme_file_uri('/build/index.js'), array('jquery'),'1.0',true);

}


add_action( 'wp_enqueue_scripts', 'university_files' );

// adding title of web page 

function university_features(){
    add_theme_support('title-tag');
    register_nav_menu('headerMenuLocation', 'Header Menu Location');
}
add_action('after_setup_theme', 'university_features');


function university_adjust_queries($query) {
    if(!is_admin() && is_post_type_archive('events') && $query->is_main_query()){
        $today = date('Ymd'); 
        $query->set('meta_key', 'event-date');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'ASC');
        $query->set('meta_query', array(
            array(
              'key'=> 'event_date',
              'compare'=> '>=',
              'value'=> $today,
              'type'=>'numeric'
            )
          ));
    }
}
add_action('pre_get_posts', 'university_adjust_queries');
