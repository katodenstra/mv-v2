<?php
$perspective_items = array(
    array(
        'slug' => 'operational',
        'eyebrow' => '01',
        'title' => 'Operational systems',
        'copy' => 'I started building interfaces for large operational systems where every screen supported real business processes. That experience taught me that interfaces are not decoration. They are decision-making tools, and clarity has measurable consequences.',
    ),
    array(
        'slug' => 'creative',
        'eyebrow' => '02',
        'title' => 'Creative constraints',
        'copy' => 'Agency work exposed me to different industries, budgets, stakeholders, and timelines. Every project required adapting quickly without sacrificing consistency, forcing me to think in systems rather than isolated deliverables.',
    ),
    array(
        'slug' => 'human',
        'eyebrow' => '03',
        'title' => 'Human scale',
        'copy' => 'Designing digital services for IMSS introduced another level of responsibility. Accessibility, usability, technical constraints, and millions of potential users meant every decision had to balance clarity, inclusion, and practicality.',
    ),
    array(
        'slug' => 'enterprise',
        'eyebrow' => '04',
        'title' => 'Enterprise architecture',
        'copy' => 'At Thomson Reuters, my focus shifted toward design systems, frontend architecture, and implementation quality across multiple enterprise products. Working closely with designers and engineers reinforced that consistency is not a visual outcome. It is an engineering decision that compounds over time.',
    ),
    array(
        'slug' => 'organizational',
        'eyebrow' => '05',
        'title' => 'Organizational alignment',
        'copy' => 'More recent consulting work has centered on rebuilding digital platforms, aligning executive stakeholders, navigating multilingual strategies, balancing regional requirements, and making technical decisions under real-world constraints.',
    ),
    array(
        'slug' => 'computational',
        'eyebrow' => '06',
        'title' => 'Computational reasoning',
        'copy' => 'My work in AI training introduced another dimension: translating design intent into structured reasoning. Teaching language models to understand interfaces strengthened my thinking about systems, communication, abstraction, and decision-making.',
    ),
);
?>

<section id="perspective" class="home-section perspective-section" aria-labelledby="perspective-title" data-perspective-section>
  <div class="container perspective-section__inner">
    <header class="perspective-section__header">
      <p class="eyebrow">Perspective</p>
      <h2 id="perspective-title">Where this perspective comes from.</h2>
    </header>

    <div class="perspective-section__intro">
      <p>Every environment taught me a different kind of problem.</p>
      <p>Operational systems. Public services. Enterprise products. Consulting. AI.</p>
      <p class="perspective-section__thesis">
        The pattern underneath was always the same: complexity only creates value when people can understand it.
      </p>
    </div>
  </div>

  <div
    class="perspective-stack"
    style="--perspective-card-count: <?php echo esc_attr(count($perspective_items)); ?>;"
    data-perspective-stack
  >
    <div class="container perspective-stack__sticky">
      <div class="perspective-stack__cards" aria-label="Kinds of complexity that shaped my perspective">
        <?php foreach ($perspective_items as $index => $item): ?>
          <?php $svg_path = get_template_directory() . '/assets/svg/perspective-' . $item['slug'] . '.svg'; ?>
          <article
            class="perspective-card"
            style="--perspective-card-index: <?php echo esc_attr((string) $index); ?>;"
            data-perspective-card
          >
            <div class="perspective-card__content">
              <p class="perspective-card__eyebrow"><?php echo esc_html($item['eyebrow']); ?></p>
              <h3><?php echo esc_html($item['title']); ?></h3>
              <p><?php echo esc_html($item['copy']); ?></p>
            </div>

            <?php if (file_exists($svg_path)): ?>
              <div class="perspective-card__art" aria-hidden="true">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/svg/perspective-' . $item['slug'] . '.svg'); ?>" alt="" width="600" height="600" loading="lazy" decoding="async">
              </div>
            <?php endif; ?>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <div class="container perspective-section__inner">
    <div class="perspective-section__closing" aria-label="Perspective summary">
      <p>Different products.</p>
      <p>Different industries.</p>
      <p>Different constraints.</p>
      <p>The common thread has never been the technology.</p>
      <p>It is learning how to bring structure to complexity, regardless of where it appears.</p>
    </div>

    <p class="perspective-section__footnote">
      10+ years designing and building digital products across enterprise software, public services, design systems,
      frontend engineering, consulting, and AI.
    </p>
  </div>
</section>
