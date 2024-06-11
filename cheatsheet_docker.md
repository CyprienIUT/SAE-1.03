`docker image pull nom_image` = pull l'image `nom_image` sur sa machine

`docker container run nom_image` = lancer l'image `nom_image` avec la commande par défaut

`docker container run nom_image commande` = lancer la commande `commande` dans l'image `nom_image`

`docker container ps` = liste les container actifs
- `-a` = les containers même inactifs

`docker container stop ID` = stop le container à l'ID `ID`

`docker container logs -f ID` = montre les logs du container

`docker container exec -ti ID sh` = exécute dans le container ayant l'id `ID` la commande sh
- `-t` = dans un terminal, TTY
- `i` = lire des choses au clavier, interactive

`docker container rm ID` = supprime le container ayant l'id `ID`

