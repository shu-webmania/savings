<?php
$stylesheet_uri = get_stylesheet_directory_uri();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="format-detection" content="telephone=no">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <header id="header" class="header">
        <div class="header__inner">
            <div class="header__logo">
                <img src="" alt="節約の達人">
            </div>
        </div>
    </header><!-- #header -->