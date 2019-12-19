# Mijn izi account

## Installatie

Bij installatie voor de eerste keer dienen de volgende stappen te worden uitgevoerd:

1. Kopiëer *config/config.empty.php* naar *config/config.php* en vul de *base_url* in.
2. Kopiëer *config/database.empty.php* naar *config/database.php* en vul de juiste database configuratie in.
3. Kopiëer *config/email.empty.php* naar *config/email.php* en vul de juiste e-mail configuratie in.
4. Bewerk *config/migtration.php*, zet *migration_enabled* op *true* en voer een migratie uit door naar */migrate* te navigeren.
5. Zet na het uitvoeren van een migratie *migration_enabled* weer op *false*.

Na het installeren van izi account zijn er nog geen bedrijven en gebruikers aanwezig. Deze dienen eenmalig handmatig te worden toegevoegd aan de database.

Stap 4 t/m 5 dienen bij elke migratie te worden uitgevoerd.

## Producten

### Product soorten

Onderstaand de verschillende prouduct soorten zoals deze binnen de applicatie zijn gedefiniëerd:

| Soort                 | Waarde |
| --------------------- | -----: |
| Standaardproduct      | 0      |
| Voorraadproduct       | 1      |
| Product is arbeid     | 2      |
| Product is domeinnaam | 3      |

### Product typen

Onderstaand de verschillende product typen zoals deze binnen de applicatie zijn gedefiniëerd:

| Type           | Waarde |
| -------------- | -----: |
| Verkoopproduct | 0      |
| Inkoopproduct  | 1      |
| Beide          | 2      |
