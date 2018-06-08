<?php 
/**
 * masonry
 * @since    1.0.0
 */
 
defined('ABSPATH') || die('Direct access denied!');
 
global $out_posts; 
global $post;

?>

<div class="masonry">

<?php foreach ($out_posts as $post) {
	setup_postdata( $post );?>
	
	<div class="item">
		<image class="image" src="<?php the_post_thumbnail_url();?>">
		<p><strong>Title :</strong> <?php the_title();?></p>
		<strong>Description:</strong> <?php the_content();?>
		<p><strong>Position:</strong> <?php the_terms($post->ID, 'position');?></p>
		<p><strong>Organization:</strong> <?php the_terms($post->ID, 'organization');?></p>
		<p><strong>Sallary:</strong> $<?php echo get_post_meta( $post->ID, 'sall', true );?></p>
		<p><strong>Birth Date:</strong> <?php echo get_post_meta( $post->ID, 'birthday', true );?></p>
	</div>

<?php } ?>
   
</div>