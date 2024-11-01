<?php

function top_viewed_posts_shortcode($atts) {
    $atts = shortcode_atts(
        [
            'category_id' => ''
        ],
        $atts,
        'top_viewed_posts'
    );

    $args = [
        'post_type' => 'post',
        'posts_per_page' => 10,
        'meta_key' => 'ekit_post_views_count',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
    ];

    if (!empty($atts['category_id'])) {
        $args['cat'] = $atts['category_id'];
    } elseif (is_category()) {
        $args['cat'] = get_queried_object_id(); // if no param with category, so current category
    } 

    $query = new WP_Query($args);

    $output = '<div class="top-viewed-posts margin-bottom-50">';
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $output .= '<div class="item">';
            $output .= '<a href="' . get_permalink() . '">';
            if (has_post_thumbnail()) {
                $output .= get_the_post_thumbnail(get_the_ID(), 'medium');
            }
            $output .= '<div class="item-title">' . get_the_title() . '</div>';
            $output .= '</a>';
            $output .= '</div>';
        }
    } else {
        $output .= 'No posts found.';
    }
    $output .= '</div>';

    wp_reset_postdata();

    return $output;
}

add_shortcode('top_viewed_posts', 'top_viewed_posts_shortcode');


function categ_list_shortcode(){
	$current_category_id = get_queried_object_id();
	$categories = get_categories([
		'exclude' => [$current_category_id, 1],
		'hide_empty' => false
		]);

	if ($categories) {
		echo '<div class="category-list-inline">';
		foreach ($categories as $category) {
			echo '<div class="item-categ-list"><a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a></div>';
		}
			echo '</div>';
	}
}

add_shortcode('categ_list', 'categ_list_shortcode');

?>
