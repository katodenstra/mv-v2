<?php

function mvillada_project_story_field($name, $default = '')
{
    if (!function_exists('get_field')) {
        return $default;
    }

    $post_id = get_the_ID();

    if (!$post_id && function_exists('get_queried_object_id')) {
        $post_id = get_queried_object_id();
    }

    $value = $post_id ? get_field($name, $post_id) : get_field($name);

    return null === $value || false === $value || '' === $value ? $default : $value;
}

function mvillada_project_story_is_engineering_story()
{
    return 'Engineering Story' === mvillada_project_story_field('project_format', 'Case Study');
}

function mvillada_project_story_lines($field_name)
{
    $value = mvillada_project_story_field($field_name, array());

    if (is_array($value)) {
        $items = array();

        foreach ($value as $item) {
            if (is_array($item)) {
                $item = $item['item'] ?? $item['label'] ?? $item['name'] ?? reset($item);
            }

            if (is_string($item) && '' !== trim($item)) {
                $items[] = trim($item);
            }
        }

        return $items;
    }

    $lines = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) $value))));

    if ('stack' !== $field_name) {
        return $lines;
    }

    $items = array();

    foreach ($lines as $line) {
        foreach (preg_split('/\s*,\s*/', $line) as $item) {
            if ('' !== trim($item)) {
                $items[] = trim($item);
            }
        }
    }

    return $items;
}

function mvillada_project_story_rich_text($field_name)
{
    return mvillada_project_story_field($field_name, '');
}

function mvillada_project_story_plain_text($field_name)
{
    return trim(wp_strip_all_tags((string) mvillada_project_story_field($field_name, '')));
}

function mvillada_project_story_sections()
{
    return array_filter(mvillada_project_story_section_definitions(), 'mvillada_project_story_section_has_content');
}

function mvillada_project_story_section_definitions()
{
    return array(
        'overview' => array('label' => 'Overview', 'partial' => 'overview', 'type' => 'field'),
        'business_context' => array('label' => 'Business Context', 'partial' => 'business-context', 'type' => 'field'),
        'why_not_redesign' => array('label' => 'Why This Wasn\'t Just a Redesign', 'partial' => 'why-not-redesign', 'type' => 'field'),
        'my_role' => array('label' => 'My Role', 'partial' => 'my-role', 'type' => 'field'),
        'understanding_problem' => array('label' => 'Understanding the Problem', 'partial' => 'understanding-problem', 'type' => 'field'),
        'designing_system' => array('label' => 'Designing the System', 'partial' => 'designing-system', 'type' => 'field'),
        'engineering_platform' => array('label' => 'Engineering the Platform', 'partial' => 'engineering-platform', 'type' => 'field'),
        'critical_decisions' => array('label' => 'Critical Decisions', 'partial' => 'critical-decisions', 'type' => 'decisions'),
        'launch_constraints' => array('label' => 'Launch Under Constraints', 'partial' => 'launch', 'type' => 'field'),
        'outcome' => array('label' => 'Outcome', 'partial' => 'outcome', 'type' => 'field'),
        'lessons_learned' => array('label' => 'Lessons Learned', 'partial' => 'lessons', 'type' => 'lessons'),
        'why_project_matters' => array('label' => 'Why This Project Matters', 'partial' => 'why-project-matters', 'type' => 'field'),
    );
}

function mvillada_project_story_section_has_content($section, $field_name = null)
{
    if (null === $field_name) {
        $field_name = array_search($section, mvillada_project_story_section_definitions(), true);
    }

    if ('decisions' === $section['type']) {
        return !empty(mvillada_project_story_decisions());
    }

    if ('lessons' === $section['type']) {
        return !empty(mvillada_project_story_lessons());
    }

    if ('gallery' === $section['type']) {
        return !empty(mvillada_project_story_gallery_items());
    }

    return $field_name && (
        '' !== mvillada_project_story_plain_text($field_name) ||
        !empty(mvillada_project_story_section_image($field_name))
    );
}

function mvillada_project_story_section_image($field_name)
{
    $image = mvillada_project_story_field($field_name . '_image', array());

    if (!empty($image)) {
        return $image;
    }

    return mvillada_project_story_plain_text($field_name . '_image_url');
}

function mvillada_project_story_section_image_caption($field_name)
{
    return mvillada_project_story_plain_text($field_name . '_image_caption');
}

