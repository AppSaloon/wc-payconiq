TESTS folder

Lees ook:

- [ phpunit ](PHPUNIT.md)
- [ acceptance ](ACCEPTANCE.md)

## Hoe begin je eraan met vagrant?

Alle tools die we nodig hebbben worden geïnstalleerd via vagrant.

Zie installatie vagrant: [Puphpet repository](https://bitbucket.org/klasse/puphpet)

Testen uitvoeren kan per plugin of via een algemene test waarbij alle testen worden uitgevoerd.

### Alle testen uitvoeren

Met start-test.sh:

> `vagrant ssh`
 
> `bash /var/www/klasse/bin/start-test.sh`

Met phpunit zonder selenium server:

> `vagrant ssh`
 
> `cd /var/www/klasse/`

> `bash bin/install-wp-tests.sh tests root 123 localhost latest` (enkel nodig indien nog niet geïnstalleerd)

> `phpunit`

Met phpunit en selenium server:

> `vagrant ssh`

> `xvfb-run --server-args='-screen 0, 1920x1200x24' java -jar /usr/local/bin/selenium-server-standalone-3.4.0.jar`

> `command-t` (open nieuw tabblad)

> `vagrant ssh`
 
> `cd /var/www/klasse/`

> `bash bin/install-wp-tests.sh tests root 123 localhost latest` (enkel nodig indien nog niet geïnstalleerd)

> `phpunit`

> Na de testen kan je selenium server terug sluiten met `ctrl-t` (in het tabblad waar selenium draait).

### Testen per plugin uitvoeren

> `vagrant ssh`

> `cd /var/www/klasse/httpdocs/wp/wp-content/plugins/klasse-plugin-boilerplate`
 
> `bash bin/start-test.sh`

of 

> `vagrant ssh`

> `bash /var/www/klasse/httpdocs/wp/wp-content/plugins/klasse-plugin-boilerplate/bin/start-test.sh`

of 

> `vagrant ssh`

> `bash /var/www/klasse/bin/start-test.sh --testsuite klasse-profielen`

	Zie ook 'Alle testen uitvoeren' hoe je via phpunit kan testen.

### Toevoegen testen aan algemene test

Plugin met testen (bv. klasse-profielen) toevoegen aan `/var/www/klasse/tests/bootstrap.php`.
```
/**
 * Manually load the plugin being tested.
 */
function _manually_load_plugin() {
	// klasse autoloader
	// LADEN VAN KLASSE AUTOLOADER
    include_once '/var/www/klasse/httpdocs/wp/wp-content/mu-plugins/klasse-core/klasse-core.php';
    // alle plugins met testen
    require '/var/www/klasse/httpdocs/wp/wp-content/plugins/klasse-abonnementen/klasse-abonnementen.php';
	require '/var/www/klasse/httpdocs/wp/wp-content/plugins/klasse-profielen/klasse-profielen.php';
}
```

Testen toevoegen aan `/var/www/klasse/phpunit.xml.dist`.
```
<testsuites>
	<testsuite name="klasse-abonnementen">
		<directory prefix="test" suffix=".php">httpdocs/wp/wp-content/plugins/klasse-abonnementen/tests/</directory>
	</testsuite>
	<testsuite name="klasse-profielen">
		<directory prefix="test" suffix=".php">httpdocs/wp/wp-content/plugins/klasse-profielen/tests/</directory>
	</testsuite>
</testsuites>
```

### Wat doet bin/start-test.sh

- Kijkt of `/tmp/wordpress/` bestaat, indien niet dan wordt `bin/install-wp-tests.sh tests root 123 localhost latest` uitgevoerd.
- Stopt bestaande processen selenium server: java en Xvfb.
- Start de selenium server.
- Start PHPUnit.
- Stopt bestaande processen selenium server: java en Xvfb.

### Beperkingen van bin/start-test.sh

Momenteel kan je enkel volgende parameters meegeven:

	--debug             Display debugging information during test execution.
    -e|--exclude-group  Exclude tests from the specified group(s).
    -f|--filter         Filter which tests to run.
    --force-wp-install  Force temporary WP install.
    -g|--group          Only runs tests from the specified group(s).
                        For groups use 'acceptance,login' (comma and no space).
    -h|--help           Display this help and exit.
    --list-groups       List available test groups.
    --list-suites       List available test suites.
    --no-coverage       Ignore code coverage configuration.
    -s|--selenium       Start selenium server: 'on' (default) or 'off'.
    -t|--testsuite      Filter which testsuite to run.
    -v|--verbose        Output more verbose information.

Enkel `-e -f -g -t -v --list-groups --list-suites --debug --no-coverage' worden aan phpunit meegeven, terwijl er veel meer [parameters](https://phpunit.de/manual/5.7/en/textui.html#textui.clioptions) zijn.

### Start-test.sh aanpassen

Aanpassingen aan start-test.sh doen we in `klasse-plugin-boilerplate`. Er is een script `bin\copy-start-test.sh` dat je kan gebruiken om aanpassingen te kopiëren naar alle plugins die testen hebben. Plugins moeten toegevoegd worden aan het script. 

Plugins toevoegen aan `bin\copy-start-test.sh`.
```
# voeg de namen van de plugins tot die testen hebben
plugins=(
    'klasse-abonnementen'
    'klasse-end-date'
)
```

### Screenshots

Je kan screenshots nemen met selenium. Deze worden geplaatst in de map `tmp/screenshots`. Als je een plugin test dan is dat in de plugin, bv `klasse-plugin-boilerplate/tmp/screenshots`. Doe je een algemene test dan is dat `/var/www/klasse/tmp/screenshots`.

`tmp`-folders zitten in `.gitignore` en worden dus niet gepushed.

	`tmp`-folders gaan we ook kunnen gebruiken voor logging, maar zo ver zijn we nog niet (zie KD-808).