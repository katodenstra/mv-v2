<main class="site-main project-story-main">
    <?php if (have_posts()):
        while (have_posts()):
            the_post();
            $sections = mvillada_project_story_sections();
            ?>
            <?php get_template_part('template-parts/project-story/hero'); ?>

            <div class="container project-story-shell">
                <?php get_template_part('template-parts/project-story/sidebar', null, array('sections' => $sections)); ?>

                <article class="project-story-article" aria-label="<?php echo esc_attr(get_the_title()); ?>">
                    <?php foreach ($sections as $section_id => $section): ?>
                        <?php
                        get_template_part(
                            'template-parts/project-story/' . $section['partial'],
                            null,
                            array(
                                'id' => $section_id,
                                'section' => $section,
                            )
                        );
                        ?>
                    <?php endforeach; ?>
                </article>
            </div>

            <?php get_template_part('template-parts/project-story/footer'); ?>
        <?php endwhile; endif; ?>
</main>
