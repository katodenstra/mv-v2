<?php

function mvillada_portfolio_setup()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('menus');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script'
    ));

    register_nav_menus(array(
        'primary' => __('Primary Menu', 'mvillada-portfolio'),
    ));
}
add_action('after_setup_theme', 'mvillada_portfolio_setup');

function mvillada_register_case_study_cpt()
{
    $labels = array(
        'name' => __('Case Studies', 'mvillada-portfolio'),
        'singular_name' => __('Case Study', 'mvillada-portfolio'),
        'menu_name' => __('Case Studies', 'mvillada-portfolio'),
        'name_admin_bar' => __('Case Study', 'mvillada-portfolio'),
        'add_new' => __('Add New', 'mvillada-portfolio'),
        'add_new_item' => __('Add New Case Study', 'mvillada-portfolio'),
        'new_item' => __('New Case Study', 'mvillada-portfolio'),
        'edit_item' => __('Edit Case Study', 'mvillada-portfolio'),
        'view_item' => __('View Case Study', 'mvillada-portfolio'),
        'all_items' => __('All Case Studies', 'mvillada-portfolio'),
        'search_items' => __('Search Case Studies', 'mvillada-portfolio'),
        'not_found' => __('No case studies found.', 'mvillada-portfolio'),
        'not_found_in_trash' => __('No case studies found in Trash.', 'mvillada-portfolio'),
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'work'),
        'menu_icon' => 'dashicons-portfolio',
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail'),
        'show_in_rest' => true,
        'publicly_queryable' => true,
    );

    register_post_type('case_study', $args);
}
add_action('init', 'mvillada_register_case_study_cpt');