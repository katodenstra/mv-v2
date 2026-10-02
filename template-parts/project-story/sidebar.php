<?php
$sections = isset($args['sections']) && is_array($args['sections']) ? $args['sections'] : array();
$role = mvillada_project_story_plain_text('role');
$duration = mvillada_project_story_plain_text('duration');
$stack = mvillada_project_story_lines('stack');
$responsibilities = mvillada_project_story_lines('responsibilities');
$timeline = mvillada_project_story_lines('timeline');
?>

<aside class="project-story-sidebar" aria-label="Project story navigation">
    <div class="project-story-sidebar__inner">
        <div class="project-story-progress" aria-hidden="true">
            <span data-project-story-progress></span>
        </div>

        <?php if ($sections): ?>
            <nav class="project-story-toc" aria-label="Story sections">
                <p class="project-story-sidebar__label">Sections</p>
                <ol>
                    <?php foreach ($sections as $section_id => $section): ?>
                        <li>
                            <a href="#<?php echo esc_attr($section_id); ?>" data-project-story-toc-link>
                                <?php echo esc_html($section['label']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </nav>
        <?php endif; ?>

        <dl class="project-story-sidebar__meta">
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

        </dl>

        <?php if ($stack): ?>
            <div class="project-story-sidebar__group project-story-sidebar__group--stack">
                <p class="project-story-sidebar__label">Stack</p>
                <ul class="project-story-sidebar__chips">
                    <?php foreach ($stack as $item): ?>
                        <li><?php echo esc_html($item); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($timeline): ?>
            <div class="project-story-sidebar__group">
                <p class="project-story-sidebar__label">Timeline</p>
                <ol class="project-story-timeline">
                    <?php foreach ($timeline as $item): ?>
                        <li><?php echo esc_html($item); ?></li>
                    <?php endforeach; ?>
                </ol>
            </div>
        <?php endif; ?>

        <?php if ($responsibilities): ?>
            <div class="project-story-sidebar__group">
                <p class="project-story-sidebar__label">Responsibilities</p>
                <ul>
                    <?php foreach ($responsibilities as $item): ?>
                        <li><?php echo esc_html($item); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</aside>
