<?php

function pageBanner ($args = NULL) {

    if (!$args['title']) {
         $args['title'] = get_the_title();
    }

    if (!$args['subtitle']) {
        $args['subtitle'] = get_field('page_banner_title');
    }

    if (!$args['photo']) {
        if (get_field('page_banner_background_image')) {
            $args['photo'] =get_field('page_banner_background_image') ['sizes']['pageBanner'];
        }else {
            $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
        }
    }

    ?>
     <div class="page-banner">
      <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?> )"></div>
      <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
        <div class="page-banner__intro">
          <p><?php echo $args['subtitle'] ?></p>
        </div>
      </div>
    </div>
<?php }

function university_files() {
    wp_enqueue_style( 'font-family ','//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i' );
    wp_enqueue_style( 'font-awesome','//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
    wp_enqueue_style('style-sheet', get_theme_file_uri('/build/style-index.css'));
    wp_enqueue_style('extra-styles', get_theme_file_uri('/build/index.css'));
    wp_enqueue_script('extra-script', get_theme_file_uri('/build/index.js'), array('jquery'),'1.0',true);
    wp_enqueue_script('googleMap','//maps.googleapi.com/maps/api/js?key=AIzaSyBmk41lhueG6v0cdzI8vwaFJAjgo8kW8Qk',NULL,'1.0',true);
    wp_localize_script('getURL','universityData', array(
        'root_url' => get_site_url()
    ));


}


add_action( 'wp_enqueue_scripts', 'university_files' );

// adding title of web page 

function university_features(){
    add_theme_support('title-tag');
    register_nav_menu('headerMenuLocation', 'Header Menu Location');
    add_theme_support('post-thumbnails');
    add_image_size('professorLandscape', 400, 260, true);
    add_image_size('professorPotrait', 480, 650 , true);
    add_image_size('pageBanner', 1500, 350 , true);
}
add_action('after_setup_theme', 'university_features');


function university_adjust_queries($query) {
    if(!is_admin() && is_post_type_archive('campus') && $query->is_main_query()) {
        $query->set('post_per_page', -1);
    }
    if(!is_admin() && is_post_type_archive('program') && $query->is_main_query()) {
        $query->set('orderby','title');
        $query->set('order','ASC');
    }

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

function universityMapKey($api) {
    $api['key'] = 'AIzaSyBmk41lhueG6v0cdzI8vwaFJAjgo8kW8Qk';
    return $api;
  }
  
  add_filter('acf/fields/google_map/api', 'universityMapKey');