function mvillada_project_story_normalize_structured_items($value, $slot_prefix, $keys)
{
    if (!is_array($value)) {
        return array();
    }

    $items = array();
    $is_repeater = isset($value[0]) && is_array($value[0]);
    $rows = $is_repeater ? $value : array_filter($value, 'is_array');

    foreach ($rows as $row_key => $row) {
        if (!$is_repeater && 0 !== strpos((string) $row_key, $slot_prefix . '_')) {
            continue;
        }

        $item = array();

        foreach ($keys as $key) {
            $item[$key] = isset($row[$key]) ? trim((string) $row[$key]) : '';
        }

        if (array_filter($item)) {
            $items[] = $item;
        }
    }

    return $items;
}

function mvillada_project_story_decisions()
{
    return mvillada_project_story_normalize_structured_items(
        mvillada_project_story_field('critical_decisions', array()),
        'decision',
        array('title', 'problem', 'context', 'decision', 'alternatives', 'tradeoffs', 'long_term_impact')
    );
}

function mvillada_project_story_lessons()
{
    return mvillada_project_story_normalize_structured_items(
        mvillada_project_story_field('lessons_learned', array()),
        'lesson',
        array('title', 'description')
    );
}

function mvillada_project_story_gallery_items()
{
    $value = mvillada_project_story_field('gallery', array());

    if (!is_array($value)) {
        return array();
    }

    $items = array();
    $is_repeater = isset($value[0]) && is_array($value[0]);
    $rows = $is_repeater ? $value : array_filter($value, 'is_array');

    foreach ($rows as $row_key => $row) {
        if (!$is_repeater && 0 !== strpos((string) $row_key, 'gallery_item_')) {
            continue;
        }

        $image = !empty($row['image']) ? $row['image'] : (isset($row['image_url']) ? trim((string) $row['image_url']) : '');

        if (empty($image)) {
            continue;
        }

        $items[] = array(
            'image' => $image,
            'caption' => isset($row['caption']) ? trim((string) $row['caption']) : '',
            'width' => isset($row['width']) && 'full' === $row['width'] ? 'full' : 'normal',
        );
    }

    return $items;
}

function mvillada_project_story_image($image, $size = 'large', $class = '', $loading = 'lazy')
{
    if (is_string($image) && '' !== trim($image)) {
        $url = esc_url($image);

        if (!$url) {
            return '';
        }

        return sprintf(
            '<img src="%s" alt="" class="%s" loading="%s" decoding="async">',
            $url,
            esc_attr($class),
            esc_attr($loading)
        );
    }

    if (is_numeric($image)) {
        return wp_get_attachment_image((int) $image, $size, false, array(
            'class' => $class,
            'loading' => $loading,
            'decoding' => 'async',
        ));
    }

    if (!is_array($image) || empty($image['ID'])) {
        return '';
    }

    return wp_get_attachment_image((int) $image['ID'], $size, false, array(
        'class' => $class,
        'loading' => $loading,
        'decoding' => 'async',
        'alt' => isset($image['alt']) ? $image['alt'] : '',
    ));
}

/**
 * Render an externally hosted image inside the classic Case Study editor.
 *
 * Usage: [project_image url="https://example.com/image.jpg" caption="Optional caption" alt="Description"]
 */
function mvillada_project_image_shortcode($attributes)
{
    if ('case_study' !== get_post_type()) {
        return '';
    }

    $attributes = shortcode_atts(
        array(
            'url' => '',
            'caption' => '',
            'alt' => '',
        ),
        $attributes,
        'project_image'
    );

    $url = esc_url(trim((string) $attributes['url']));

    if (!$url) {
        return '';
    }

    $caption = sanitize_text_field($attributes['caption']);
    $alt = sanitize_text_field($attributes['alt']);

    ob_start();
    ?>
    <figure class="project-figure">
        <img src="<?php echo esc_url($url); ?>" alt="<?php echo esc_attr($alt); ?>" loading="lazy" decoding="async">
        <?php if ($caption): ?>
            <figcaption><?php echo esc_html($caption); ?></figcaption>
        <?php endif; ?>
    </figure>
    <?php

    return trim(ob_get_clean());
}
add_shortcode('project_image', 'mvillada_project_image_shortcode');
