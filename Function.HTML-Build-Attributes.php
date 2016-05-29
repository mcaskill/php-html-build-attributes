<?php

if ( ! function_exists('html_build_attributes') ) :

	/**
	 * Generate a string of HTML attributes
	 *
	 * @param   array     $attr      Associative array of attribute names and values.
	 * @param   callable  $callback  Callback function to escape values for HTML attributes.
	 *                               Defaults to the WordPress' 'esc_attr' function.
	 * @return  string  Returns a string of HTML attributes.
	 */
	function html_build_attributes( $attr = [], $callback = 'esc_attr' )
	{
	    $html = '';
	
	    if ( count($attr) ) {
	        $html = array_map(
	            function ( $val, $key ) {
	                if ( is_bool( $val ) ) {
	                    return ( $val ? $key : '' );
	                } elseif ( isset( $val ) ) {
	                    if ( is_array( $val ) ) {
	                        $val = implode( ' ', $val );
	                    }
	
	                    if ( is_callable( $callback ) ) {
	                    	$val = call_user_func( $callback, $val );
	                    }
	
	                    return sprintf( '%1$s="%2$s"', $key, $val );
	                }
	            },
	            $attr,
	            array_keys( $attr )
	        );
	
	        $html = implode( ' ', $html );
	    }
	
	    return $html;
	}

endif;
