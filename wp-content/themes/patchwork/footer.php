<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package Patchwork
 * @since Patchwork 1.0
 */
?>

	</div><!-- #main -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<?php do_action( 'patchwork_credits' ); ?>
			<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'patchwork' ) ); ?>" title="<?php esc_attr_e( 'A Semantic Personal Publishing Platform', 'patchwork' ); ?>" rel="generator"><?php printf( __( 'Proudly powered by %s', 'patchwork' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<?php printf( __( '%1$s by %2$s.', 'patchwork' ), 'Patchwork Theme', '<a href="http://carolinemoore.net/" rel="designer">Caroline Moore</a>' ); ?>
		</div><!-- .site-info -->
	</footer><!-- .site-footer .site-footer -->
	<div class="footer-bottom"></div>
</div><!-- #page .hfeed .site -->

<?php wp_footer(); ?>

</body>
</html>