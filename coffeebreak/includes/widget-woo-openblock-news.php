<?php
// =============================== News from the blog widget ======================================

class Woo_OpenBlockNews extends WP_Widget {

   function Woo_OpenBlockNews() {
	   $widget_ops = array('description' => 'Show Latest Openblock Tumblr Blog widget' );
       parent::WP_Widget(false, __('Woo - OpenBlock News', 'woothemes'),$widget_ops);      
   }

   function widget($args, $instance) {  
    extract( $args );
   	$title = $instance['title']; if (!$title) $title = 'News from the blog';
   	$number = $instance['number'];
	$size = $instance['size']; if (!$size && size <> 0) $size = 70;
	$align = $instance['align']; if (!$align) $align = 'alignright';
        $feedurl = 'http://blog.openblockproject.org/rss';
	?>


               <?php echo $before_widget; ?>

               <h3><a href="http://blog.openblockproject.org"><?php echo $title; ?></a></h3>
               <a class="rss" href="<?php echo $feedurl ?>" title="Subscribe to our RSS feed"><img src="/newtest/wp-content/themes/inspire/images/ico-rss-big.png" alt="RSS"/></a>

                <?php
                 require_once (ABSPATH . WPINC . '/rss.php'); $rss = fetch_rss($feedurl);
                 if ( $rss ) {
                   $rss->items = array_slice($rss->items, 0, 4);
                   foreach ($rss->items as $item ) {

                     $descr = strip_tags($item['description']);
                     $words = preg_split('/\s+/', $descr);
                     $words = array_slice($words, 0, 35);
                     $descr = join(' ', $words);
                     $pubdate = date('F j, Y', strtotime($item['pubdate']));

                     echo "<div class='item'>\n";
                     echo " <p class='post-meta'>\n";
                     echo "  <span class='post-date'>$pubdate</span>";
                     echo " </p>\n";
                     echo "<a class='title' href='$item[link]'>";
                     echo htmlentities($item['title']);
                     echo "</a>\n";
                     echo " <p class='descr'>\n";
                     echo $descr;
                     echo "&nbsp;... \n</p> </div>\n";

                 }};

                 ?>

               <p><b><a href="http://blog.openblockproject.org">More from the Blog...</a></b></p>
               <?php echo $after_widget; ?>
   
   <?php
   }

   function update($new_instance, $old_instance) {                
       return $new_instance;
   }

   function form($instance) {        
   
       $title = esc_attr($instance['title']);
       $number = esc_attr($instance['number']);
       $size = esc_attr($instance['size']);
       $align = esc_attr($instance['align']);
       ?>
       <p>
	   	   <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','woothemes'); ?></label>
	       <input type="text" name="<?php echo $this->get_field_name('title'); ?>"  value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
       </p>
       <p>
	   	   <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number:','woothemes'); ?></label>
	       <input type="text" name="<?php echo $this->get_field_name('number'); ?>"  value="<?php echo $number; ?>" class="widefat" id="<?php echo $this->get_field_id('number'); ?>" />
       </p>
       <p>
	   	   <label for="<?php echo $this->get_field_id('size'); ?>"><?php _e('Thumbnail Size (0 disable):','woothemes'); ?></label>
	       <input type="text" name="<?php echo $this->get_field_name('size'); ?>"  value="<?php echo $size; ?>" class="widefat" id="<?php echo $this->get_field_id('size'); ?>" />
       </p>
        <p>
            <label for="<?php echo $this->get_field_id('align'); ?>"><?php _e('Thumb Align:','woothemes'); ?></label>
            <select name="<?php echo $this->get_field_name('align'); ?>" class="widefat" id="<?php echo $this->get_field_id('align'); ?>">
                <option value="alignleft" <?php if($align == "alignleft"){ echo "selected='selected'";} ?>><?php _e('Left', 'woothemes'); ?></option>
                <option value="alignright" <?php if($align == "alignright"){ echo "selected='selected'";} ?>><?php _e('Right', 'woothemes'); ?></option>            
            </select>
        </p>
        <p>
      <?php
   }
} 

register_widget('Woo_OpenBlockNews');
