/**
 * Ce programme incroyable correspond au 
 * jeu du puissance 4. Chaque joueur joue 
 * à tour de rôle jusqu'à ce que l'un d'eux
 * aligne 4 pions à lui dans la grille, 
 * auquel cas il gagne. 
*/

#include <stdio.h>
#include <stdbool.h>

#define ROWS 6 /** Nombre de lignes dans la grille*/
#define COLS 7 /**  Nombre de colonnes dans la grille*/

typedef struct {
    char name[50]; /** Nom du joueur*/
    char symbol; /** Symbole du pion du joueur */
} Player; /** Structure de joueur */

typedef struct {
    char grid[ROWS][COLS]; /** Grille du Jeu*/
} Board; /** Structure de tableau de jeu*/


int currentPlayer; /** Joueur actuel*/
Player player1; /**Structure du joueur 1*/
Player player2; /**Structure du joueur 2*/
Board gameBoard; /** Structure du tableau de jeu*/

void initializeGame();
void printBoard();
bool dropDisc(int column);
bool checkWin();
bool isBoardFull();

int main() {

    initializeGame();

    while (!isBoardFull() && !checkWin()) {
        printBoard();

        int column;
        printf("Joueur %s (%c), choisissez une colonne (1-7): ", (currentPlayer == 1) ? player1.name : player2.name, (currentPlayer == 1) ? player1.symbol : player2.symbol);
        scanf("%d", &column);

        if (column < 1 || column > COLS || !dropDisc(column - 1)) {
            printf("Choix invalide. Veuillez réessayer.\n");
            continue;
        }

        currentPlayer = (currentPlayer == 1) ? 2 : 1;
    }

    printBoard();
    if (checkWin()) {
        printf("Joueur %s gagne !\n", (currentPlayer == 1) ? player2.name : player1.name);
    } else {
        printf("Match nul !\n");
    }

    return 0;
}

/**
 * \brief Initialise le jeu
 * 
 * \detail Fonction qui initialise le tableau de jeu.
 * Chaque case est remplie par une chaîne de caractère vide.
 * Initialise également le nom de chaque joueur ainsi que 
 * le symbole qui représente son pion. 
*/
void initializeGame() {

    printf("Nom du Joueur 1 : ");
    scanf("%s", player1.name);
    player1.symbol = 'X';

    printf("Nom du Joueur 2 : ");
    scanf("%s", player2.name);
    player2.symbol = 'O';

    for (int i = 0; i < ROWS; i++) {
        for (int j = 0; j < COLS; j++) {
            gameBoard.grid[i][j] = ' ';
        }
    }

    currentPlayer = 1;
}

/**
 * \brief Permet d'afficher la grille du puissance 4
 * 
 * \detail Affiche la grille sous la forme d'un tableau
 * avec des séparateurs verticales "|" pour la mise en page
*/
void printBoard() {
    printf("\n");
    for (int i = 0; i < ROWS; i++) {
        for (int j = 0; j < COLS; j++) {
            printf("| %c ", gameBoard.grid[i][j]);
        }
        printf("|\n");
    }
    printf("-----------------------------\n");
}

/**
 * \brief Fonction qui fait placer un pion au joueur dont c'est le tour
 * 
 * \detail Fonction qui permet au joueur dont c'est le tour 
 * de placer un pion au sommet de la colonne sélectionnez par column.
 * Renvoie true si la colonne est libre et false sinon.
 * 
 * \return bool colonne est libre.
 * 
 * \param int column indice de la colonne 
 * 
*/
bool dropDisc(int column) {

    for (int i = ROWS - 1; i >= 0; i--) {
        if (gameBoard.grid[i][column] == ' ') {
            gameBoard.grid[i][column] = (currentPlayer == 1) ? player1.symbol : player2.symbol;
            return true;
        }
    }
    return false; 
}

/**
 * \brief permet de vérifier si quelqu'un a gagné
 * 
 * \detail Fonction qui parcours la grille et 
 * vérifie si 4 pions sont alignés. Si c'est le cas,
 * renvoie true et fait gagner le joueur dont c'est 
 * le tour. Sinon, retourne false. 
 * 
 * \return bool un joueur a aligné 4 pion
 * 
*/
bool checkWin() {

    return false;
}

/**
 * \brief permet de vérifier si le tableau est complet
 * 
 * \detail Fonction qui parcours la grille et 
 * vérifie si le tableau est complet. Si c'est
 * le cas, alors renvoie true et la partie est 
 * considérée comme un match nul. Sinon, renvoie false.
 * 
 * \return bool un joueur a aligné 4 pion
 * 
*/
bool isBoardFull() {

    for (int i = 0; i < ROWS; i++) {
        for (int j = 0; j < COLS; j++) {
            if (gameBoard.grid[i][j] == ' ') {
                return false; 
            }
        }
    }
    return true;
}