<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<header class="site-header">

    <div class="container">

    <div class="header-inner">

        <?php if (has_custom_logo()) : ?>

            <?php the_custom_logo(); ?>

        <?php else : ?>

            <h1 class="site-title">
                <?php bloginfo('name'); ?>
            </h1>

        <?php endif; ?>

        <button class="menu-toggle" aria-label="Toggle navigation">
            ☰
        </button>

    </div>

    <nav class="primary-nav">

        <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'primary'
                )
            );
        ?>

    </nav>
    </div>

</header>