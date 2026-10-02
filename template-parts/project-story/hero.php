<?php
$subtitle = mvillada_project_story_plain_text('subtitle');
$description = mvillada_project_story_plain_text('short_description');
$role = mvillada_project_story_plain_text('role');
$duration = mvillada_project_story_plain_text('duration');
$project_type = mvillada_project_story_plain_text('project_type');
$stack = mvillada_project_story_lines('stack');
$external_featured_image_url = mvillada_project_story_plain_text('external_featured_image_url');
?>

<section class="project-story-hero">
    <div class="container project-story-hero__inner">
        <div class="project-story-hero__content">
            <p class="project-story-kicker">Engineering Story</p>
            <h1><?php the_title(); ?></h1>

            <?php if ($subtitle): ?>
                <p class="project-story-hero__subtitle"><?php echo esc_html($subtitle); ?></p>
            <?php endif; ?>

            <?php if ($description): ?>
                <p class="project-story-hero__description"><?php echo esc_html($description); ?></p>
            <?php elseif (has_excerpt()): ?>
                <p class="project-story-hero__description"><?php echo esc_html(get_the_excerpt()); ?></p>
            <?php endif; ?>

            <dl class="project-story-hero__meta">
                <?php if ($role): ?>
                    <div>
                        <dt>Role</dt>
                        <dd><?php echo esc_html($role); ?></dd>
                    </div>
                <?php endif; ?>

                <?php if ($duration): ?>
                    <div>
                        <dt>Duration</dt>
                        <dd><?php echo esc_html($duration); ?></dd>
                    </div>
                <?php endif; ?>

                <?php if ($project_type): ?>
                    <div>
                        <dt>Type</dt>
                        <dd><?php echo esc_html($project_type); ?></dd>
                    </div>
                <?php endif; ?>
            </dl>

            <?php if ($stack): ?>
                <ul class="project-story-stack" aria-label="Technology stack">
                    <?php foreach ($stack as $item): ?>
                        <li><?php echo esc_html($item); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <?php if (has_post_thumbnail()): ?>
            <figure class="project-story-hero__image">
                <?php the_post_thumbnail('large', array('loading' => 'eager', 'decoding' => 'async')); ?>
            </figure>
        <?php elseif ($external_featured_image_url): ?>
            <figure class="project-story-hero__image">
                <?php echo mvillada_project_story_image($external_featured_image_url, 'large', '', 'eager'); ?>
            </figure>
        <?php endif; ?>
    </div>
</section>
