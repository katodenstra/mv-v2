<?php get_header(); ?>

<main class="site-main">
    <section class="home-section">
        <div class="container">
            <p class="eyebrow">Selected work</p>
            <h1>Case studies shaped by systems, judgment, and execution.</h1>
            <p class="section-intro">
                A curated selection of projects focused on product structure, interaction design, design systems, and
                frontend implementation.
            </p>

            <?php if (have_posts()): ?>
                <div class="card-grid">
                    <?php while (have_posts()):
                        the_post();
                        $card_label = get_field('card_label');
                        $project_format = get_field('project_format');
                        $portfolio_card = get_field('portfolio_card');
                        ?>
                        <article class="content-card has-card-glow">
                            <?php if ($project_format && 'Case Study' !== $project_format): ?>
                                <p class="content-card__eyebrow"><?php echo esc_html($project_format); ?></p>
                            <?php elseif ($card_label): ?>
                                <p class="content-card__eyebrow"><?php echo esc_html($card_label); ?></p>
                            <?php else: ?>
                                <p class="content-card__eyebrow">Case Study</p>
                            <?php endif; ?>

                            <h3>
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>

                            <p>
                                <?php echo esc_html($portfolio_card ? $portfolio_card : get_the_excerpt()); ?>
                            </p>

                            <div class="content-card__actions">
                                <a class="text-link" href="<?php the_permalink(); ?>">
                                    <?php echo esc_html($project_format && 'Case Study' !== $project_format ? 'Read story' : 'View case study'); ?>
                                </a>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
