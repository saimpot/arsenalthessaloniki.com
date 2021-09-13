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
                    <div class="navbar-start"><a href="asdf" class="navbar-item">Left link</a></div>
                    <div class="navbar-end">
                        <div class="navbar-item">
                            <a href="asdf" class="button is-link is-small cta">Gine melos</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="navbar-team-rankings"></div>
        <div class="navbar__bg-image" style="background-image: url(<?php echo get_stylesheet_directory_uri() . '/assets/images/theme-bg.jpg'; ?>);">
            <div class="navbar__bg-overlay"></div>
        </div>
    </nav>
<?php
get_footer();
