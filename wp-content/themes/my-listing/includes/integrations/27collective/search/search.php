<?php

class CASE27_Integrations_Search extends CASE27_Ajax {

    public function __construct()
    {
        $this->register_action('c27_search_results');

        parent::__construct();
    }


    public function c27_search_results()
    {
        check_ajax_referer( 'c27_ajax_nonce', 'security' );

        $search_term = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';

        $categories = get_terms([
            'taxonomy' => 'job_listing_category',
            'search' => $search_term,
            'number' => 2,
        ]);

        $listings = get_posts([
            's' => $search_term,
            'post_type' => 'job_listing',
            'posts_per_page' => 7 - count($categories),
            'meta_query' => [[
                'key' => '_case27_listing_type',
                'value' => '',
                'compare' => '!=',
            ]],
        ]);

        $listings_grouped = [];

        foreach ($listings as $listing) {
            $type = get_post_meta($listing->ID, '_case27_listing_type', true);

            if (!isset($listings_grouped[$type])) $listings_grouped[$type] = [];

            $listings_grouped[$type][] = $listing;
        }

        ob_start();
        ?>

        <?php if (!is_wp_error($categories) && count($categories)): ?>
            <li class="ir-cat"><?php _e( 'Categories', 'my-listing' ) ?></li>

            <?php foreach ($categories as $category):
                $icon = get_field('icon', 'job_listing_category_' . $category->term_id) ? : c27()->defaults()['category']['icon'];
                $color = get_field('color', 'job_listing_category_' . $category->term_id) ? : c27()->defaults()['category']['color'];
                $text_color = get_field('text_color', 'job_listing_category_' . $category->term_id) ? : c27()->defaults()['category']['text_color'];
                ?>
                <li>
                    <a href="<?php echo esc_url( get_term_link($category) ) ?>">
                        <span class="cat-icon" style="background-color: <?php echo esc_attr( $color ) ?>;">
                            <i class="<?php echo esc_attr( $icon ) ?>" style="color: <?php echo esc_attr( $text_color ) ?>;"></i>
                        </span>
                        <span class="category-name"><?php echo esc_html( $category->name ) ?></span>
                    </a>
                </li>
            <?php endforeach ?>
        <?php endif ?>

        <?php if ($listings_grouped): ?>
            <?php foreach ($listings_grouped as $type => $group): ?>
                <?php $type_settings = c27()->get_listing_type_options($type, ['settings'] )['settings'] ?>
                <li class="ir-cat"><?php echo $type_settings['plural_name'] ?></li>

                <?php foreach ($group as $listing): $image = job_manager_get_resized_image( get_post_meta($listing->ID, '_job_logo', true), 'thumbnail') ?>
                    <li>
                        <a href="<?php echo esc_url( get_permalink($listing) ) ?>">
                            <div class="avatar">
                                <?php if ($image): ?>
                                    <img src="<?php echo esc_url( $image ) ?>">
                                <?php endif ?>
                            </div>
                            <span class="category-name"><?php echo esc_html( $listing->post_title ) ?></span>
                        </a>
                    </li>
                <?php endforeach ?>
            <?php endforeach ?>
        <?php endif ?>

        <?php

        return $this->json([
            'html' => ob_get_clean(),
            'found_posts' => count($listings),
            ]);
    }
}

new CASE27_Integrations_Search;
