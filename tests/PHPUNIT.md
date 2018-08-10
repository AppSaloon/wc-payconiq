# PHPUNIT testen schrijven

## Terminologie in UnitTesting

**Unit Testing** werkt door middel van het maken van stellingen of beter gezegd asserts. Via deze weg kan je controleren of bepaalde stukken code ook effectief teruggeven wat ervan mag worden verwacht. Dit kan men doen aan de hand van een aantal basisstellingen zoals bijvoorbeeld:

- **AssertTrue/AssertFalse** waarbij we controleren of iets waar of vals is.
- **AssertEquals** waarbij we controleren of iets gelijk is aan iets anders.
- **AssertType** waarbij we kijken of iets van een bepaald type is.

Er zijn natuurlijk ook nog andere hulpmiddelen bij het schrijven van een test.

**Fixtures** is de term die gebruikt wordt om de basis van onze test te bepalen. Onze applicatie wordt zo naar een “known state” gebracht voor de tests gaan worden uitgevoerd. Dit zorgt er voor dat je elke keer weer zeker mag zijn dat tests in exact dezelfde omstandigheden worden uitgevoerd. Deze “state” is ook gedeeld binnen de huidige tests en moet dan ook bij aanvang bepaald worden met “setUp” en na de tests terug gedraaid worden met “tearDown”.

**Mock objecten** doen wat hun naam omschrijft, ze doen alsof ze iets zijn. Deze kunnen gebruikt worden wanneer onze code bijvoorbeeld afhankelijk is van externe factoren zoals een API. Mock objecten gaan dan het gedrag van de feitelijke dependency imiteren zodat tests altijd gecontroleerd werken.

## Wat heb je nodig?

Een kort overzicht van wat er nodig is om unit tests aan de praat te krijgen.

* wp-cli om WordPress-specifieke PHPUnit testbestanden aan te maken, en om geautomatiseerd een versie van WordPress te installeren die zal gebruikt worden bij de unit tests
* PHPUnit 5.7.x. De recentste versie is 6.x, maar deze is (nog) niet compatibel met WordPress Unit testing
* Vagrant VM. We gaan ervan uit dat de hele testomgeving opgezet wordt binnen een virtuele machine, en niet op de hostcomputer rechtstreeks.

## Hoe begin je eraan met vagrant?
Zorg ervoor dat je vagrant instance goed draait en dat local.klasse.be normaal werkt. We gaan ervan uit dat alle volgende stappen gebeuren binnen je Vagrant VM (inloggen via `vagrant ssh`).

Meestal lukt de installatie van composer (en dus ook wp-cli) niet wanneer je de eerste keer `vagrant up` doet. Dit heeft te maken met rechten voor composer die (nog) niet goed staan. Voer het command `vagrant reload --provision` uit en vagrant installeert nu alles wel met de juiste rechten.

### PHPUnit
PHPUnit wordt mee geïnstalleerd met Vagrant. PHPUnit wordt geïnstalleerd via composer in de map `/home/vagrant/vendor` (`~/vendor`). Verwijder eventueel phpunit die in je `/usr/local/bin` map zit (=vorige manier van installeren).

Voeg `$HOME/vendor/bin` toe aan `~/.bash_profile`. De regel ziet er dan ongveer zo uit `PATH=$PATH:$HOME/bin:$HOME/vendor/bin`. Hierdoor kan je `phpunit` als commando gebruiken.

### WP-CLI
WP-CLI wordt mee geïnstalleerd met Vagrant. 

### Tests scaffold

Eens de vorige stappen OK zijn, kunnen we de specifieke tests voor onze plugin gaan voorbereiden.

PHPUnit opzetten en runnen verloopt in je VM. Dus eerst loggen we in in je VM.

> `vagrant ssh`

Vervolgens moet je de volgende commando's uitvoeren om de test bestanden in de plugin aan te maken. In dit voorbeeld gaan we tests voorbereiden voor de plugin `klasse-plugin boilerplate`, de naam zal dus anders zijn als het voor een andere plugin is.

