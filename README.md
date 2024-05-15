# Pamutlabor próbafeladat

Ebben a repository-ban található a megoldásom a kapott pamutlabor próbafeladatra. Az itt található információk a project futtatását és értékelését hivatottak segíteni.

## Live
A projekt élesben megtekinthető a következő címen:

http://pamutproba.just-asec.com/

## Futtató környezet
- PHP 8.2+ (pdo, pdo_mysql extension-ökkel)
- MySQL 8.0+

### Teszteléshez
- PHPUnit 11.1

## Beállítások
Minden elérhető beállítás a gyökérben lévő `.env.php`-ban található.

### E-mail
Alapértelmezetten nem küld levelet, helyette a `logs/` mappába logolja a küldendő üzeneteket. Ha a szerveren be van megfelelően konfigurálva, akkor a `MAIL.DRIVER`-ben meg lehet adni a `MailServiceDriver::SimpleMail` értéket, ami egy nagyon egyszerű `mail` függvény wrapper :)

## Dev endpointok
Készítettem egy külön endpointot a tesztelés megkönnyítésére.

`GET /dev/random`

Ez generál pár (alig) random projektet a meglévő kapcsolattartókkal és státuszokkal. A `count` request paraméterrel lehet megadni, hogy pontosan mennyit készítsen.

## Docker
Csatoltam docker build lehetőséget, valamint van `compose.yaml` is a megfelelő szolgáltatásokkal előre beállítva. Ez a szokásos compose parancssal elvileg elindít mindent, ami csak kell.

`docker compose up -d`

### Tesztek futtatása dockerben
Erre a `pamut-test` vagy a `/var/www/docker/pamut-test.sh` ad lehetőséget.