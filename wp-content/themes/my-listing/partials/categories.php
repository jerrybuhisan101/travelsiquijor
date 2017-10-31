<?php
    $data = c27()->merge_options([
            'skin' => 'transparent',
            'ids' => '',
            'align' => 'center',
        ], $data);
 ?>

 <div class="<?php echo esc_attr( 'text-' . $data['align'] ) ?>">
    <div class="featured-categories <?php echo esc_attr( $data['skin'] ) ?>" style="display: inline-block;">
        <ul>
            <?php foreach ((array) explode(',', (string) $data['ids']) as $category_id):
                $category = get_term(trim($category_id), 'job_listing_category');
                if ( ! is_object( $category ) ) continue;

                $icon = get_field('icon', 'job_listing_category_' . $category->term_id) ? : c27()->defaults()['category']['icon'];

                if (is_wp_error($category)) continue;
                ?>

                <li class="reveal text-center">
                    <a href="<?php echo esc_url( get_term_link( $category ) ) ?>">
                        <div class="slc-icon">
                            <i class="<?php echo esc_attr( $icon ) ?>"></i>
                        </div>
                        <div class="slc-info">
                            <p><?php echo esc_html( $category->name ) ?></p>
                        </div>
                    </a>
                </li>

            <?php endforeach ?>
        </ul>
    </div>
</div>