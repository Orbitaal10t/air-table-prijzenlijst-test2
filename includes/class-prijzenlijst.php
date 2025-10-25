<?php
if (!defined('ABSPATH')) exit;

class Prijzenlijst_Shortcode {

    public static function render($atts) {
        $atts = shortcode_atts([
            'table' => '',
            'set'   => '',
        ], $atts, 'airtable_prijzenlijst');

        $config = include plugin_dir_path(__FILE__) . '../airtable-config.php';

        if (empty($atts['table']) || empty($config['tables'][$atts['table']])) {
            return '<p>Geen tabel geselecteerd of ongeldig.</p>';
        }
        // Als er een set is opgegeven, render alle tabellen in die set
        if (!empty($atts['set'])) {
            $set_name = $atts['set'];
            if (empty($config['sets'][$set_name])) {
                return '<p>Ongeldige set: ' . esc_html($set_name) . '</p>';
            }

            $output = '';
            foreach ($config['sets'][$set_name] as $table_key) {
                // Recurse: roep render opnieuw aan voor elke tabel
                $output .= self::render(['table' => $table_key]);
            }

            return '<div class="prijzenlijst-set prijzenlijst-set-' . esc_attr($set_name) . '">' . $output . '</div>';
        }
        $table_config = $config['tables'][$atts['table']];
        $api_key = $config['api_key'];
        $base_id = $table_config['base_id'];
        $table_name = $table_config['table_name'];

        $url = "https://api.airtable.com/v0/{$base_id}/" . rawurlencode($table_name) .
               "?sort[0][field]=" . rawurlencode('Volgorde') . "&sort[0][direction]=asc";
        $response = wp_remote_get($url, [
            'headers' => ['Authorization' => 'Bearer ' . $api_key],
            'timeout' => 15
        ]);

        if (is_wp_error($response)) return '<p>Fout bij ophalen van data.</p>';

        $data = json_decode(wp_remote_retrieve_body($response), true);
        if (empty($data['records'])) return '<p>Geen data gevonden.</p>';

        // --- BELANGRIJK: volgorde uit Airtable behouden ---
        $groepen = [];
        $volgorde_dieren = []; // Houd bij in welke volgorde dieren voorkomen

        foreach ($data['records'] as $record) {
            $fields = $record['fields'] ?? [];
            $dier = airtable_field_to_text($fields['Groep'] ?? '');

            if (!isset($groepen[$dier])) {
                $groepen[$dier] = [];
                $volgorde_dieren[] = $dier; // Bewaar de volgorde waarin dieren voor het eerst voorkomen
            }

            $groepen[$dier][] = $fields; // Voeg record toe in originele volgorde
        }

        // Bouw een nieuwe array met dezelfde volgorde als Airtable
        $groepen_op_volgorde = [];
        foreach ($volgorde_dieren as $dier) {
            $groepen_op_volgorde[$dier] = $groepen[$dier];
        }

        $shortcode_id = 'prijzenlijst-' . sanitize_title($table_name);

        ob_start();
        ?>
        <section id="<?php echo esc_attr($shortcode_id); ?>" class="prijzenlijst">
            <header>
                <h1 class="table-name"><?php echo esc_html($table_name); ?></h1>
            </header>

          <nav class="prijzenlijst-nav">
            <?php foreach ($groepen_op_volgorde as $dier => $records): 
      // Alleen een nav-link maken als $dier niet leeg is
      if (trim($dier) === '') continue;

      $slug = $shortcode_id . '-' . sanitize_title($dier); ?>
            <a href="#<?php echo esc_attr($slug); ?>"><?php echo esc_html($dier); ?></a>
            <?php endforeach; ?>
          </nav>


            <?php foreach ($groepen_op_volgorde as $dier => $records):
                $slug = $shortcode_id . '-' . sanitize_title($dier); ?>
                <section id="<?php echo esc_attr($slug); ?>" class="dieren-groep">
                    <h2 class="diersoort"><?php echo esc_html($dier); ?></h2>
                    <ul class="prijzenlijst-items">
                        <?php foreach ($records as $fields):

                            $naam = $fields['Aanbod Naam'] ?? '';
                            $prijs_raw = trim($fields['Prijs'] ?? '');
                            $prijs_categorie = $fields['Prijs categorie'] ?? '';
                            $gewicht = $fields['Gewicht'] ?? '';

                            $beschikbaar = !empty($fields['Beschikbaar?']) && $fields['Beschikbaar?'] === true;
                            if (!$beschikbaar) continue;



                            if ($prijs_raw !== '') {
                                // Verwijder €-teken en overbodige spaties
                                $cleaned = trim(str_replace(['€', 'EUR'], '', $prijs_raw));

                                // Kijk of het veld meerdere prijzen bevat, bijv. "5,80 / 8,50 / 9,50"
                                if (preg_match_all('/[\d]+[.,]?\d*/', $cleaned, $matches)) {
                                    $prijzen = array_map(function($p) {
                                        // Vervang komma door punt voor correcte float-conversie
                                        $p = str_replace(',', '.', trim($p));
                                        // floatval pakt het juiste getal, number_format formatteert met 2 decimalen
                                        return number_format(floatval($p), 2, ',', '');
                                    }, $matches[0]);

                                    $prijs = '€ ' . implode(' / ', $prijzen);
                                } else {
                                    $prijs = esc_html($prijs_raw);
                                }
                            } else {
                                $prijs = '';
                            }

                            // Voeg altijd de prijs categorie toe, ook als er geen prijs is
                            if ($prijs_categorie !== '') {
                                $prijs .= ($prijs !== '' ? ' ' : '') . esc_html($prijs_categorie);
                            }



                            $beschrijving = airtable_field_to_text($fields['Aanbod beschrijving'] ?? '');
                        ?>
                        <li class="prijzenlijst-item">
                            <div class="item-header">
                                <span class="item-naam"><?php echo esc_html($naam); ?></span>
                                <span class="item-gewicht"><?php echo esc_html($gewicht); ?></span>
                                <span class="prijzenlijst-separator"></span>
                                <span class="item-prijs"><?php echo esc_html($prijs); ?></span>
                            </div>
                            <?php if ($beschrijving !== ''): ?>
                                <pre class="item-beschrijving"><?php echo esc_html($beschrijving); ?></pre>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </section>
            <?php endforeach; ?>
        </section>
        <?php
        return ob_get_clean();
    }
}
