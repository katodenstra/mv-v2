<?php get_header(); ?>

<main class="site-main">
  <section class="home-section">
    <div class="container">
      <?php
      if (have_posts()):
        while (have_posts()):
          the_post();
          ?>
          <article>
            <p class="eyebrow">Page</p>
            <h1><?php the_title(); ?></h1>
            <div class="section-intro">
              <?php the_content(); ?>
            </div>
          </article>
          <?php
        endwhile;
      endif;
      ?>
    </div>
  </section>
</main>

<?php get_footer(); ?>