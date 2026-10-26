<?php
/** Template Name: Contact */
get_header();
?>
<main class="site-main">
  <section class="home-section contact-section" aria-labelledby="contact-title">
    <div class="container contact-layout">
      <header class="contact-intro">
        <p class="eyebrow">Get in touch</p>
        <h1 id="contact-title">Let’s talk.</h1>
        <p class="contact-lead">Good work starts with a clear conversation.</p>
        <p>Hiring for a product role, building a design system, or working through a complex interface? Tell me what you have in mind.</p>
        <p class="contact-alternative">Prefer to connect on LinkedIn?<br><a href="https://www.linkedin.com/in/mauricio-villada-ruiz-31a336a0/">Visit my profile <span aria-hidden="true">↗</span></a></p>
      </header>
      <div class="contact-panel">
        <h2>What are you working on?</h2>
        <p>A little context is all we need to start. All fields are required.</p>
        <?php while (have_posts()): the_post(); ?>
          <?php if (shortcode_exists('fluentform') && has_shortcode(get_the_content(), 'fluentform')): ?>
            <div class="contact-form"><?php the_content(); ?></div>
            <p class="contact-privacy">I’ll use the details you share to respond to your enquiry.
              <?php if (get_privacy_policy_url()): ?>
                <a href="<?php echo esc_url(get_privacy_policy_url()); ?>">Privacy policy</a>
              <?php endif; ?>
            </p>
          <?php else: ?>
            <p role="status">The contact form is temporarily unavailable. You can reach me on <a href="https://www.linkedin.com/in/mauricio-villada-ruiz-31a336a0/">LinkedIn</a>.</p>
          <?php endif; ?>
        <?php endwhile; ?>
      </div>
    </div>
  </section>
</main>
<?php get_footer(); ?>
