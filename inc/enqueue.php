<?php

function mvillada_portfolio_enqueue_assets() {
    if (is_page('contact') || is_page_template('page-contact.php')) {
        wp_enqueue_style('mvillada-contact', get_template_directory_uri() . '/assets/css/contact.css', array('mvillada-portfolio-utilities'), filemtime(get_template_directory() . '/assets/css/contact.css'));
    }
    $base_css_path = get_template_directory() . '/assets/css/base.css';
    $components_css_path = get_template_directory() . '/assets/css/components.css';
    $utilities_css_path = get_template_directory() . '/assets/css/utilities.css';
    $hero_signal_css_path = get_template_directory() . '/assets/css/hero-controlled-signal.css';
    $project_story_css_path = get_template_directory() . '/assets/css/project-story.css';
    $anime_js_path = get_template_directory() . '/assets/vendor/anime/anime.min.js';
    $hero_signal_js_path = get_template_directory() . '/assets/js/hero-controlled-signal.js';
    $main_js_path = get_template_directory() . '/assets/js/main.js';
    $project_story_js_path = get_template_directory() . '/assets/js/project-story.js';

    wp_enqueue_style(
        'mvillada-portfolio-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200',
        array(),
        null
    );

    wp_enqueue_style(
        'mvillada-portfolio-base',
        get_template_directory_uri() . '/assets/css/base.css',
        array('mvillada-portfolio-fonts'),
        file_exists($base_css_path) ? filemtime($base_css_path) : null
    );

    wp_enqueue_style(
        'mvillada-portfolio-components',
        get_template_directory_uri() . '/assets/css/components.css',
        array('mvillada-portfolio-base'),
        file_exists($components_css_path) ? filemtime($components_css_path) : null
    );

    wp_enqueue_style(
        'mvillada-portfolio-utilities',
        get_template_directory_uri() . '/assets/css/utilities.css',
        array('mvillada-portfolio-components'),
        file_exists($utilities_css_path) ? filemtime($utilities_css_path) : null
    );

    if (is_front_page()) {
        wp_enqueue_style(
            'mvillada-hero-controlled-signal',
            get_template_directory_uri() . '/assets/css/hero-controlled-signal.css',
            array('mvillada-portfolio-utilities'),
            file_exists($hero_signal_css_path) ? filemtime($hero_signal_css_path) : null
        );

        wp_enqueue_script(
            'animejs',
            get_template_directory_uri() . '/assets/vendor/anime/anime.min.js',
            array(),
            file_exists($anime_js_path) ? filemtime($anime_js_path) : '3.2.2',
            true
        );

        wp_enqueue_script(
            'mvillada-hero-controlled-signal',
            get_template_directory_uri() . '/assets/js/hero-controlled-signal.js',
            array('animejs'),
            file_exists($hero_signal_js_path) ? filemtime($hero_signal_js_path) : null,
            true
        );
    }

    wp_enqueue_script(
        'mvillada-portfolio-main',
        get_template_directory_uri() . '/assets/js/main.js',
        array(),
        file_exists($main_js_path) ? filemtime($main_js_path) : null,
        true
    );

    if (is_singular('case_study') && function_exists('mvillada_project_story_is_engineering_story') && mvillada_project_story_is_engineering_story()) {
        wp_enqueue_style(
            'mvillada-project-story',
            get_template_directory_uri() . '/assets/css/project-story.css',
            array('mvillada-portfolio-utilities'),
            file_exists($project_story_css_path) ? filemtime($project_story_css_path) : null
        );

        wp_enqueue_script(
            'mvillada-project-story',
            get_template_directory_uri() . '/assets/js/project-story.js',
            array(),
            file_exists($project_story_js_path) ? filemtime($project_story_js_path) : null,
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'mvillada_portfolio_enqueue_assets');
