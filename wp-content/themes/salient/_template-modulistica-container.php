<?php
/*template name: template-modulistica-container*/
get_header(); ?>
<? // fornitore

//fieldgroup
$modulistica = 'modulistica';

//repeater
$carica_modulistica = 'carica_'.$modulistica;

//subfields
$assegna_categoria = 'assegna_categoria';
$carica_file = 'carica_file';
?>

<?php nectar_page_header($post->ID);  ?>

<?php
$options = get_nectar_theme_options();


//wp_enqueue_script('nectarMap', get_template_directory_uri() . '/js/map.js', array('jquery'), '1.0', TRUE);


?>

<div class="container-wrap">

	<div class="container main-content">

		<div class="row">

<?php //if( !post_password_required( $post )): ?>
	<!-- #################################################################### -->
	<?php include_once('modulistica-core.php'); ?>
	<!-- ####################################################################/copiato -->
<?php //endif; ?>

<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
	<?php the_content(); ?>
			<?php endwhile; endif; ?>


		</div><!--/row-->

	</div><!--/container-->

</div>
<?php get_footer(); ?>
