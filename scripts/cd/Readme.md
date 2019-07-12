# Readme Stack

### Testen van Drupal stack

Pas de variablen in .env aan en test de stack 

benodigdheden:
- Traefik voor routing lokaal (Drupaldev project in Gitlab)

#### Opstarten:
```
docker-compose -p pluimen-fixtures -f stack.yml pull
```