<?php

/**
 * Get metadata
 *
 * @param $post_id
 * @param $key
 * @param string $default
 *
 * @return mixed|string
 * @since 1.0.0
 */
function aiovp_get_meta( $post_id, $key, $default = '' ) {
	$meta = get_post_meta( $post_id, $key, true );

	return ! empty( $meta ) ? $meta : $default;
}
