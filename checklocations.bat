@ECHO OFF
ECHO Lancement de la commande envoyant des alertes e-mail pour tous les vehicules loues non rendus...

php bin/console app:verif-locations

PAUSE