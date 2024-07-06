<?php
/*
Plugin Name: TRecent Posts
Description: Добавляет шорткод, который выводит последние N записей
Author: Alekxej Babushkin
Version: 1.0
*/

require_once dirname(__FILE__) . '/vendor/autoload.php';
require_once dirname(__FILE__) . '/ErrorLogLogger.php';
require_once dirname(__FILE__) . '/TrcSettingsPage.php';

// [trc_get_posts]
function trp_get_posts($atts)
{
  $options = get_option('trecent_posts', ['posts_limit' => 10]);

  $recent_args = [
    "post_type" => "post",
    'post__not_in' => get_option("sticky_posts"),
    "posts_per_page" => $options['posts_limit'],
    "orderby" => "date",
    "order" => "DESC",
  ];

  $recent_posts = new WP_Query($recent_args);

  $output = "";
  if ($recent_posts->have_posts()) {
    while ($recent_posts->have_posts()) {
      $recent_posts->the_post();
      $output .= '<a href="' . get_permalink() . '">' . get_the_title() . '</a> (' . get_the_date() . ')<br>';
    }
  }
  return $output;
}

add_shortcode('trc_get_posts', 'trp_get_posts');
