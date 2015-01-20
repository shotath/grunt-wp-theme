<!DOCTYPE html>
<html lang="ja">

<meta property="og:title" content="<?php bloginfo('name'); ?>">
<meta property="og:type" content="website">
<meta property="og:description" content="<?php bloginfo('description'); ?>">
<meta property="og:url" content="<?php echo home_url(); ?>">
<meta property="og:image" content="<?php bloginfo('template_directory'); ?>/files/images/ogimage.png">
<meta property="og:locale" content="ja_JP">

<meta name="robots" content="INDEX,FOLLOW">

<meta name="description" content="<?php echo $meta['description']; ?>">
<meta name="keywords" content="<?php echo $meta['keywords']; ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">

<title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>

<link rel="shortcut icon" type="image/x-icon" href="<?php bloginfo('template_directory'); ?>/files/images/favicon.ico" />
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.17.2/build/cssreset/cssreset-min.css">

<!--[if lt IE 9]><![endif]-->

<?php wp_deregister_script('jquery'); ?>
<?php wp_head(); ?>
