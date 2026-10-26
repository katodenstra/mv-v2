<?php
$case_studies = mvillada_get_project_nav_items('Case Study');
$engineering_stories = mvillada_get_project_nav_items('Engineering Story');
$home_url = home_url('/');
$is_home = is_front_page();
$work_url = $is_home ? '#work' : $home_url . '#work';
$stories_url = $is_home ? '#engineering-stories' : $home_url . '#engineering-stories';
$approach_url = $is_home ? '#approach' : $home_url . '#approach';
$contact_url = home_url('/contact/');
$current_post_id = is_singular('case_study') ? get_queried_object_id() : 0;
?>

<header class="site-header" data-site-header>
  <div class="container site-header__inner">
    <a class="site-logo" href="<?php echo esc_url($home_url); ?>" aria-label="Go to homepage">
      <span class="site-logo__mark">MV</span>
      <span class="site-logo__text"><?php bloginfo('name'); ?></span>
    </a>

    <button
      class="site-nav-toggle"
      type="button"
      aria-expanded="false"
      aria-controls="site-primary-nav"
      data-site-nav-toggle
    >
      <span class="material-symbols-rounded" aria-hidden="true">menu</span>
      <span class="screen-reader-text">Toggle navigation</span>
    </button>

    <nav id="site-primary-nav" class="site-nav" aria-label="Primary navigation" data-site-nav>
      <ul class="site-nav__menu">
        <li class="site-nav__item site-nav__item--has-submenu" data-nav-dropdown>
          <button
            class="site-nav__button"
            type="button"
            aria-expanded="false"
            aria-controls="site-nav-work"
            data-nav-dropdown-toggle
          >
            Work
            <span class="material-symbols-rounded" aria-hidden="true">expand_more</span>
          </button>

          <ul id="site-nav-work" class="site-nav__submenu" data-nav-dropdown-menu>
            <?php if ($case_studies): ?>
              <?php foreach ($case_studies as $case_study): ?>
                <li>
                  <a
                    href="<?php echo esc_url(get_permalink($case_study)); ?>"
                    <?php echo $current_post_id === $case_study->ID ? 'aria-current="page"' : ''; ?>
                  >
                    <?php echo esc_html(get_the_title($case_study)); ?>
                  </a>
                </li>
              <?php endforeach; ?>
            <?php else: ?>
              <li><a href="<?php echo esc_url($work_url); ?>">Selected work</a></li>
            <?php endif; ?>
          </ul>
        </li>

        <li class="site-nav__item site-nav__item--has-submenu" data-nav-dropdown>
          <button
            class="site-nav__button"
            type="button"
            aria-expanded="false"
            aria-controls="site-nav-engineering-stories"
            data-nav-dropdown-toggle
          >
            Engineering Stories
            <span class="material-symbols-rounded" aria-hidden="true">expand_more</span>
          </button>

          <ul id="site-nav-engineering-stories" class="site-nav__submenu" data-nav-dropdown-menu>
            <?php if ($engineering_stories): ?>
              <?php foreach ($engineering_stories as $story): ?>
                <li>
                  <a
                    href="<?php echo esc_url(get_permalink($story)); ?>"
                    <?php echo $current_post_id === $story->ID ? 'aria-current="page"' : ''; ?>
                  >
                    <?php echo esc_html(get_the_title($story)); ?>
                  </a>
                </li>
              <?php endforeach; ?>
            <?php else: ?>
              <li><a href="<?php echo esc_url($stories_url); ?>">Engineering Stories</a></li>
            <?php endif; ?>
          </ul>
        </li>

        <li class="site-nav__item">
          <a href="<?php echo esc_url($approach_url); ?>">Approach</a>
        </li>

        <li class="site-nav__item">
          <a href="<?php echo esc_url($contact_url); ?>" <?php echo is_page('contact') ? 'aria-current="page"' : ''; ?>>Let’s talk</a>
        </li>
      </ul>
    </nav>

    <button class="theme-toggle" type="button" aria-label="Toggle theme">
      <span class="material-symbols-rounded" aria-hidden="true">light_mode</span>
    </button>
  </div>
</header>
