<?php
$testimonials = array(
  array(
    'quote' => 'He developed the website for my law firm, as well as managed social media follow-ups and required updates.<br><br>Always with <strong>great professionalism, commitment, and timely delivery.</strong>',
    'author' => 'Thelma Riva',
    'context' => 'General Manager, ALEPI',
  ),
  array(
    'quote' => 'I have worked on several projects with Mauricio Villada, and I always found in him dedication, skills, and high-level knowledge, all of which had a <strong>positive impact on client results.</strong> Highly recommended.',
    'author' => 'Juan Carlos Hermosillo',
    'context' => 'Paid Traffic Manager, Atrevia',
  ),
  array(
    'quote' => 'I worked with Mauricio on a couple of projects with very good results.<br><br>There was excellent communication at all times, and <strong>he guided us on the best decisions according to our goals and budget.</strong>',
    'author' => 'Enrique Islas',
    'context' => 'Creative Director',
  ),
  array(
    'quote' => 'Mauricio supported us with the creation of our website, company email accounts, and training so we could manage configuration changes ourselves. He also always makes sure to stay on top of renewals and payments that need to be made. <strong>He is very kind, punctual, and professional.</strong> Highly recommended.',
    'author' => 'Erika Del Arco Cervantes',
    'context' => 'Manager at OCEFREM',
  ),
  array(
    'quote' => 'I have had the opportunity to collaborate frequently with Mauricio, on both small client projects and larger initiatives involving key stakeholders. Together, we have built and designed user experiences, developed websites, implemented improvements, and analyzed areas of opportunity for a variety of clients.<br><br><strong>His collaborative approach, attention to detail, and commitment to client satisfaction</strong> make working with him both effective and enjoyable.',
    'author' => 'Araceli Diaz',
    'context' => 'Product UX/UI Designer',
  ),
);
?>

<section class="home-section credibility-block" aria-labelledby="testimonials-title">
  <div class="container">
    <div class="credibility-block__intro">
      <h2 id="testimonials-title">What people say</h2>
    </div>

    <div class="testimonial-slider" data-testimonial-slider>
      <button class="testimonial-slider__control testimonial-slider__control--prev" type="button" data-testimonial-prev
        aria-label="Show previous testimonial">
        <span class="material-symbols-rounded" aria-hidden="true">arrow_back</span>
      </button>

      <div class="testimonial-slider__viewport" aria-live="polite">
        <div class="testimonial-slider__slides">
          <?php foreach ($testimonials as $index => $testimonial): ?>
            <figure class="testimonial-slide <?php echo 0 === $index ? ' is-active' : ''; ?>" data-testimonial-slide
              aria-hidden="<?php echo 0 === $index ? 'false' : 'true'; ?>">
              <blockquote>
                <p><?php echo wp_kses_post($testimonial['quote']); ?></p>
              </blockquote>
              <figcaption>
                <span class="testimonial-slide__author"><?php echo esc_html($testimonial['author']); ?></span>
                <span class="testimonial-slide__context"><?php echo esc_html($testimonial['context']); ?></span>
              </figcaption>
            </figure>
          <?php endforeach; ?>
        </div>
      </div>

      <button class="testimonial-slider__control testimonial-slider__control--next" type="button" data-testimonial-next
        aria-label="Show next testimonial">
        <span class="material-symbols-rounded" aria-hidden="true">arrow_forward</span>
      </button>

      <div class="testimonial-slider__dots" role="group" aria-label="Choose testimonial">
        <?php foreach ($testimonials as $index => $testimonial): ?>
          <button class="testimonial-slider__dot<?php echo 0 === $index ? ' is-active' : ''; ?>" type="button"
            data-testimonial-dot="<?php echo esc_attr($index); ?>"
            aria-label="<?php echo esc_attr(sprintf('Show testimonial %d', $index + 1)); ?>"
            aria-current="<?php echo 0 === $index ? 'true' : 'false'; ?>"></button>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>