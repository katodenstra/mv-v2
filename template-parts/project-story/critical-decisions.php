<?php $decisions = mvillada_project_story_decisions(); ?>

<?php if ($decisions): ?>
    <section id="critical_decisions" class="project-story-section project-story-decisions" data-project-story-section>
        <div class="project-story-section__header">
            <h2>Critical Decisions</h2>

            <?php if (count($decisions) > 1): ?>
                <div class="project-story-slider-controls" aria-label="Critical decision controls">
                    <button type="button" data-project-story-slider-prev aria-label="Show previous decision">
                        <span class="material-symbols-rounded" aria-hidden="true">arrow_back</span>
                    </button>
                    <button type="button" data-project-story-slider-next aria-label="Show next decision">
                        <span class="material-symbols-rounded" aria-hidden="true">arrow_forward</span>
                    </button>
                </div>
            <?php endif; ?>
        </div>

        <div class="project-story-decisions__track" data-project-story-slider tabindex="0" aria-label="Critical decisions">
            <?php foreach ($decisions as $index => $decision): ?>
                <article class="project-story-decision has-card-glow" data-project-story-slide>
                    <p class="project-story-decision__index">
                        Decision <?php echo esc_html(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?>
                    </p>

                    <?php if ($decision['title']): ?>
                        <h3><?php echo esc_html($decision['title']); ?></h3>
                    <?php endif; ?>

                    <dl>
                        <?php
                        $rows = array(
                            'problem' => 'Problem',
                            'context' => 'Context',
                            'decision' => 'Decision',
                            'alternatives' => 'Alternatives',
                            'tradeoffs' => 'Trade-offs',
                            'long_term_impact' => 'Long-term impact',
                        );
                        ?>

                        <?php foreach ($rows as $key => $label): ?>
                            <?php if (!empty($decision[$key])): ?>
                                <div>
                                    <dt><?php echo esc_html($label); ?></dt>
                                    <dd><?php echo wp_kses_post(wpautop($decision[$key])); ?></dd>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </dl>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>
