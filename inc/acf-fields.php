<?php

function mvillada_project_story_wysiwyg_field($key, $label, $name)
{
    return array(
        'key' => $key,
        'label' => $label,
        'name' => $name,
        'type' => 'wysiwyg',
        'tabs' => 'all',
        'toolbar' => 'basic',
        'media_upload' => 0,
        'delay' => 1,
    );
}

function mvillada_project_story_textarea_field($key, $label, $name, $instructions = '')
{
    return array(
        'key' => $key,
        'label' => $label,
        'name' => $name,
        'type' => 'textarea',
        'instructions' => $instructions,
        'rows' => 4,
        'new_lines' => '',
    );
}

function mvillada_project_story_image_field($key, $label, $name)
{
    return array(
        'key' => $key,
        'label' => $label,
        'name' => $name,
        'type' => 'image',
        'instructions' => 'Optional. Upload an image here or use the URL field below. Uploaded images take priority.',
        'return_format' => 'array',
        'preview_size' => 'medium',
    );
}

function mvillada_project_story_url_field($key, $label, $name, $instructions = '')
{
    return array(
        'key' => $key,
        'label' => $label,
        'name' => $name,
        'type' => 'url',
        'instructions' => $instructions,
    );
}

function mvillada_project_story_section_image_fields($section_name, $label)
{
    return array(
        mvillada_project_story_image_field(
            "field_project_story_{$section_name}_image",
            "{$label} Image",
            "{$section_name}_image"
        ),
        mvillada_project_story_url_field(
            "field_project_story_{$section_name}_image_url",
            "{$label} Image URL",
            "{$section_name}_image_url",
            'Optional. Paste the full URL of an externally hosted image. Leave empty when this section has no image.'
        ),
        array(
            'key' => "field_project_story_{$section_name}_image_caption",
            'label' => "{$label} Image Caption",
            'name' => "{$section_name}_image_caption",
            'type' => 'text',
        ),
    );
}

function mvillada_project_story_decision_sub_fields($prefix)
{
    return array(
        array('key' => "{$prefix}_title", 'label' => 'Title', 'name' => 'title', 'type' => 'text'),
        mvillada_project_story_textarea_field("{$prefix}_problem", 'Problem', 'problem'),
        mvillada_project_story_textarea_field("{$prefix}_context", 'Context', 'context'),
        mvillada_project_story_textarea_field("{$prefix}_decision", 'Decision', 'decision'),
        mvillada_project_story_textarea_field("{$prefix}_alternatives", 'Alternatives considered', 'alternatives'),
        mvillada_project_story_textarea_field("{$prefix}_tradeoffs", 'Trade-offs', 'tradeoffs'),
        mvillada_project_story_textarea_field("{$prefix}_impact", 'Long-term impact', 'long_term_impact'),
    );
}

function mvillada_project_story_lesson_sub_fields($prefix)
{
    return array(
        array('key' => "{$prefix}_title", 'label' => 'Title', 'name' => 'title', 'type' => 'text'),
        mvillada_project_story_textarea_field("{$prefix}_description", 'Description', 'description'),
    );
}

function mvillada_project_story_gallery_sub_fields($prefix)
{
    return array(
        array(
            'key' => "{$prefix}_image",
            'label' => 'Image',
            'name' => 'image',
            'type' => 'image',
            'return_format' => 'array',
            'preview_size' => 'medium',
        ),
        mvillada_project_story_url_field(
            "{$prefix}_image_url",
            'Image URL',
            'image_url',
            'Optional fallback for an externally hosted image.'
        ),
        array('key' => "{$prefix}_caption", 'label' => 'Caption', 'name' => 'caption', 'type' => 'text'),
        array(
            'key' => "{$prefix}_width",
            'label' => 'Width',
            'name' => 'width',
            'type' => 'select',
            'choices' => array(
                'normal' => 'Normal',
                'full' => 'Full',
            ),
            'default_value' => 'normal',
            'return_format' => 'value',
        ),
    );
}

