/** 
 * Un exemple de code en C avec des structures, constantes, 
 * fonctions et procédures.
 */

#include <stdio.h>
#define MAX_POINTS 14 /** Nombre max de point*/

typedef struct {
    float x; /** Coordonnée x du point. */
    float y; /** Coordonnée y du point. */
} Point2D; /** Structure représentant un point dans l'espace 2D. */

const float PI = 3.14159265359; /** Constante représentant la valeur de pi. */


/**  
 * \brief affiche les coordonnées d'un point. 
 * \detail affiche les coordonnées d'un point graçe a la structure Point2D qui est pris en paramêtre
 * \param Point2D point Coordonné x et y d'un point
 * */
void afficherPoint(Point2D point) {
    printf("Coordonnées du point : (%.2f, %.2f)\n", point.x, point.y);
}

/**  
 * \brief Calcule la distance entre deux points. 
 * \detail Calcule la distance entre deux points mis en paramètre en fesant une 
 * soustraction les coordonné x des points puis y et ensuite les additionne
 * \return float : La distance entre les deux points
 * \param point2D a Coordonné x et y d'un point a
 * \param point2D a Coordonné x et y d'un point b
*/
float calculerDistance(Point2D a, Point2D b) {
    float dx = b.x - a.x;
    float dy = b.y - a.y;
    return sqrt(dx * dx + dy * dy);
}


typedef struct {
    Point2D coinSuperieurGauche; /** Coin supérieur gauche du rectangle. */
    Point2D coinInferieurDroit; /** Coin inférieur droit du rectangle. */
} Rectangle; /** Structure représentant un rectangle. */


const int LARGEUR_MAX = 100; /** Constante représentant la largeur maximale d'un rectangle. */

/**  
 * \brief Calcule l'aire d'un rectangle. 
 * \detail Calcule l'aire d'un rectangle mis en paramètre en calculant 
 * la largeur puis la hauteur et ensuite les multiplie entre
 * \return float largeur * float hauteur Le calcul de l'aire
*/
float calculerAire(Rectangle rect) {
    float largeur = rect.coinInferieurDroit.x - rect.coinSuperieurGauche.x;
    float hauteur = rect.coinInferieurDroit.y - rect.coinSuperieurGauche.y;
    return largeur * hauteur;
}

/**  
 * \brief Procédure principale. 
 * \detail -initialise deux point(pointA, PointB) de type Point2D
 *          -affiche les coordonné des deux points
 *          -calcul la distance entre les points
 *          -Création d'un rectangle monRectangle de type Rectangle
 *          -Calcul de l'aire du rectangle
*/
int main() {
    Point2D pointA = {1.0, 2.0}; 
    Point2D pointB = {4.0, 6.0}; 
    Rectangle monRectangle = {{2.0, 3.0}, {6.0, 5.0}};
    float distance; 
    float aireRectangle; 

    afficherPoint(pointA);
    afficherPoint(pointB);

    distance = calculerDistance(pointA, pointB);
    printf("Distance entre les points : %.2f\n", distance);

    aireRectangle = calculerAire(monRectangle);
    printf("Aire du rectangle : %.2f\n", aireRectangle);

    return EXIT_SUCCESS;
}
