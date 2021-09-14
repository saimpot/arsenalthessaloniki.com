<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Wenger_Lives_On
 */

get_header();
$teams_args = [
	'post_type'      => 'team',
	'posts_per_page' => - 1,
	'hide_empty'     => true,
    'orderby'        => 'position',
    'order'          => 'ASC'
];
$teams = new WP_Query( $teams_args );

?>
    <div class="styled-top-bar"></div>
    <nav class="navbar" role="navigation" aria-label="main-navigation">
        <div class="navbar-inner">
            <div class="container">
                <div class="navbar-brand">
                    <a class="navbar-item" href="<?php echo get_bloginfo('siteurl'); ?>" title="<?php echo get_bloginfo('name'); ?> - <?php echo get_bloginfo('description'); ?>" class="navbar-item">
                        <img src="<?php echo get_stylesheet_directory_uri() . '/assets/images/logo.png'; ?>" alt="<?php echo get_bloginfo('name'); ?> - <?php echo get_bloginfo('description'); ?>">
                    </a>
                </div>

                <div class="navbar-menu">
                    <div class="navbar-start">
                        <a href="asdf" class="navbar-item is-active">Αρχικη</a>
                        <a href="asdf" class="navbar-item">Νεα</a>
                        <a href="asdf" class="navbar-item">Το κλαμπ</a>
                        <a href="asdf" class="navbar-item">Shop</a>
                        <a href="asdf" class="navbar-item">Επικοινωνια</a>
                        <a href="asdf" class="navbar-item">Γινε μελος</a>
                    </div>
                    <div class="navbar-end">
                        <div class="navbar-item">
                            <a class="icon-link" href="">
                                <ion-icon name="color-palette-outline"></ion-icon>
                            </a>
                        </div>
                        <div class="navbar-item">
                            <a class="icon-link" href="">
                                <ion-icon name="search"></ion-icon>
                            </a>
                        </div>
                        <div class="navbar-item">
                            <a href="asdf" class="button is-link is-small cta">Γινε Μελος</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="navbar-team-rankings">
            <div class="container">
                <?php
                while($teams->have_posts()) {
	                $teams->the_post();
                    $attachmentUrl = get_the_post_thumbnail();
                    echo '<div class="team-crest">'.$attachmentUrl.'</div>';
                }
                ?>
            </div>
        </div>
        <div class="navbar__bg-image" style="background-image: url(<?php echo get_stylesheet_directory_uri() . '/assets/images/theme-bg.jpg'; ?>);">
            <div class="navbar__bg-overlay"></div>
        </div>
    </nav>
<?php
get_footer();
