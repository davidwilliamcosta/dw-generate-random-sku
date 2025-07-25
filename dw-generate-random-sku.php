<?php
/**
 * Plugin Name: DW Generate Random SKU
 * Plugin URI: https://github.com/agenciadw/dw-generate-random-sku
 * Description: Gera automaticamente SKUs aleatórios e únicos para produtos WooCommerce quando não são fornecidos manualmente.
 * Version: 1.0.2
 * Author: David William da Costa
 * Author URI: https://github.com/agenciadw
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: dw-generate-random-sku
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * WC requires at least: 5.0
 * WC tested up to: 8.5
 *
 * @package DW_Generate_Random_SKU
 */

// Impede acesso direto ao arquivo
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define constantes do plugin
define( 'DW_GENERATE_SKU_VERSION', '1.0.2' );
define( 'DW_GENERATE_SKU_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'DW_GENERATE_SKU_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'DW_GENERATE_SKU_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Classe principal do plugin
 */
class DW_Generate_Random_SKU {

    /**
     * Instância única da classe
     *
     * @var DW_Generate_Random_SKU
     */
    private static $instance = null;

    /**
     * Configurações do plugin
     *
     * @var array
     */
    private $settings = array();

    /**
     * Obtém a instância única da classe
     *
     * @return DW_Generate_Random_SKU
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Construtor privado para implementar singleton
     */
    private function __construct() {
        add_action( 'plugins_loaded', array( $this, 'init' ) );
        register_activation_hook( __FILE__, array( $this, 'activate' ) );
        register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
    }

    /**
     * Inicializa o plugin
     */
    public function init() {
        // Verifica se o WooCommerce está ativo
        if ( ! $this->is_woocommerce_active() ) {
            add_action( 'admin_notices', array( $this, 'woocommerce_missing_notice' ) );
            return;
        }

        // Carrega as configurações
        $this->load_settings();

        // Adiciona os hooks principais
        $this->add_hooks();

        // Declara compatibilidade com HPOS
        add_action( 'before_woocommerce_init', array( $this, 'declare_hpos_compatibility' ) );

        // Carrega arquivos de tradução
        add_action( 'init', array( $this, 'load_textdomain' ) );

        // Adiciona página de configurações no admin
        if ( is_admin() ) {
            add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
            add_action( 'admin_init', array( $this, 'register_settings' ) );
        }
    }

    /**
     * Verifica se o WooCommerce está ativo
     *
     * @return bool
     */
    private function is_woocommerce_active() {
        return class_exists( 'WooCommerce' );
    }

    /**
     * Exibe aviso quando WooCommerce não está ativo
     */
    public function woocommerce_missing_notice() {
        ?>
        <div class="notice notice-error">
            <p><?php esc_html_e( 'DW Generate Random SKU requer o WooCommerce para funcionar. Por favor, instale e ative o WooCommerce.', 'dw-generate-random-sku' ); ?></p>
        </div>
        <?php
    }

    /**
     * Carrega as configurações do plugin
     */
    private function load_settings() {
        $default_settings = array(
            'sku_length' => 8,
            'sku_prefix' => 'PROD-',
            'characters' => '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'max_attempts' => 10,
            'auto_generate' => 'yes'
        );

        $saved_settings = get_option( 'dw_generate_sku_settings', array() );
        $this->settings = wp_parse_args( $saved_settings, $default_settings );
    }

    /**
     * Adiciona os hooks do WordPress
     */
    private function add_hooks() {
        // Hook para gerar SKU em novos produtos
        add_action( 'woocommerce_new_product', array( $this, 'set_sku_on_new_product' ), 10, 1 );
        
        // Hook alternativo para produtos salvos sem SKU
        add_action( 'save_post', array( $this, 'set_sku_on_save_product' ), 10, 3 );
    }

    /**
     * Declara compatibilidade com HPOS (High-Performance Order Storage).
     */
    public function declare_hpos_compatibility() {
        if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
        }
    }

    /**
     * Carrega arquivos de tradução
     */
    public function load_textdomain() {
        load_plugin_textdomain( 'dw-generate-random-sku', false, dirname( DW_GENERATE_SKU_PLUGIN_BASENAME ) . '/languages' );
    }

    /**
     * Ações na ativação do plugin
     */
    public function activate() {
        // Salva configurações padrão se não existirem
        if ( ! get_option( 'dw_generate_sku_settings' ) ) {
            update_option( 'dw_generate_sku_settings', $this->settings );
        }
    }

    /**
     * Ações na desativação do plugin
     */
    public function deactivate() {
        // Limpa cache se necessário
        wp_cache_flush();
    }

    /**
     * Gera um SKU aleatório seguro
     *
     * @param int    $length Comprimento do SKU (sem prefixo)
     * @param string $prefix Prefixo do SKU
     * @return string SKU gerado
     */
    public function generate_random_sku( $length = null, $prefix = null ) {
        $length = $length ?: $this->settings['sku_length'];
        $prefix = $prefix ?: $this->settings['sku_prefix'];
        $characters = $this->settings['characters'];
        $characters_length = strlen( $characters );
        $random_string = '';

        // Gera a string aleatória de forma segura
        for ( $i = 0; $i < $length; $i++ ) {
            try {
                // random_int é criptograficamente seguro
                $random_string .= $characters[ random_int( 0, $characters_length - 1 ) ];
            } catch ( Exception $e ) {
                // Fallback para mt_rand em caso de erro
                $random_string .= $characters[ mt_rand( 0, $characters_length - 1 ) ];
            }
        }

        return $prefix . $random_string;
    }

    /**
     * Define SKU para novos produtos WooCommerce
     *
     * @param int $product_id ID do produto
     */
    public function set_sku_on_new_product( $product_id ) {
        if ( 'yes' !== $this->settings['auto_generate'] ) {
            return;
        }

        $this->generate_and_set_sku( $product_id );
    }

    /**
     * Define SKU quando produto é salvo (fallback)
     *
     * @param int     $post_id ID do post
     * @param WP_Post $post    Objeto do post
     * @param bool    $update  Se é uma atualização
     */
    public function set_sku_on_save_product( $post_id, $post, $update ) {
        // Verifica se é um produto
        if ( 'product' !== $post->post_type ) {
            return;
        }

        // Verifica se a geração automática está ativa
        if ( 'yes' !== $this->settings['auto_generate'] ) {
            return;
        }

        $this->generate_and_set_sku( $post_id );
    }

    /**
     * Gera e define um SKU único para o produto
     *
     * @param int $product_id ID do produto
     */
    private function generate_and_set_sku( $product_id ) {
        // Verifica se o produto já tem SKU
        $existing_sku = get_post_meta( $product_id, '_sku', true );

        if ( ! empty( $existing_sku ) ) {
            return;
        }

        $max_attempts = $this->settings['max_attempts'];
        $attempt = 0;

        do {
            $new_sku = $this->generate_random_sku();
            $exists = wc_get_product_id_by_sku( $new_sku );
            $attempt++;

        } while ( $exists && $attempt < $max_attempts );

        // Se encontrou um SKU único, salva no produto
        if ( ! $exists ) {
            update_post_meta( $product_id, '_sku', $new_sku );
            
            // Log para debug (opcional)
            if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                error_log( sprintf( 'DW Generate SKU: SKU "%s" gerado para produto ID %d', $new_sku, $product_id ) );
            }
        }
    }

    /**
     * Adiciona menu no admin do WordPress
     */
    public function add_admin_menu() {
        add_submenu_page(
            'woocommerce',
            __( 'DW Generate SKU', 'dw-generate-random-sku' ),
            __( 'Generate SKU', 'dw-generate-random-sku' ),
            'manage_woocommerce',
            'dw-generate-sku',
            array( $this, 'admin_page' )
        );
    }

    /**
     * Registra configurações do plugin
     */
    public function register_settings() {
        register_setting( 'dw_generate_sku_settings', 'dw_generate_sku_settings' );
    }

    /**
     * Página de administração do plugin
     */
    public function admin_page() {
        if ( isset( $_POST['submit'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'dw_generate_sku_settings' ) ) {
            $settings = array(
                'sku_length' => absint( $_POST['sku_length'] ),
                'sku_prefix' => sanitize_text_field( $_POST['sku_prefix'] ),
                'characters' => sanitize_text_field( $_POST['characters'] ),
                'max_attempts' => absint( $_POST['max_attempts'] ),
                'auto_generate' => sanitize_text_field( $_POST['auto_generate'] )
            );

            update_option( 'dw_generate_sku_settings', $settings );
            $this->settings = $settings;

            echo '<div class="notice notice-success"><p>' . esc_html__( 'Configurações salvas com sucesso!', 'dw-generate-random-sku' ) . '</p></div>';
        }

        include DW_GENERATE_SKU_PLUGIN_PATH . 'admin/admin-page.php';
    }

    /**
     * Obtém uma configuração específica
     *
     * @param string $key Chave da configuração
     * @return mixed Valor da configuração
     */
    public function get_setting( $key ) {
        if ( 'all' === $key ) {
            return $this->settings;
        }
        return isset( $this->settings[ $key ] ) ? $this->settings[ $key ] : null;
    }
}

// Inicializa o plugin
DW_Generate_Random_SKU::get_instance();