function mvillada_project_story_slot_groups($type, $count, $callback)
{
    $fields = array();

    for ($index = 1; $index <= $count; $index++) {
        $fields[] = array(
            'key' => "field_project_story_{$type}_{$index}",
            'label' => sprintf('%s %d', ucwords(str_replace('_', ' ', $type)), $index),
            'name' => "{$type}_{$index}",
            'type' => 'group',
            'layout' => 'block',
            'sub_fields' => $callback("field_project_story_{$type}_{$index}"),
        );
    }

    return $fields;
}

add_action('acf/init', function () {
    acf_add_local_field_group(array(
        'key' => 'group_69eb86e9ca943',
        'title' => 'Case Study Fields',
        'fields' => array(
            array(
                'key' => 'field_69eba2491bb3f',
                'label' => 'General',
                'name' => '',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ),
            array('key' => 'field_69eb874acb121', 'label' => 'Case Type', 'name' => 'case_type', 'type' => 'text'),
            mvillada_project_story_textarea_field('field_69eba0db633f8', 'Hero Summary', 'hero_summary'),
            mvillada_project_story_url_field(
                'field_case_study_external_featured_image_url',
                'External Featured Image URL',
                'external_featured_image_url',
                'Optional. Used only when this project does not have a WordPress featured image.'
            ),
            array('key' => 'field_69eba0fa633f9', 'label' => 'Demo URL', 'name' => 'demo_url', 'type' => 'url'),
            array('key' => 'field_69eba108633fa', 'label' => 'Repo URL', 'name' => 'repo_url', 'type' => 'url'),
            array(
                'key' => 'field_69eba3371bb40',
                'label' => 'Hero Metadata',
                'name' => '',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ),
            array('key' => 'field_69eba117633fb', 'label' => 'Hero Meta 1 Label', 'name' => 'hero_meta_1_label', 'type' => 'text'),
            array('key' => 'field_69eba140633fd', 'label' => 'Hero Meta 1 Value', 'name' => 'hero_meta_1_value', 'type' => 'text'),
            array('key' => 'field_69eba1d21bb3a', 'label' => 'Hero Meta 2 Label', 'name' => 'hero_meta_2_label', 'type' => 'text'),
            array('key' => 'field_69eba1e01bb3b', 'label' => 'Hero Meta 2 Value', 'name' => 'hero_meta_2_value', 'type' => 'text'),
            array('key' => 'field_69eba1eb1bb3c', 'label' => 'Hero Meta 3 Label', 'name' => 'hero_meta_3_label', 'type' => 'text'),
            array('key' => 'field_69eba1f41bb3d', 'label' => 'Hero Meta 3 Value', 'name' => 'hero_meta_3_value', 'type' => 'text'),
            array(
                'key' => 'field_69eba3661bb41',
                'label' => 'Card / Home',
                'name' => '',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ),
            array('key' => 'field_69eba20d1bb3e', 'label' => 'Card Label', 'name' => 'card_label', 'type' => 'text'),
            array(
                'key' => 'field_project_story_tab_engineering_story',
                'label' => 'Engineering Story',
                'name' => '',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ),
            array(
                'key' => 'field_project_format',
                'label' => 'Project Format',
                'name' => 'project_format',
                'type' => 'select',
                'choices' => array(
                    'Case Study' => 'Case Study',
                    'Engineering Story' => 'Engineering Story',
                ),
                'default_value' => 'Case Study',
                'return_format' => 'value',
            ),
            array('key' => 'field_project_story_subtitle', 'label' => 'Subtitle', 'name' => 'subtitle', 'type' => 'text'),
            mvillada_project_story_textarea_field('field_project_story_short_description', 'Short Description', 'short_description'),
            array('key' => 'field_project_story_role', 'label' => 'Role', 'name' => 'role', 'type' => 'text'),
            array('key' => 'field_project_story_duration', 'label' => 'Duration', 'name' => 'duration', 'type' => 'text'),
            array('key' => 'field_project_story_project_type', 'label' => 'Project Type', 'name' => 'project_type', 'type' => 'text'),
            mvillada_project_story_textarea_field('field_project_story_stack', 'Stack', 'stack', 'Enter one technology per line.'),
            mvillada_project_story_textarea_field('field_project_story_responsibilities', 'Responsibilities', 'responsibilities', 'Enter one responsibility per line.'),
            mvillada_project_story_textarea_field('field_project_story_timeline', 'Timeline', 'timeline'),
            mvillada_project_story_wysiwyg_field('field_project_story_overview', 'Overview', 'overview'),
            ...mvillada_project_story_section_image_fields('overview', 'Overview'),
            mvillada_project_story_wysiwyg_field('field_project_story_business_context', 'Business Context', 'business_context'),
            ...mvillada_project_story_section_image_fields('business_context', 'Business Context'),
            mvillada_project_story_wysiwyg_field('field_project_story_why_not_redesign', 'Why This Wasn\'t Just a Redesign', 'why_not_redesign'),
            mvillada_project_story_wysiwyg_field('field_project_story_my_role', 'My Role', 'my_role'),
            mvillada_project_story_wysiwyg_field('field_project_story_understanding_problem', 'Understanding the Problem', 'understanding_problem'),
            ...mvillada_project_story_section_image_fields('understanding_problem', 'Understanding the Problem'),
            mvillada_project_story_wysiwyg_field('field_project_story_designing_system', 'Designing the System', 'designing_system'),
            ...mvillada_project_story_section_image_fields('designing_system', 'Designing the System'),
            mvillada_project_story_wysiwyg_field('field_project_story_engineering_platform', 'Engineering the Platform', 'engineering_platform'),
            ...mvillada_project_story_section_image_fields('engineering_platform', 'Engineering the Platform'),
            mvillada_project_story_wysiwyg_field('field_project_story_launch_constraints', 'Launch Under Constraints', 'launch_constraints'),
            ...mvillada_project_story_section_image_fields('launch_constraints', 'Launch Under Constraints'),
            mvillada_project_story_wysiwyg_field('field_project_story_outcome', 'Outcome', 'outcome'),
            ...mvillada_project_story_section_image_fields('outcome', 'Outcome'),
            mvillada_project_story_wysiwyg_field('field_project_story_why_project_matters', 'Why This Project Matters', 'why_project_matters'),
            mvillada_project_story_textarea_field('field_project_story_portfolio_card', 'Portfolio Card', 'portfolio_card'),
            array(
                'key' => 'field_project_story_tab_critical_decisions',
                'label' => 'Critical Decisions',
                'name' => '',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ),
            array(
                'key' => 'field_project_story_critical_decisions',
                'label' => 'Critical Decisions',
                'name' => 'critical_decisions',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => mvillada_project_story_slot_groups('decision', 6, 'mvillada_project_story_decision_sub_fields'),
            ),
            array(
                'key' => 'field_project_story_tab_lessons_learned',
                'label' => 'Lessons Learned',
                'name' => '',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ),
            array(
                'key' => 'field_project_story_lessons_learned',
                'label' => 'Lessons Learned',
                'name' => 'lessons_learned',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => mvillada_project_story_slot_groups('lesson', 6, 'mvillada_project_story_lesson_sub_fields'),
            ),
            array(
                'key' => 'field_project_story_tab_gallery',
                'label' => 'Gallery',
                'name' => '',
                'type' => 'tab',
                'placement' => 'top',
                'endpoint' => 0,
            ),
            array(
                'key' => 'field_project_story_gallery',
                'label' => 'Gallery',
                'name' => 'gallery',
                'type' => 'group',
                'layout' => 'block',
                'sub_fields' => mvillada_project_story_slot_groups('gallery_item', 6, 'mvillada_project_story_gallery_sub_fields'),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'case_study',
                ),
            ),
        ),
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'active' => true,
    ));
});
