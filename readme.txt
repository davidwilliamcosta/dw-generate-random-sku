=== DW Generate Random SKU ===
Contributors: davidwilliamdacosta
Tags: woocommerce, sku, product, generate, random, automatic
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.4
WC requires at least: 5.0
WC tested up to: 8.5
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Um plugin simples e eficiente para WooCommerce que gera automaticamente SKUs aleatórios e únicos para seus produtos. Ideal para lojas que precisam de um sistema de SKU automatizado e robusto, garantindo que cada novo produto tenha um identificador único sem intervenção manual.

Características:

*   **Geração Automática:** Atribui SKUs automaticamente a novos produtos WooCommerce.
*   **SKUs Únicos:** Garante que cada SKU gerado seja único em sua loja.
*   **Personalizável:** Defina o comprimento do SKU, prefixo e os caracteres permitidos.
*   **Seguro:** Utiliza `random_int()` para geração de SKUs criptograficamente seguros.
*   **Performance Otimizada:** Integra-se com hooks específicos do WooCommerce para evitar sobrecarga.
*   **Página de Configurações:** Interface simples no painel do WordPress para gerenciar as opções do plugin.

== Installation ==

1.  Faça o upload da pasta `dw-generate-random-sku` para o diretório `/wp-content/plugins/`.
2.  Ative o plugin através da tela 'Plugins' no WordPress.
3.  Vá para `WooCommerce > Generate SKU` para configurar as opções do plugin.

== Frequently Asked Questions ==

= O que acontece se eu já tiver SKUs definidos para meus produtos? =

O plugin só gerará um SKU se o campo SKU estiver vazio. Ele não sobrescreverá SKUs existentes.

= Posso personalizar o formato do SKU? =

Sim, você pode definir o comprimento do SKU, adicionar um prefixo e escolher quais caracteres (números, letras maiúsculas, etc.) serão usados na geração, através da página de configurações do plugin.

= O plugin funciona com variações de produtos? =

Este plugin foca na geração de SKU para o produto principal. Para variações, o WooCommerce geralmente tem seu próprio sistema de geração ou você pode precisar de um plugin complementar.

= O que acontece se o WooCommerce não estiver ativo? =

O plugin exibirá um aviso no painel do WordPress e não funcionará até que o WooCommerce seja ativado.

== Screenshots ==

(Nenhum screenshot disponível no momento. Adicione screenshots da página de configurações e de um produto com SKU gerado.)

== Changelog ==

= 1.0.2 - 2025-07-25 =
*   Adicionada a capacidade de personalizar o prefixo do SKU através da página de configurações do plugin.

= 1.0.1 - 2025-07-25 =
*   Adicionada compatibilidade com o Armazenamento de Pedidos de Alto Desempenho (HPOS) do WooCommerce.

= 1.0.0 - 2025-07-25 =
*   Lançamento inicial do plugin.
*   Geração automática de SKU para novos produtos WooCommerce.
*   Página de configurações para personalização de comprimento, prefixo e caracteres.
*   Verificação de unicidade de SKU.
*   Compatibilidade com WooCommerce e WordPress recentes.



