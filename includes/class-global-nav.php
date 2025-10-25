<?php
if (!defined('ABSPATH')) exit;

class Global_Nav_Shortcode {

    public static function render($atts = []) {
        $atts = shortcode_atts([
            'set' => '', // Nieuw attribuut
        ], $atts, 'prijzenlijst_navigatie');

        $config = include plugin_dir_path(__FILE__) . '../airtable-config.php';

        // Kies de juiste tabellen
        if (!empty($atts['set']) && !empty($config['sets'][$atts['set']])) {
            // ✅ Gebruik alleen tabellen uit de gekozen set
            $tables_to_show = $config['sets'][$atts['set']];
        } else {
            // 🔄 Standaard: toon alle tabellen
            $tables_to_show = array_keys($config['tables']);
        }

        ob_start();
        ?>
        <div class="global-nav-body">
            <nav class="global-nav">
                <?php foreach ($tables_to_show as $label): 
                    // Haal de table info op
                    $table = $config['tables'][$label] ?? null;
                    if (!$table) continue;
                ?>
                    <a href="#prijzenlijst-<?php echo esc_attr(sanitize_title($table['table_name'])); ?>">
                        <?php echo esc_html($label); ?>
                    </a>
                <?php endforeach; ?>
            </nav>
        </div>
        <?php
        return ob_get_clean();
    }
}
