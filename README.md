# WooCommerce Payconiq
  
This plugin does add new payment method to WooCommerce so the visitor can pay through payconiq gateway.

## Inhoud
1. [ config ](config/README.md)
2. [ controller ](controller/README.md)
3. [ js ](js/README.md)
4. [ lib ](lib/README.md)
5. [ model ](model/README.md)
6. [ style ](style/README.md)
7. [ tests ](tests/README.md)
8. [ view ](view/README.md)

## How to use the plugin
Get the Merchant ID and Secret password from Payconiq.

### Voor een property:

    /**
     * String die de modus aangeeft van de profielpagina:
     *  - 'profile' voor het bekijken en updaten van een bestaand profiel
     *  - 'register' voor registratie van een nieuwe gebruiker
     *  - 'login' voor login
     *
     * @see set_mode()
     * @var string
     */
    private static $mode = 'profile';
    
### Voor een method:

    /**
     * Genereert een url naar een bepaalde mode, als die toegelaten is.
     *
     * @see $allowed_modes
     * @param string $mode De modus waarvoor een url moet gegenereerd worden.
     * @param string $flavor Optioneel. Eventuele extra parameter die toegevoegd wordt aan de querystring. Standaard leeg.
     * @param string|array $query {
     *     Optioneel. Array of string met parameters.
     *
     *     @type int          $id           ID van iets.
     *     @type int|string   $author       ID van iets, of komma gescheiden lijst van ID's.
     * }
     * @todo Mergen met get_permalink()
     * @return string de url naar de modus. Als de meegegeven $mode leeg of foutief is, wordt de standaard profiellink teruggegeven.
     */
    public static function get_mode_url( $mode = 'profile', $flavor = '', $query = '' ) {
        ...
    }

Er kan ook gebruik gemaakt worden van `@see` en `@todo` om code van context te voorzien.

Daarnaast moet de versie nummer van de method ook bijgehouden worden. Die kan met deze tags `@since` en `@version`.

## Dependency Injection Container

Dependency Injection Container wordt gebruikt voor laden van alle objecten met dependency injection methode. Deze methode zorgt ervoor dat de objecten modulair geschreven moet worden, zodat je op ieder moment van library/dependency kan switchen.

Bijvoorbeeld class A gebruikt een logger class die met een database werkt. Als je de database wilt aanpassen naar een file methode, dan moet je class A niet aanpassen. Je moet dan alleen de logger class aanpassen.

Container dient dan ervoor dat je al die classes op 1 plek gaat definiëren zodat je o.a. later bij testen alle classes kan aanpassen naar mocked versie.  

### korte uitleg met voorbeeld [php-di](http://php-di.org/doc/understanding-di.html)

#### Classic PHP code #
Here is how a code **not** using DI will roughly work:

- Application needs Foo (e.g. a controller), so:
- Application creates Foo
- Application calls Foo
    - Foo needs Bar (e.g. a service), so:
    - Foo creates Bar
    - Foo calls Bar
        - Bar needs Bim (a service, a repository, …), so:
        - Bar creates Bim
        - Bar does something

#### Using dependency injection #
Here is how a code using DI will roughly work:

- Application needs Foo, which needs Bar, which needs Bim, so:
- Application creates Bim
- Application creates Bar and gives it Bim
- Application creates Foo and gives it Bar
- Application calls Foo
    - Foo calls Bar
        - Bar does something
        
This is the pattern of **Inversion of Control**. The control of the dependencies is **inverted** from one being called to the one calling.

The main advantage: the one at the top of the caller chain is always **you**. You can control all dependencies and have complete control over how your application works. You can replace a dependency by another (one you made for example).

For example what if Library X uses Logger Y and you want to make it use your logger Z? With dependency injection, you don't have to change the code of Library X.


Dependency injection and dependency injection containers are different things:

dependency injection is a method for writing better code
a container is a tool to help injecting dependencies
You don't need a container to do dependency injection. However a container can help you.

PHP-DI is about this: making dependency injection more practical.

The theory #
Classic PHP code #
Here is how a code not using DI will roughly work:

Application needs Foo (e.g. a controller), so:
Application creates Foo
Application calls Foo
Foo needs Bar (e.g. a service), so:
Foo creates Bar
Foo calls Bar
Bar needs Bim (a service, a repository, …), so:
Bar creates Bim
Bar does something
Using dependency injection #
Here is how a code using DI will roughly work:

Application needs Foo, which needs Bar, which needs Bim, so:
Application creates Bim
Application creates Bar and gives it Bim
Application creates Foo and gives it Bar
Application calls Foo
Foo calls Bar
Bar does something
This is the pattern of Inversion of Control. The control of the dependencies is inverted from one being called to the one calling.

The main advantage: the one at the top of the caller chain is always you. You can control all dependencies and have complete control over how your application works. You can replace a dependency by another (one you made for example).

For example what if Library X uses Logger Y and you want to make it use your logger Z? With dependency injection, you don't have to change the code of Library X.

#### Using a container #
Now how does a code using PHP-DI works:

- Application needs Foo so:
- Application gets Foo from the Container, so:
    - Container creates Bim
    - Container creates Bar and gives it Bim
    - Container creates Foo and gives it Bar
- Application calls Foo
    - Foo calls Bar
        - Bar does something
        
In short, **the container takes away all the work of creating and injecting dependencies.**

