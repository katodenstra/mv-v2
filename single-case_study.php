<?php get_header(); ?>

<?php
if (function_exists('mvillada_project_story_is_engineering_story') && mvillada_project_story_is_engineering_story()) {
    get_template_part('template-parts/project-story/single');
    get_footer();
    return;
}

$case_type    = get_field('case_type');
$hero_summary = get_field('hero_summary');
$external_featured_image_url = get_field('external_featured_image_url');
$demo_url     = get_field('demo_url');
$repo_url     = get_field('repo_url');

$meta_1_label = get_field('hero_meta_1_label');
$meta_1_value = get_field('hero_meta_1_value');
$meta_2_label = get_field('hero_meta_2_label');
$meta_2_value = get_field('hero_meta_2_value');
$meta_3_label = get_field('hero_meta_3_label');
$meta_3_value = get_field('hero_meta_3_value');
?>

<main class="site-main">
    <?php if (have_posts()):
        while (have_posts()):
            the_post(); ?>
            <section class="case-study-hero home-section">
                <div class="container case-study-hero__inner">
                    <div class="case-study-hero__content">
                        <?php if ($case_type): ?>
                            <p class="eyebrow">
                                <?php echo esc_html($case_type); ?>
                            </p>
                        <?php endif; ?>

                        <h1>
                            <?php the_title(); ?>
                        </h1>

                        <?php if ($hero_summary): ?>
                            <p class="lead">
                                <?php echo esc_html($hero_summary); ?>
                            </p>
                        <?php elseif (has_excerpt()): ?>
                            <p class="lead">
                                <?php echo esc_html(get_the_excerpt()); ?>
                            </p>
                        <?php endif; ?>

                        <div class="hero-actions">
                            <?php if ($demo_url): ?>
                                <a class="button button--primary" href="<?php echo esc_url($demo_url); ?>" target="_blank"
                                    rel="noopener noreferrer">
                                    View demo
                                </a>
                            <?php endif; ?>

                            <?php if ($repo_url): ?>
                                <a class="button button--secondary" href="<?php echo esc_url($repo_url); ?>" target="_blank"
                                    rel="noopener noreferrer">
                                    View repo
                                </a>
                            <?php endif; ?>
                        </div>

                        <?php if ($meta_1_value || $meta_2_value || $meta_3_value): ?>
                            <div class="case-study-meta" aria-label="Project metadata">
                                <?php if ($meta_1_value): ?>
                                    <div class="case-study-meta__item">
                                        <span class="case-study-meta__label">
                                            <?php echo esc_html($meta_1_label); ?>
                                        </span>
                                        <span class="case-study-meta__value">
                                            <?php echo esc_html($meta_1_value); ?>
                                        </span>
                                    </div>
                                <?php endif; ?>

                                <?php if ($meta_2_value): ?>
                                    <div class="case-study-meta__item">
                                        <span class="case-study-meta__label">
                                            <?php echo esc_html($meta_2_label); ?>
                                        </span>
                                        <span class="case-study-meta__value">
                                            <?php echo esc_html($meta_2_value); ?>
                                        </span>
                                    </div>
                                <?php endif; ?>

                                <?php if ($meta_3_value): ?>
                                    <div class="case-study-meta__item">
                                        <span class="case-study-meta__label">
                                            <?php echo esc_html($meta_3_label); ?>
                                        </span>
                                        <span class="case-study-meta__value">
                                            <?php echo esc_html($meta_3_value); ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

            <?php if (has_post_thumbnail() || $external_featured_image_url): ?>
                <section class="home-section case-study-media">
                    <div class="container">
                        <div class="case-study-media__frame">
                            <?php if (has_post_thumbnail()): ?>
                                <?php the_post_thumbnail('full'); ?>
                            <?php else: ?>
                                <?php echo mvillada_project_story_image($external_featured_image_url, 'full'); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <section class="home-section">
                <div class="container case-study-layout">
                    <article class="case-study-content">
                        <?php the_content(); ?>
                    </article>
                </div>
            </section>

            <section class="home-section case-study-section--cta">
                <div class="container">
                    <div class="cta-block">
                        <p class="eyebrow">Explore</p>
                        <h2>Interested in the full context behind the project?</h2>
                        <p>
                            This case study is part of a broader portfolio focused on systems, product thinking, and frontend
                            execution.
                        </p>

                        <div class="hero-actions">
                            <a class="button button--secondary"
                                href="<?php echo esc_url(get_post_type_archive_link('case_study')); ?>">
                                View all case studies
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        <?php endwhile; endif; ?>
</main>

<?php get_footer(); ?>
