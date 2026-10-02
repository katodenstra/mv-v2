<?php $lessons = mvillada_project_story_lessons(); ?>

<?php if ($lessons): ?>
    <section id="lessons_learned" class="project-story-section project-story-lessons" data-project-story-section>
        <h2>Lessons Learned</h2>

        <div class="project-story-lessons__list">
            <?php foreach ($lessons as $index => $lesson): ?>
                <article class="project-story-lesson has-card-glow">
                    <span class="project-story-lesson__mark" aria-hidden="true"><?php echo esc_html(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?></span>

                    <?php if ($lesson['title']): ?>
                        <h3><?php echo esc_html($lesson['title']); ?></h3>
                    <?php endif; ?>

                    <?php if ($lesson['description']): ?>
                        <div><?php echo wp_kses_post(wpautop($lesson['description'])); ?></div>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>
