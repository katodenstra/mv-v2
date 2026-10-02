<?php $gallery_items = mvillada_project_story_gallery_items(); ?>

<?php if ($gallery_items): ?>
    <section id="gallery" class="project-story-section project-story-gallery" data-project-story-section>
        <h2>Gallery</h2>

        <?php foreach ($gallery_items as $item): ?>
            <figure class="project-story-gallery__item project-story-gallery__item--<?php echo esc_attr($item['width']); ?>">
                <?php echo mvillada_project_story_image($item['image'], 'large'); ?>

                <?php if ($item['caption']): ?>
                    <figcaption><?php echo esc_html($item['caption']); ?></figcaption>
                <?php endif; ?>
            </figure>
        <?php endforeach; ?>
    </section>
<?php endif; ?>
