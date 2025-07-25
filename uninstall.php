<?php
/**
 * Arquivo executado quando o plugin é desinstalado.
 *
 * @package DW_Generate_Random_SKU
 */

// Se a desinstalação não foi chamada do WordPress, sair
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Remove as configurações do plugin
delete_option( 'dw_generate_sku_settings' );

// Remove dados de cache relacionados
wp_cache_flush();

