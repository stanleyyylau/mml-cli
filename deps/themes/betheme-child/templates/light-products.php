<?php
/**
 * Template Name: MML-light-products

 * @package Betheme
 * @author Muffin Group
 */

get_header();
?>
    
<!-- #Content -->

<div class="banner-wrap">
    <?php
while( have_posts() ){
                        the_post();                         // Post Loop
                        mfn_builder_print( get_the_ID() );  // Content Builder & WordPress Editor Content
                    }
$data = [
	'181' => 173,
	'156' => 154,
	'179' => 172,
	'185' => 174
];
$sid = $data[get_the_ID()];       
                    ?>
</div>

<div id="Content" class="page-product">
    <section class="products">
        <div class="container">
            <div class="filter-side">
                <div class="filter-inner">
                    <div class="page-title">
                        <h3>Category</h3>
                    </div>
                    <!-- Echo below if post id is 154 -->
                    <?php echo do_shortcode('[searchandfilter id="' . $sid . '"]'); ?>

                    <!-- Echo below if post id is 172 -->
                    <?php //echo do_shortcode('[searchandfilter id="172"]'); ?>

                    <!-- Echo below if post id is 173 -->
                    <?php //echo do_shortcode('[searchandfilter id="173"]'); ?>

                    <!-- Echo below if post id is 174 -->
                    <?php //echo do_shortcode('[searchandfilter id="174"]'); ?>
                </div>
               
            </div>
            <div class="product-wrap">
                <div class="top-filter-bar">
                    
                </div>
                <!-- Echo below if post id is 154 -->
                <?php echo do_shortcode('[searchandfilter id="' . $sid . '" show="results"]'); ?>

                <!-- Echo below if post id is 172 -->
                <?php //echo do_shortcode('[searchandfilter id="172"]'); ?>

                <!-- Echo below if post id is 173 -->
                <?php //echo do_shortcode('[searchandfilter id="173"]'); ?>

                <!-- Echo below if post id is 174 -->
                <?php //echo do_shortcode('[searchandfilter id="174"]'); ?>
            </div>
       
    </div>
    </section>
</div>
<script type="text/javascript">
    

    // if($('.top-filter-bar').length == 0){
    //         $('.top-filter-bar').css('display','none');
    //     } else{
    //          $('.top-filter-bar').css('display','block');
    //     }
</script>
<?php get_footer();

// Omit Closing PHP Tags