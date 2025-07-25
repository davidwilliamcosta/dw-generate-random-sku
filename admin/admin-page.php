<?php
/**
 * Página de administração do plugin DW Generate Random SKU.
 *
 * @package DW_Generate_Random_SKU
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$plugin_settings = DW_Generate_Random_SKU::get_instance()->get_setting( 'all' );

?>

<div class="wrap">
    <h1><?php esc_html_e( 'Configurações do DW Generate Random SKU', 'dw-generate-random-sku' ); ?></h1>

    <form method="post" action="">
        <?php wp_nonce_field( 'dw_generate_sku_settings' ); ?>

        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><label for="auto_generate"><?php esc_html_e( 'Ativar Geração Automática', 'dw-generate-random-sku' ); ?></label></th>
                    <td>
                        <select name="auto_generate" id="auto_generate">
                            <option value="yes" <?php selected( $plugin_settings['auto_generate'], 'yes' ); ?>><?php esc_html_e( 'Sim', 'dw-generate-random-sku' ); ?></option>
                            <option value="no" <?php selected( $plugin_settings['auto_generate'], 'no' ); ?>><?php esc_html_e( 'Não', 'dw-generate-random-sku' ); ?></option>
                        </select>
                        <p class="description"><?php esc_html_e( 'Ativa ou desativa a geração automática de SKUs para novos produtos.', 'dw-generate-random-sku' ); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="sku_length"><?php esc_html_e( 'Comprimento do SKU', 'dw-generate-random-sku' ); ?></label></th>
                    <td>
                        <input type="number" name="sku_length" id="sku_length" value="<?php echo esc_attr( $plugin_settings['sku_length'] ); ?>" min="4" max="32" class="small-text" />
                        <p class="description"><?php esc_html_e( 'Define o número de caracteres para o SKU gerado (excluindo o prefixo).', 'dw-generate-random-sku' ); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="sku_prefix"><?php esc_html_e( 'Prefixo do SKU', 'dw-generate-random-sku' ); ?></label></th>
                    <td>
                        <input type="text" name="sku_prefix" id="sku_prefix" value="<?php echo esc_attr( $plugin_settings['sku_prefix'] ); ?>" class="regular-text" />
                        <p class="description"><?php esc_html_e( 'Um prefixo opcional para ser adicionado antes do SKU gerado (ex: PROD-).', 'dw-generate-random-sku' ); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="characters"><?php esc_html_e( 'Caracteres Permitidos', 'dw-generate-random-sku' ); ?></label></th>
                    <td>
                        <input type="text" name="characters" id="characters" value="<?php echo esc_attr( $plugin_settings['characters'] ); ?>" class="large-text" />
                        <p class="description"><?php esc_html_e( 'Os caracteres que podem ser usados na geração do SKU.', 'dw-generate-random-sku' ); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="max_attempts"><?php esc_html_e( 'Máximo de Tentativas', 'dw-generate-random-sku' ); ?></label></th>
                    <td>
                        <input type="number" name="max_attempts" id="max_attempts" value="<?php echo esc_attr( $plugin_settings['max_attempts'] ); ?>" min="1" max="100" class="small-text" />
                        <p class="description"><?php esc_html_e( 'Número máximo de tentativas para gerar um SKU único antes de desistir.', 'dw-generate-random-sku' ); ?></p>
                    </td>
                </tr>
            </tbody>
        </table>

        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_attr_e( 'Salvar Alterações', 'dw-generate-random-sku' ); ?>" />
        </p>
    </form>
</div>


