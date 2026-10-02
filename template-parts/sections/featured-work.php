<section id="work" class="home-section">
  <div class="container">
    <p class="eyebrow">Selected work</p>
    <h2>Selected work shaped by systems, judgment, and execution.</h2>
    <p class="section-intro">
      A small number of projects chosen to show how I think through product structure, interface clarity, and
      implementation quality.
    </p>

    <?php
    $work_categories = array(
      array(
        'title' => 'Engineering Stories',
        'description' => 'Engineering stories behind systems, implementation decisions, and technical trade-offs.',
        'empty_message' => 'No engineering stories are published yet.',
        'query' => array(
          'post_type' => 'case_study',
          'posts_per_page' => 3,
          'orderby' => 'menu_order',
          'order' => 'ASC',
          'meta_key' => 'project_format',
          'meta_value' => 'Engineering Story',
        ),
      ),
      array(
        'title' => 'Case Studies',
        'description' => 'Product and UX case studies focused on structure, clarity, and measurable execution.',
        'empty_message' => 'No case studies are published yet.',
        'query' => array(
          'post_type' => 'case_study',
          'posts_per_page' => 3,
          'orderby' => 'menu_order',
          'order' => 'ASC',
          'meta_query' => array(
            'relation' => 'OR',
            array(
              'key' => 'project_format',
              'value' => 'Case Study',
              'compare' => '=',
            ),
            array(
              'key' => 'project_format',
              'value' => '',
              'compare' => '=',
            ),
            array(
              'key' => 'project_format',
              'compare' => 'NOT EXISTS',
            ),
          ),
        ),
      ),
    );
    ?>

    <div class="work-category-list">
      <?php foreach ($work_categories as $work_category): ?>
        <?php $featured_query = new WP_Query($work_category['query']); ?>

        <section id="<?php echo esc_attr(sanitize_title($work_category['title'])); ?>" class="work-category" aria-labelledby="<?php echo esc_attr(sanitize_title($work_category['title'])); ?>-title">
          <div class="work-category__header">
            <h3 id="<?php echo esc_attr(sanitize_title($work_category['title'])); ?>-title">
              <?php echo esc_html($work_category['title']); ?>
            </h3>
            <p><?php echo esc_html($work_category['description']); ?></p>
          </div>

          <?php if ($featured_query->have_posts()): ?>
            <?php
            $grid_count = min(3, (int) $featured_query->post_count);
            ?>
            <div class="card-grid card-grid--count-<?php echo esc_attr($grid_count); ?>">
              <?php while ($featured_query->have_posts()):
                $featured_query->the_post();
                $card_label = get_field('card_label');
                $project_format = get_field('project_format');
                $portfolio_card = get_field('portfolio_card');
                ?>
                <article class="content-card has-card-glow">
                  <?php if ($project_format && 'Case Study' !== $project_format): ?>
                    <p class="content-card__eyebrow">
                      <?php echo esc_html($project_format); ?>
                    </p>
                  <?php elseif ($card_label): ?>
                    <p class="content-card__eyebrow">
                      <?php echo esc_html($card_label); ?>
                    </p>
                  <?php else: ?>
                    <p class="content-card__eyebrow">Case Study</p>
                  <?php endif; ?>

                  <h4>
                    <a href="<?php the_permalink(); ?>">
                      <?php the_title(); ?>
                    </a>
                  </h4>

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
          <?php else: ?>
            <p class="work-category__empty"><?php echo esc_html($work_category['empty_message']); ?></p>
          <?php endif; ?>

          <?php wp_reset_postdata(); ?>
        </section>
      <?php endforeach; ?>
    </div>
  </div>
</section>