> `wp scaffold plugin-tests klasse-plugin-boilerplate`

Vervolgens moet er een versie van WordPress geïnstalleerd worden die louter gebruikt wordt om standaard WordPress functies te kunnen gebruiken in tests. Deze zal geplaatst worden in `/tmp/wordpress`.

Om wordpress te kunnen downloaden, moet je eerst subversion en wget installeren. Deze worden normaal mee geïnstalleerd met Vagrant. Indien ze nog niet aanwezig zijn kan je ze installeren als volgt:

> `sudo apt-get install subversion`
 
> `sudo apt-get install wget`

Ga naar de plugin waar je de testen wil schrijven of uitvoeren.

> `cd /var/www/klasse/httpdocs/wp/wp-content/plugins/klasse-plugin-boilerplate`

Vervolgens moet je het volgende commando uitvoeren. Hiermee wordt het script uitgevoerd dat in de `bin` directory staat. Dit script installeert WordPress, de database en worden er eventuele andere commando's uitgevoerd die de tests nodig hebben. De parameters die je meegeeft aan het script zijn `install-wp-tests.sh database_name database_user database_pass host wp_version`.

> `bash bin/install-wp-tests.sh tests root 123 localhost latest`

Omdat we met namespaces werken moet je de autolader toevoegen in de test bootstrap.
Wijzig in `tests/bootstrap.php`, in je plugin directory, `function _manually_load_plugin()` zodat het eruit ziet al in volgend voorbeeld.

Voeg ook de constante `define( 'PHPUNIT_KLASSE_TESTSUITE', true );` toe zodat de omgeving op `local` wordt gezet.
```
// unittest heeft geen omgeving - hiermee zetten we de omgeving op local
define( 'PHPUNIT_KLASSE_TESTSUITE', true );

/**
 * Manually load the plugin being tested.
 */
function _manually_load_plugin() {
	// klasse autoloader
	require dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/mu-plugins/klasse-core/klasse-core.php';
	// plugin
	require dirname( dirname( __FILE__ ) ) . '/klasse-plugin-boilerplate.php';
}
```
En als laatst kan je dan de tests starten door het commando `phpunit` uit te voeren in de directory van je plugin.

### Testsen uitvoeren met start-test.sh

In de map `klasse-plugin-boilerplate/bin` is er een script (`start-test.sh`) dat je kan gebruiken om testen uit te voeren met één commando. Kopieer het script naar de map `bin` in je plugin. Je kan dan testen met volgende commando (in dit geval klasse-plugin-boilerplate):

> `bash /var/www/klasse/httpdocs/wp/wp-content/plugins/klasse-plugin-boilerplate/bin/start-test.sh`

Dit script kan je aanroepen eender waar  je bent in de VM.

Zie [ README ](README.md) voor meer info.

### Datasets maken

Onze datasets zijn sql dumps. Daarvoor kan je de export-functie in Sequel Pro of phpMyAdmin gebruiken. Het resultaat van zo een export ziet er ongeveer zo uit:

