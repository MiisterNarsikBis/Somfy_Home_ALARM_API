# Somfy_Home_ALARM_API

![Image Screen](https://zupimages.net/up/22/24/0634.png)
![Image Screen](https://zupimages.net/up/22/24/2hlp.png)

Widget iPhone (page state.php?display&passwordPanel=Motdepasse)
![Image Screen](https://zupimages.net/up/22/24/ti8z.png)

Utilisation des api myfox pour somfy home alarm

1) deplacer le dossier private dans le repertoire parent
2) appeler la page control.php avec l'argument action= armed, disarmed, partial, weekend, notif_off ou notif_on
ex: control.php?action=armed

Pour connaite l'état de l'alarme il faut appeler la page state.php

Description des commandes:
- armed : activer l'alarme
- disarmed : desactiver l'alarme
- partial: mode nuit : 3 etapes: desactivation de la notification sonore de la sirene, activation du mode nuit, réactivation de la notification sonore (après le délais d'activation)
- weekend: mode nuit sans réactivation des notifications sonores (évite d'être reveillé par la notification)
- notif_off: desactivation des notifications sonores de la sirene
- notif_on: activation des notifications sonores de la sirene

Un grand merci à @Mystikal57 pour le fork !
En cas de besoin, je suis disponible sur discord MiisterNarsik#7461

Changelog:

18-06-2022:
- Je reprends ce projet, j'ai adapté les functions pour les Curl, un peu de tri et des includes
- Par la suite, la page d'accueil inclura aussi les control.php (pas encore eu le temps de faire)
- la config.ini aura un nouveau champ "PasswordPanel", ce qui permettra d'avoir un mot de passe pour accéder au panel

28-09-2020:
- Modification page parametres afin qu'il trouve automatiquement l'ID de la sirene

10-11-2020:
- ajout de la génération automatique du token à l'aide du nom d'utilisateur et mot de passe du fichier config.ini
- si le token est juste expiré -> raffraichissement du token
- ajout mode weekend
