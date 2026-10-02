<?php
$section_id = isset($args['id']) ? $args['id'] : '';
$title = isset($args['title']) ? $args['title'] : '';
$field = isset($args['field']) ? $args['field'] : $section_id;
$content = mvillada_project_story_rich_text($field);
$image = mvillada_project_story_section_image($field);
$caption = mvillada_project_story_section_image_caption($field);
?>

<?php if ($section_id && $title && ($content || $image)): ?>
    <section id="<?php echo esc_attr($section_id); ?>" class="project-story-section" data-project-story-section>
        <h2><?php echo esc_html($title); ?></h2>
        <?php if ($content): ?>
            <div class="project-story-section__body">
                <?php echo wp_kses_post($content); ?>
            </div>
        <?php endif; ?>

        <?php if ($image): ?>
            <figure class="project-story-figure">
                <?php echo mvillada_project_story_image($image, 'large'); ?>

                <?php if ($caption): ?>
                    <figcaption><?php echo esc_html($caption); ?></figcaption>
                <?php endif; ?>
            </figure>
        <?php endif; ?>
    </section>
<?php endif; ?>
