# Acceptance testen schrijven

## Doel acceptance testen

Doel van de acceptatietest is om tijdig te weten te komen of het systeem voor de gebruiker inclusief management en beheer, acceptabel is.

Bij de acceptatietest wordt nagegaan of er daarnaast problemen zijn te verwachten in het gebruik die eerder nog niet gevonden zijn.

## Wat heb je nodig?

Een kort overzicht van wat er nodig is om testen aan de praat te krijgen. Alles wordt geïnstalleerd via vagrant. De eerste installatie lukt meestal niet. Na een provision wordt alles wel geïnstalleerd.

> `vagrant up`

> daarna `vagrant reload --provision`

* Vagrant VM.
* PHPUnit 5.7.x.
* Phpunit-selenium
* Php-webdriver (als alternatief voor Phpunit-selenium)
* Selenium standalone server
* Java
* Firefox
* Chrome
* geckodriver
* chromedriver
* xvfb

## Hoe begin je eraan met vagrant?

Zorg ervoor dat je vagrant instance goed draait en dat local.klasse.be normaal werkt. We gaan ervan uit dat alle volgende stappen gebeuren binnen je Vagrant VM.

> Log in op je VM met `vagrant ssh`.

> Start de selenium standalone server met `xvfb-run --server-args='-screen 0, 1920x1200x24' java -jar /usr/local/bin/selenium-server-standalone-3.4.0.jar`. Laat dit tablab openstaan.

> Open een nieuwe tabblad in je terminal en log opnieuw in met `vagrant ssh`

> Ga naar de map waar je testen wil uitvoeren en start de test met het commando `phpunit`.

Zie ook:

- [Puphpet repository](https://bitbucket.org/klasse/puphpet)
- [ README ](README.md)

## Phpunit-selenium of Php-webdriver?

Beide gebruiken minstens versie 2 van selenium en kunnen via PHPUnit getest worden.

Php-webdriver is een alternatief voor phpunit-selenium. Volgens de auteur zou die recenter zijn en versie 3 volgen ipv 2 (phpunit-selenium).

Verder onderzoek moet nog uitmaken welke van de 2 het beste voor ons kan zijn (momenteel enkel uitgebreide testen gedaan met phpunit-selenium).

- [Voorbeelden testen phpunit-selenium](https://github.com/giorgiosironi/phpunit-selenium/blob/master/Tests/Selenium2TestCaseTest.php)
- [Voorbeelden testen php-webdriver](https://github.com/facebook/php-webdriver/blob/community/example.phpp)

## Tips

Je kan de selenium server stoppen door `ctrl-c` in de voeren.

Voeg de optie `-a` toe aan xvfb-run als je `error: Xvfb failed to start` krijgt: `xvfb-run -a [commando]`. Er is dan waarschijnlijk al een process van xvfb met hetzelfde scherm.

Xvfb debuggen kan door `-e /dev/stdout` toe te voegen aan het commando: `xvfb-run -e /dev/stdout [commando]`.

Schermresolutie kan je wijzigen door `1920x1200x24` in `--server-args=` aan te passen.