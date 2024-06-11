/**
* Le programme sert à trier le contenu d'un tableau
* par la méthode du tri par selection et qui affiche le tableau
* avant et après le tri par selection
*/

#include <stdio.h>
#include <stdlib.h>
#include <stdbool.h>
#include <string.h>

#define N 10 /** La taille du tableau */
#define ZERO 0 /** la valeur 0 */

typedef int tab[N];

typedef struct client {
    int no_client;        /** numéro du client */
    char nom[20];       /** nom du client */
    char prenom[20];    /** prenom du client */
    int age;            /** age du client */
}; /** Structure d'un client */

typedef struct ocean {
    char nom[20];       /** nom de l'ocean */
    int superficie;     /** superficie de l'ocean */
}; /** Structure d'un ocean */

void tri_selection(tab);
bool existe(tab,int);
int somme(int,int);

int main() {
    int nbEtudiant = 45;                    /** Nombre d'étudiants */
    tab T_test = {1,2,3,4,5,6,7,8,9,10};    /** Tableau d'entier de 1 à 10 dans l'ordre croissant */
    tab T = {4,1,8,9,8,11,10,3,8,6};        /** Tableau d'entier à trier */

    for(int i = ZERO ; i < N ; i++){
        printf("T[%d]=%d\n",i,T[i]);
    }

    tri_selection(T);

    printf("---------------\n");

    for(int i = ZERO ; i < N ; i++){
        printf("T[%d]=%d\n",i,T[i]);
    }

    return EXIT_SUCCESS;
}

/**
 * \brief effectue un tri par sélection dans un tableau
 * \detail le tableau passé en paramètre est trié par sélection
 * si le tableau est constitué de plus qu'une case remplie.
 * 
 * Il parcout toute la partie non trié du tableau (à gauche)
 * en comparant les valeurs entre elles, avant de les placer les
 * plus à gauche de la zone non triée.
 * 
 * \param tab T le tableau à trier
*/
void tri_selection(tab T){
    int imin;
    int mem;
    for(int i = 0 ; i < N-1 ; i++){
        imin = i;
        for (int j = i+1; j < N; j++){
            if(T[j] < T[imin])
                imin = j;
        }
        mem = T[i];
        T[i] = T[imin];
        T[imin] = mem;
    }
}

/**
 * \brief vérifie si une valeur est présente dans le tableau
 * \detail à l'aide d'une boucle for, on parcourt le tableau
 * afin de vérifier si la valeur passée en paramètre se trouve
 * dans le tableau passée aussi en paramètre.
 * 
 * On arrête la recherche dans le tableau si la valeur est
 * trouvé dans le tableau ou bien que le tableau a fini d'être
 * parcouru
 * 
 * \return bool : true si la valeur est trouvé, false sinon
 * \param tab T le tableau à parcourir
 * \param int nb la valeur à trouver dans le tableau
*/
bool existe(tab T, int nb){
    bool existeBien = false;
    for(int i = 0 ; i < N ; i++){
        if(T[i] = nb)
            existeBien = true;
    }
    return existeBien;
}

/**
 * \brief fait la somme de 2 entiers
 * \detail on additionne 2 entiers entre eux pour en retourner
 * le résultat
 * 
 * \return int la somme des 2 entiers
 * \param int a le 1er entier
 * \param int b le 2e entier
*/
int somme(int a, int b){
    return a + b;
}
