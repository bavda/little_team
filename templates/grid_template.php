<?php 
/**
 * GRID
 * @since    1.0.0
 */
 
defined('ABSPATH') || die('Direct access denied!');
 
global $out_posts; 
global $post;

?>

<div class="grid-container">

<?php foreach ($out_posts as $post) {
	setup_postdata( $post );?>
	
	<div class="item">
		<image src="<?php the_post_thumbnail_url();?>">
		<strong>Title :</strong> <?php the_title();?>
		<strong>Description:</strong> <?php the_content();?>
		<strong>Position:</strong> <?php the_terms($post->ID, 'position');?>
		<strong>rganization:</strong> <?php the_terms($post->ID, 'organization');?>
		<strong>Sallary:</strong> $<?php echo get_post_meta( $post->ID, 'sall', true );?>
		<strong>Birth Date:</strong> <?php echo get_post_meta( $post->ID, 'birthday', true );?>
	</div>

<?php } ?>

</div>