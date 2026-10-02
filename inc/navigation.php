<?php

function mvillada_get_project_nav_items($format = 'Case Study')
{
    $query_args = array(
        'post_type' => 'case_study',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => array(
            'menu_order' => 'ASC',
            'date' => 'DESC',
        ),
        'no_found_rows' => true,
    );

    if ('Engineering Story' === $format) {
        $query_args['meta_query'] = array(
            array(
                'key' => 'project_format',
                'value' => 'Engineering Story',
                'compare' => '=',
            ),
        );
    } else {
        $query_args['meta_query'] = array(
            'relation' => 'OR',
            array(
                'key' => 'project_format',
                'value' => 'Case Study',
                'compare' => '=',
            ),
            array(
                'key' => 'project_format',
                'value' => '',
                'compare' => '=',
            ),
            array(
                'key' => 'project_format',
                'compare' => 'NOT EXISTS',
            ),
        );
    }

    return get_posts($query_args);
}