```
DROP TABLE IF EXISTS `wptests_options`;
CREATE TABLE IF NOT EXISTS `wptests_options` (
  `option_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `option_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `option_value` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `autoload` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `wptests_options` (`option_id`, `option_name`, `option_value`, `autoload`) VALUES
(2, '_site_transient_timeout_theme_roots', '1504000454', 'no'),
(3, '_site_transient_theme_roots', 'a:15:{s:12:\"broken-theme\";s:51:\"/tmp/wordpress-tests-lib/includes/../data/themedir1\";s:9:\"camelCase\";s:51:\"/tmp/wordpress-tests-lib/includes/../data/themedir1\";s:7:\"default\";s:51:\"/tmp/wordpress-tests-lib/includes/../data/themedir1\";s:23:\"internationalized-theme\";s:51:\"/tmp/wordpress-tests-lib/includes/../data/themedir1\";s:20:\"page-templates-child\";s:51:\"/tmp/wordpress-tests-lib/includes/../data/themedir1\";s:14:\"page-templates\";s:51:\"/tmp/wordpress-tests-lib/includes/../data/themedir1\";s:7:\"sandbox\";s:51:\"/tmp/wordpress-tests-lib/includes/../data/themedir1\";s:14:\"stylesheetonly\";s:51:\"/tmp/wordpress-tests-lib/includes/../data/themedir1\";s:24:\"subdir/theme with spaces\";s:51:\"/tmp/wordpress-tests-lib/includes/../data/themedir1\";s:13:\"subdir/theme2\";s:51:\"/tmp/wordpress-tests-lib/includes/../data/themedir1\";s:11:\"theme1-dupe\";s:51:\"/tmp/wordpress-tests-lib/includes/../data/themedir1\";s:6:\"theme1\";s:51:\"/tmp/wordpress-tests-lib/includes/../data/themedir1\";s:13:\"twentyfifteen\";s:7:\"/themes\";s:15:\"twentyseventeen\";s:7:\"/themes\";s:13:\"twentysixteen\";s:7:\"/themes\";}', 'no'),
(4, 'siteurl', 'http://example.org', 'yes'),
(5, 'home', 'http://example.org', 'yes');
```

Alle fixtures worden samengezet `tests/fixtures/tables.sql`.

In het bestand `klasse-plugin-boilerplate/tests/fixtures/tables.sql` zitten alle fixtures die aangemaakt worden door wordpress-test-lib. De belangrijkste is wptests_options. Die heb je meestal nodig in je eigen testen, aangezien fixtures na elke test verwijderd/vernieuwd worden.

## Codecoverage

Codecoverage is een extensie van PHPUnit die gebruikt maakt van de PHPUnit-logs om te bepalen welke regels code getest worden. Hiervan maakt Codecoverage een rapport en worden de resultaten inzichtelijk weergegeven. Developers kunnen vlot zien welke functies niet (volledig) getest zijn.
Meer gegevens over codecoverage: https://phpunit.de/manual/current/en/code-coverage-analysis.html

### Configuratie

Onderstaande kan je in `phpunit.xml.dist` configureren.

<bilter> bepaalt welke bestanden door codecoverage moeten bekeken worden.
````
<filter>
    <whitelist processUncoveredFilesFromWhitelist="true">
        <directory suffix=".php">./model</directory>
        <file>example-model</file>
        <exclude>
              <directory suffix=".php">/path/to/files</directory>
              <file>/path/to/file</file>
        </exclude>
    </whitelist>
</filter>

````

<logging> bepaalt welke output codecoverage genereert.

````
<logging>
		<log type="coverage-html" target="./tmp/report" lowUpperBound="35" highLowerBound="70" />
		<log type="coverage-text" target="php://stdout" showUncoveredFiles="false"/>
</logging>
````

Meer gegevens over logging: https://phpunit.de/manual/current/en/appendixes.configuration.html

### Code uitsluiten van codecoverage
Sommige functies hebben omwille van goede redenen geen unit tests, en moeten dus uitgesloten worden van codecoverage. Dit kan door een tag mee te geven in een docblock.

Een hele class uitsluiten:
````
/**
 * @codeCoverageIgnore
 */
class Foo {
    public function bar() {
    }
}
````

Een bepaalde functie uitsluiten:
````
class Foo {
    /**
     * @codeCoverageIgnore
     */
    public function bar() {
    }
}
````

Code uitsluiten:
````
class Foo {
    public function bar() {
        // @codeCoverageIgnoreStart
        print '*';
        // @codeCoverageIgnoreEnd

        exit; // @codeCoverageIgnore
    }
}
````

### Indirect geteste methods
Soms wordt een method indirect getest via een andere testcase. In dit geval kunnen we via een tag in een docblock aangeven welke method er (ook) getest wordt door de testcase.

````
/**
 * @covers BankAccount::getBalance
 */
public function testBalanceIsInitiallyZero()
{
    $this->assertEquals(0, $this->ba->getBalance());
}
````
 
 

