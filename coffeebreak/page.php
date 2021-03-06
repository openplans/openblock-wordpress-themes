<?php get_header(); ?>
       
	<div id="featured">
        <div id="page-title" class="content">
            <h1 class="page"><?php the_title(); ?></h1>
        </div>
    </div>
           
    <!-- Content Starts -->
	<div id="main-content" class="page">           
    <div class="content">
		<div class="col-left">
			<div id="main">
            
            <?php if (have_posts()) : $count = 0; ?>
            <?php while (have_posts()) : the_post(); $count++; ?>
                                                                        
                <!-- Post Starts -->
                <div class="post wrap">
                    
                    <?php the_content(); ?>
                    
					<?php if ('open' == $post->comment_status) : ?>
                    <div id="comments">
                        <?php comments_template(); ?>
                    </div>
					<?php endif; ?>

                </div>
                <!-- Post Ends -->
                                                    
			<?php endwhile; else: ?>
                <p><?php _e('Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
            <?php endif; ?>  
        
            </div><!-- main ends -->
        </div><!-- .col-left ends -->

        <?php get_sidebar(); ?>

	</div>
    </div><!-- Content Ends -->
		
<?php get_footer(); ?>