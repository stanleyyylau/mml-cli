<?php
/**
 * Search & Filter Pro 
 *
 * Sample Results Template
 * 
 * @package   Search_Filter
 * @author    Ross Morsali
 * @link      http://www.designsandcode.com/
 * @copyright 2015 Designs & Code
 * 
 * Note: these templates are not full page templates, rather 
 * just an encaspulation of the your results loop which should
 * be inserted in to other pages by using a shortcode - think 
 * of it as a template part
 * 
 * This template is an absolute base example showing you what
 * you can do, for more customisation see the WordPress docs 
 * and using template tags - 
 * 
 * http://codex.wordpress.org/Template_Tags
 *
 */

if ( $query->have_posts() )
{
	
?>
	
<div class="all-products-wrap">
        <?php
        while ($query->have_posts())
        {
                $query->the_post();

                ?>
                <a class="product-item" href="<?php the_permalink(); ?>">
                                        <div class="img-wrap">
                                                <?php 
                                                        if ( has_post_thumbnail() ) { 
                                                                $featureImg1 = get_the_post_thumbnail_url(get_the_ID(),'full');
                                                                $featureImg2 = get_field('feature_image_2', get_the_ID());
                                                        ?>
                                                                <img src="<?php echo $featureImg1;?>" class="feature-image feature-1">
                                                                <img src="<?php echo $featureImg2;?>" class="feature-image feature-2">
                                                <?php
                                                        }
                                                ?>
                                        </div>
                                <h2 class="product-title"><?php the_title(); ?></h2>
                </a>

                <?php
        }
        ?>
        </div>
	
	<div class="pagination">
		
		
		<?php
			/* example code for using the wp_pagenavi plugin */
			if (function_exists('wp_pagenavi'))
			{
				echo "<br />";
				wp_pagenavi( array( 'query' => $query ) );
			}
		?>
	</div>
	<?php
}
else
{
	echo "No Results Found";
}
?>
