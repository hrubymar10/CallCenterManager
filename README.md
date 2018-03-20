# CallCenterManager
Tento projekt má za úkol ukázat, jak se dá řešit evidence v Call Centru.

## Realizace:
### Při realizaci byly použity následující věci:
* `nginx/1.13.9`
* `PHP 7.1.14`
* Visual Studio Code

Data se ukládají pomocí noSQL do textových souborů do složky `contacts`.

## Instalace:
1. Server s nastaveným `PHP` a `nginx`
2. Nainstalovat `Composer` pomocí návodu zde: https://getcomposer.org/doc/00-intro.md
3. Spustit `composer install`

## Zabezpečení:
### `contacts`
Dle využití lze přímý přístup do složky `contacts` zablokovat pomocí následujícího konfiguračního bloku pro nginx:

```
location /contacts/ {
        return 403;
}
```

