<?php
// =============================== Signup form ======================================

class OpenBlockSignup extends WP_Widget {

   function OpenBlockSignup() {
	   $widget_ops = array('description' => 'OpenBlock Signup form widget' );
       parent::WP_Widget(false, __('OpenBlock Signup', 'woothemes'),$widget_ops);      
   }

   function widget($args, $instance) {  
        extract( $args );
   	$title = $instance['title']; if (!$title) $title = 'Sign up for updates';
	?>


               <?php echo $before_widget; ?>
               <h3><?php echo $title; ?></h3>

	       <form id="ss-form" method="POST" action="https://spreadsheets.google.com/formResponse?formkey=dGtQVnhSTGp2VXB6b1pvR2g4R2xpNXc6MQ&amp;ifq">
                 <div class="selfclear"><label for="entry_0" class="ss-q-title">Name</label> <input type="text" id="entry_0" class="ss-q-short" value="" name="entry.0.single"></div>

                 <div class="selfclear"><label for="entry_1" class="ss-q-title">Email </label> <input type="text" id="entry_1" class="ss-q-short" value="" name="entry.1.single"></div>
                 <div class="selfclear"><label class="ss-q-title" for="entry_2">Organization /<br />Background /<br />Interest in OpenBlock
</label> 
                 <textarea name="entry.2.single" rows="8" cols="75" class="ss-q-long" id="entry_2"></textarea></div>
                 <input class="submit" type="submit" value="Submit" name="submit">
               </form>
               <?php echo $after_widget; ?>
   
   <?php
   }

   function update($new_instance, $old_instance) {                
       return $new_instance;
   }

   function form($instance) {        
   
       $title = esc_attr($instance['title']);
       ?>
       <p>
	   	   <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','woothemes'); ?></label>
                   <input type="text" name="<?php echo $this->get_field_name('title'); ?>"  value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />

       </p>
      <?php
   }
} 

register_widget('OpenBlockSignup');
