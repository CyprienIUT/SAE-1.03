# Documentation utilisateur

## Liste

### Liste non ordonnée

- Elt 1
- Elt 2

## Tableau

| Tableau | à   | quatre  | colonnes    |
| ------- | --- | ------- | ----------- |
| avec    | des | valeurs | différentes |

## Code

### Bloc de code

```C
int main() {
        vecteur vInitial = {35,25,20,20};
        matrice mEvolution = {{0.9 , 0.06 , 0.08 , 0.05},
                              {0.03, 0.8  , 0.02 , 0.03},
                              {0.02, 0.03 , 0.75 , 0.04},
                              {0.05, 0.11 , 0.15 , 0.88}};
        vecteur vResultat;
    
        afficherVecteur(vInitial,4);
    
        afficherMatrice(mEvolution,4);
    
        for(int i = 0; i != 30 ; i++){
            afficherVecteur(vResultat,4);
            copiervecteur(vInitial,vResultat,4);
            produitVectMat(vResultat,mEvolution,vResultat,4);
        }

        return EXIT_SUCCESS;
}
```

### Code en ligne
Ce `Code` est du code.

## Lien

[lien vers le titre Liste](#Liste)

## Texte

Lorem ipsumdolor sit amet, consectetur adipiscing elit. 

### Décoration de texte

#### Gras
Ce **texte** est en gras

#### Italique
Ce _texte_ est en italique
