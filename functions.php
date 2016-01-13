<?php
function divi_child_theme_setup() {
   if ( class_exists('ET_Builder_Module')) {
      get_template_part( 'custom-modules/cfpm' );

      $cfpm = new FDL_ET_Builder_Module_Fullwidth_Portfolio();

      remove_shortcode( 'et_pb_fullwidth_portfolio' );

      add_shortcode( 'et_pb_fullwidth_portfolio', array($cfpm, '_shortcode_callback') );
   }
}
add_action('wp', 'divi_child_theme_setup', 9999);
