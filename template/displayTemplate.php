<?php
global $post;
wp_head();
$shortcodeString = '[constant_contact_forms id="postId"]';
$shortcodeString = str_replace('postId', $post->ID, $shortcodeString);
echo do_shortcode($shortcodeString);
wp_footer();
?>