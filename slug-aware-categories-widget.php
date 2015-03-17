<?php
/*
Plugin Name: Slug Aware Categories Widget
Description: Categories Widget that outputs category slug as css class in a span tag before each category link. Feel free to copy and modify to suit your needs.
Version: 1.0
Author: Pippin Williamson, Henning Klasén
Author URI: http://pippinsplugins.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

/**
 * Slug Aware Categories Widget Class
 */
class slug_aware_categories_widget extends WP_Widget {

  /** constructor -- name this the same as the class above */
  function slug_aware_categories_widget() {
      parent::WP_Widget(false, $name = 'Slug Aware Categories');
  }

 /** @see WP_Widget::widget -- do not rename this */
 function widget($args, $instance) {
  extract( $args );
  $title      = apply_filters('widget_title', $instance['title']); // the widget title
  $number     = $instance['number']; // the number of categories to show
  $taxonomy   = 'category'; // the taxonomy to display

  $args = array(
   'number'   => $number,
   'taxonomy' => $taxonomy
  );

  // retrieves an array of categories or taxonomy terms
  $cats = get_categories($args);
  ?>
     <?php echo $before_widget; ?>
      <?php if ( $title ) { echo $before_title . $title . $after_title; } ?>
      <ul>
       <?php foreach($cats as $cat) { ?>
        <li>
          <a href="<?php echo get_term_link($cat->slug, $taxonomy); ?>" title="<?php sprintf( __( "View all posts in %s" ), $cat->name ); ?>">
            <span class="icon <?php echo $this->category_slug_class( $cat, ' ' ); ?>"></span>
            <?php echo $cat->name; ?>
          </a>
        </li>
       <?php } ?>
      </ul>
     <?php echo $after_widget; ?>
  <?php
 }

 /** @see WP_Widget::update -- do not rename this */
 function update($new_instance, $old_instance) {
  $instance = $old_instance;
  $instance['title'] = strip_tags($new_instance['title']);
  $instance['number'] = strip_tags($new_instance['number']);
  $instance['taxonomy'] = 'category';
  return $instance;
 }

  /** @see WP_Widget::form -- do not rename this */
  function form($instance) {
    $title   = esc_attr($instance['title']);
    $number  = esc_attr($instance['number']);
    $exclude = esc_attr($instance['exclude']);

    ?>
    <p>
      <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of categories to display'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" />
    </p>
    <?php
  }

  function category_slug_class( $category, $separator = ' ' ) {
    $slug_class = $separator . 'category-' . $category->slug;
    return $slug_class;
  }
} // end class slug_aware_categories_widget

add_action('widgets_init', create_function('', 'return register_widget("slug_aware_categories_widget");'));
?>
