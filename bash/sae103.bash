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


if [ $# == 1 ] 
then
    if [ $1 == "--build" ]
    then
        echo "Version build"
        build `egrep ^VERSION= config | colrm 1 8`
    elif [ $1 == "--minor" ]
    then
        echo "Version minor"
        minor `egrep ^VERSION= config | colrm 1 8`
    elif [ $1 == "--major" ]
    then
        echo "Version major"
        major `egrep ^VERSION= config | colrm 1 8`
    fi
fi
    docker volume create sae103

    docker container run --name sae103-forever -dv sae103:/work clock > tempIds
    idClock=$(cat tempIds)

    for file in `ls *.c`; do 
        docker cp $file $idClock:/work
    done



    docker container stop $idClock
    docker container rm $idClock
    docker volume rm sae103
fi
