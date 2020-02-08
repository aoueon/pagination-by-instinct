<?php 
/**
 * Plugin Name: Pagination by instinct
 * -Plugin URI: https://aniomalia.com/plugins/pagination-by-instinct/
 * Author: Aniomalia
 * Author URI: https://aniomalia.com/
 * Description: Format pagination the way you like it using the generated array, or use our recommended output.
 * Version: 1.0
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

/* Enqueue necessary assets */
function aniomalia_pagination_assets() {
    $plugin_url = plugin_dir_url( __FILE__ );
    wp_enqueue_style( 'aniomalia-pagination-style',  $plugin_url . 'css/style.css');
}
add_action( 'wp_enqueue_scripts', 'aniomalia_pagination_assets' );

/* Output array of pagination */
function get_aniomalia_pagination() {

    if ( is_singular() ) return;
    global $wp_query;
    if ( $wp_query->max_num_pages <= 1 ) return;

    $current = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
    $total = intval( $wp_query->max_num_pages );

    $pagination = array();

    $pagination['total'] = $total;

    $pagination['first'] = get_pagenum_link(1);
    $pagination['previous'] = ( $current != 1 ) ? get_pagenum_link($current-1) : false;
    $pagination['current'] = $current;
    $pagination['next'] = ( $current != $total ) ? get_pagenum_link($current+1) : false;
    $pagination['last'] = get_pagenum_link($total);

    for ( $i = 1; $i < ($total + 1); $i++ ) {
        $pagination['pages'][] = array(
            'page' => $i,
            'url' => get_pagenum_link($i),
            'current' => ( $i == $current ) ? 1 : 0
        );
    }


    return $pagination;

}

/* Output formatted pagination */
function aniomalia_pagination($show_pages = 5, $show_adjacent = true, $show_ends = true) {

    $pages = get_aniomalia_pagination();

    $show_all_pages = ( $show_pages == -1 ) ? true : false;

    if ( ! $show_all_pages && $show_pages ) {
        $max = ( $show_pages > 0 ) ? $show_pages : 5;
        $max_before = ( $max > 2 ) ? floor( ($max-1) / 2) : 0;
        $max_after = ceil( ($max-1) / 2);
    }

    ob_start();
    ?>

    <div class="aniomalia-pagination">

        <?php if ( ! $show_all_pages && $pages['total'] !== $max ) : ?>

            <?php if ( $show_ends && $pages['current'] !== 1 ) : ?>
            <span class="aniomalia-pagination-item aniomalia-pagination-item-non-page aniomalia-pagination-item-first">
                <a href="<?php echo $pages['first']; ?>"><span>«</span></a>
            </span>
            <span class="aniomalia-pagination-separator"></span>
            <?php endif; ?>

            <?php if ( $show_adjacent && $pages['previous'] && $pages['previous'] !== $pages['first'] ) : ?>
            <span class="aniomalia-pagination-item aniomalia-pagination-item-non-page aniomalia-pagination-item-previous">
                <a href="<?php echo $pages['previous']; ?>"><span>‹</span></a>
            </span>
            <span class="aniomalia-pagination-separator"></span>
            <?php endif; ?>

        <?php endif; ?>

        <?php if ( $show_pages && $pages['pages'] ) : foreach ( $pages['pages'] as $key => $page ) : 

            $i = $key + 1;

            if ( ! $show_all_pages && $pages['total'] !== $max ) {
                if ( $pages['current'] < $max ) {
                    // at start
                    if ( $i > $max ) break;
                } elseif ( $pages['current'] > $pages['total'] - $max_after ) {
                    // at end
                    if ( $i <= $pages['total'] - $max ) continue;
                } else {
                    // in middle
                    if ( $i < ( $pages['current'] - $max_before) ) continue;
                    if ( $i > ( $pages['current'] + $max_after) ) continue;
                }

            }
            ?>

            <span class="aniomalia-pagination-item aniomalia-pagination-item-page <?php echo ( $page['current'] ) ? ' aniomalia-pagination-item-current' : ''; ?>">

                <?php if ( ! $page['current'] ) : ?>
                    <a href="<?php echo $page['url']; ?>">
                <?php endif; ?>
                    <?php echo $page['page']; ?></a>
                <?php if ( ! $page['current'] ) : ?>
                    </a>
                <?php endif; ?>

            </span>


            <?php
            // Add a separator except for the last item
            if ( $key < count( $pages['pages'] ) - 1 ) :
            ?>
            <span class="aniomalia-pagination-separator"></span>
            <?php endif; ?>

        <?php endforeach; endif; ?>

        <?php if ( ! $show_all_pages && $pages['total'] !== $max ) : ?>

            <?php if ( $show_adjacent && $pages['next'] && $pages['next'] !== $pages['last'] ) : ?>
            <span class="aniomalia-pagination-separator"></span>
            <span class="aniomalia-pagination-item aniomalia-pagination-item-non-page aniomalia-pagination-item-next">
                <a href="<?php echo $pages['next']; ?>"><span>›</span></a>
            </span>
            <?php endif; ?>

            <?php if ( $show_ends == true && $pages['current'] !== $pages['total'] ) : ?>
            <span class="aniomalia-pagination-separator"></span>
            <span class="aniomalia-pagination-item aniomalia-pagination-item-non-page aniomalia-pagination-item-last">
                <a href="<?php echo $pages['last']; ?>"><span>»</span></a>
            </span>
            <?php endif; ?>
        
        <?php endif; ?>

    </div>
    <?php
    return ob_get_contents();

}