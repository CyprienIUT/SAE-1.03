#!/bin/bash

build() {
    old_build=$(echo $1 | cut -d'.' -f 3)
    new_build=$((old_build + 1))

    result="$(echo $1 | cut -d'.' -f1-2).$new_build"
    
    grep -v '^VERSION=' config > config.tmp
    (
        cat config.tmp
        echo -n "VERSION=$result"
    ) > config
    rm config.tmp
}

minor() {
    old_build=$(echo $1 | cut -d'.' -f 2)
    new_build=$((old_build + 1))

    result="$(echo $1 | cut -d'.' -f1).$new_build.0"
    
    grep -v '^VERSION=' config > config.tmp
    (
        cat config.tmp
        echo -n "VERSION=$result"
    ) > config
    rm config.tmp
}

major() {
    old_build=$(echo $1 | cut -d'.' -f 1)
    new_build=$((old_build + 1))

    result="$new_build.0.0"
    
    grep -v '^VERSION=' config > config.tmp
    (
        cat config.tmp
        echo -n "VERSION=$result"
    ) > config
    rm config.tmp
}

version=$(cat config | egrep ^VERSION= | colrm 1 8)
if [ $# == 1 ] 
then
    if [ $1 == "--build" ]
    then
        echo "Version build"
        build $version
    elif [ $1 == "--minor" ]
    then
        echo "Version minor"
        minor $version
    elif [ $1 == "--major" ]
    then
        echo "Version major"
        major $version
    fi
fi

version=$(cat config | egrep ^VERSION= | colrm 1 8)
docker volume create sae103
docker container run --name sae103-forever -dv sae103:/work clock > tempIds
idClock=$(cat tempIds)
echo "Création de clock en étant monté sur le volume"

# copie des fichiers 
for file in `ls *.c`; do 
    docker cp $file $idClock:/work/ 
done

docker cp "DOC_UTILISATEUR.md" $idClock:/work/
docker cp "gendoc-tech.php" $idClock:/work/
docker cp "gendoc-user.php" $idClock:/work/
docker cp "config" $idClock:/work/
echo "Copie des fichiers dans le volume monté"

# generation de la doc
docker container run --rm -tiv sae103:/work sae103-php php -f /work/gendoc-tech.php > "doc-technique-$version.html" # ne marche pas sans une version interactive
docker container run --rm -tiv sae103:/work sae103-php php -f /work/gendoc-user.php > "doc-utilisateur-$version.html" # ne marche pas sans une version interactive
docker cp doc-technique-$version.html $idClock:/work
docker cp doc-utilisateur-$version.html $idClock:/work
echo "Génération des docs"

# conversion en PDF
docker container run --rm -tiv  sae103:/work sae103-html2pdf "html2pdf doc-technique-$version.html doc-technique-$version.pdf" # ne marche pas
docker container run --rm -tiv sae103:/work sae103-html2pdf "html2pdf doc-utilisateur-$version.html doc-utilisateur-$version.pdf" # ne marche pas
echo "Génération des pdfs"

# copie pour les tests
docker cp $idClock:/work/doc-utilisateur-$version.html ./
docker cp $idClock:/work/doc-technique-$version.html ./
docker cp $idClock:/work/doc-utilisateur-$version.pdf ./
docker cp $idClock:/work/doc-technique-$version.pdf ./
echo "Copie des fichiers hors du volume"

# arret des conteneurs et du volume
docker container stop $idClock
docker container rm $idClock
docker volume rm sae103