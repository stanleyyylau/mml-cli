<?php
get_header();
?>


<div id="container" class="container">
    <div id="content" role="main">
   <!---end-------> 
   
   
   <div class="cool_timeline_single">
        <div class="ctl-story-detials ctl-left">
            <?php
            // Start the loop.
            while (have_posts()) : the_post();
                ?>
                <div class="ctl-story-type-cont ">
                    <?php
                      /*
                       * Story Content Type
                       * 
                       * Image/Video/Slideshow
                       */  
                    get_cool_timeline_template('single-story-content');
                    ?>
                </div>

                <header class="ctl-header-entry">
                    <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                </header><!-- .entry-header -->
                <div class="story-date-cont">
                    <ul class="ctl-related-stories">
                        <li>
                            <div class="ctl-related-stories-badge info-even story_date"><i class="fa fa-circle"></i></div>
                            <div class="ctl-related-stories-panel">
                                <div class="ctl-related-stories-heading">
                                    <h4 class="story-date"> 
                                    <?php 
                                       /*
                                        * Story Date helper function
                                        */
                                    
                                   echo  ctl_get_story_date(get_the_ID()); 
                                     ?></h4>

                                </div>
                            </div>
                        </li>  
                    </ul> 

                </div>
                <div class="entry-content">
                    <?php
                    /*
                     * Story Content
                     */
                    the_content();
                    ?>
                </div>

                <?php
            endwhile;
            wp_reset_query();
            ?> 
        </div>
        <div class="ctl-stories-links ctl-right">

            <div class="related-posts wrapper clearfix">
            <?php
                 /*
                 * Related stories to custom function
                 */
                    get_related_stories(get_the_ID());
                ?> 
            </div>

        </div>
    </div>  
   
<!---end------->   
    </div>
</div>
<!---------->   
<?php get_footer(); ?>
