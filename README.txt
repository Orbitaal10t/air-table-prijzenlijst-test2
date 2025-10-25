Hoe te gebruiken:

===== Setup API Key =====
1. Clone deze repository
2. Kopieer `airtable-config.example.php` naar `airtable-config.php`
3. Voeg je Airtable API key toe in `airtable-config.php`

===== PAGINA OPBOUW =====
	[prijzenlijst_navigatie]
    [airtable_prijzenlijst table="SHORCODE_NAME"]
    [airtable_prijzenlijst table="SHORCODE_NAME"]
    [airtable_prijzenlijst table="SHORCODE_NAME"]
    [airtable_prijzenlijst table="SHORCODE_NAME"]
    enz...


===== TE GEBRUIKEN SHORTCODES =====
shortcodes gemaakt door de plugin zien er zo uit:

			[airtable_prijzenlijst table="SHORCODE_NAME"]
	zoals 	[airtable_prijzenlijst table="Wild"]  voor de tabel Wild


===== TABELLEN TOEVOEGEN =====
in /airtable-config.php worden de tabellen toegevoegd in dit format

        'SHORTCODE_NAME' => [						
            'base_id' => 'YOUR_BASE_ID',			<- begint met app (appXXX...)
            'table_name' => 'YOUR_TABLE_NAME',		<- de naam van de tabel in de data sheet
        ],


===== BESTANDEN STRUCTUUR =====
de bestandenstructuur van de plugin ziet er zo uit:

/wp-content/plugins/airtable-prijzenlijst/
│
├── airtable-prijzenlijst.php         
│
├── includes/
│   ├── airtable-prijzenlijst-shortcode.php
│   ├── global-nav-shortcode.php
│   └── helpers.php
│
├── assets/
│   ├── css/
│   │   ├── prijzenlijst.css
│   │   └── global-nav.css
│   └── js/
│       ├── prijzenlijst.js
│       └── global-nav.js
│
└── airtable-config.php
