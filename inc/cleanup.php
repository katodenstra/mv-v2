<?php

function mvillada_portfolio_body_classes($classes) {
    if (is_front_page()) {
        $classes[] = 'page-home';
    }

    return $classes;
}
add_filter('body_class', 'mvillada_portfolio_body_classes');