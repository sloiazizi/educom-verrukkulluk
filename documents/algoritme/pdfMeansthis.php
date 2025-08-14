<?php

/*
the first one can be read like this i guess:

START: function addingGroceries(recipe_id, user_id)
⬇️
ACTION: ingredients = fetchIngredients(recipe_id)
⬇️
LOOP: "for each ingredient"
⬇️
DECISION: "Is the article already on shopping list?" (ingredient->article_id, user_id)
├─ NO → ACTION: "Add article to shopping list"
└─ YES → ACTION: "Update article quantity i guess"
⬇️
END loop, 
END function

---------------------------------------------------------------------

START: function articleOnList(article_id, user_id)
⬇️
ACTION: shoppingList = fetchShoppingList(user_id)
⬇️
LOOP: "for each shoppingList item"
⬇️
DECISION: "Does shoppingList->article_id == article_id?"
├─ NO → Continue the loop
└─ YES → RETURN: shoppingList item
⬇️
END: return false (if nothing found)
-----------------------------------------------------------------------

okay so i need for first psd:
    - addingGroceries($recipe_id, $user_id)                      >>> Main function
    - fetchIngredients($recipe_id)                               >>> fetch ingredients
    - articleOnList($article_id, $user_id)                       >>> Check of article bestaat
    - addArticleToShoppingList($article_id, $user_id, $amount)   >>> Add article + n
    - updateArticleQuantity($shopping_list_id, $new_amount)      >>> Update article n=....

the second one:
    - articleOnList($article_id, $user_id)                        >>> Main function
    - fetchShoppingList($user_id)                                 >>> Get shopping list

-------------------------------------------------------------------------

so overall i guess i will need a checker script and adder. cool
PSD 1 = GroceryAdder
PSD 2 = ShoppingListChecker
>>>>> GroceryAdder calls ShoppingListChecker                    <<< niet vergeten existingArticle want anders lastig straks... ofja dat lijkt me.

-------------------------------------------------------------------------

now what will stay public and what private...
what functions zullen door andere classes opgeroepen worden (must >> therefore PUBLIC):
    - addingGroceries() - main entry point
    - articleOnList() - called by GroceryAdder
    - fetchShoppingList() - utility function

Welke zijn internal helpers used binnen zelfde class... ja weetje ik denk:
    - addArticleToShoppingList()                                 >>> only used in GroceryAdder i think
    - updateArticleQuantity()                                    >>> only used in GroceryAdder .. i think

oh wacht en in GroceryAdder moet ik uiteraard ook require_once(ingredients.php) doen en voor ShoppingListChecker.php

-------------------------------------------------------------------------

oke voor GroceryAdder heb ik nodig:

public function addingGroceries($recipe_id, $user_id) {
    $ingredients = $this->ingredients->fetchIngredients($recipe_id);
    
    if (!$ingredients) {
    return false;
    }

    en dan een foreach of ... nee wacht een foreach is handiger denk ik voor existingArticle stukje... hmmm

    foreach ($ingredients as $ingredient) {
        $article_id = $ingredient['article_id'];
        $amount = $ingredient['amount'];

        $existingArticle = $this->checker->articleOnList($article_id, $user_id);    << Use ShoppingListChecker to check whether its on list already yes or no 
        if ($existingArticle) {                                                     << zoals before met rene example en ook toen met yt video
                $newAmount = $existingArticle['amount'] + $amount;
                $this->updateArticleQuantity($existingArticle['id'], $newAmount);

        } else {
            $this->addArticleToShoppingList($article_id, $user_id, $amount);
        }
            REST IN SCRIPT ITSELF
    }
}


------------------------------------------------------------------------
Bruh hoeveelheden zijn verkeerd
oke eerst shoppinglist tabel maken lets start there >>> easier om gewoon in SQL in phpMyAdmin te doen

[done]

oke nu i guess tabel aanpassen want stomme eenheden zijn verkeerd. in article:
    - package size 
    - package unit?

.................................................
 werkte niet kgkgflflhfhfljhfl
.................................. oke wait...
    
    - package size
    - recipe unit
    - unit

INSERT INTO `article` (`id`, `name`, `description`, `price`, `package_size`, `recipe_unit`, `unit`) VALUES
(1, 'Komkommer', 'Verse komkommer', 0.99, 1.00, 'stuk', 'per stuk'),
(2, 'Tomaat', 'Ripe tomato', 0.85, 1.00, 'stuk', 'per stuk'),
(3, 'Feta cheese', 'Zachte feta kaas', 2.99, 200.00, 'gram', 'per 200g '),
(4, 'Cheddar cheese', 'Geraspte cheddar', 3.99, 200.00, 'gram', 'per 200g '),
(5, 'Sriracha sauce', 'Pittige saus', 1.99, 1.00, 'stuk', 'per fles'),
(6, 'Ui (onion)', 'Rode ui', 0.50, 1.00, 'stuk', 'per stuk'),
(7, 'Noedels', 'Gedroogde noedels', 2.00, 500.00, 'gram', 'per 500g '),
(8, 'Sesamzaadjes', 'Sesamzaadjes', 1.25, 100.00, 'gram', 'per 100g '),
(9, 'Stroop', 'Traditionele stroop', 1.75, 1.00, 'stuk', 'per fles'),
(10, 'Poedersuiker', 'Suiker om te strooien', 0.99, 1.00, 'stuk', 'per pak'),
(11, 'munt', 'Verse muntblaadjes', 1.50, 1.00, 'stuk', 'per bosje');


YE this kinda works... alleen nu stomme issue met lijst addition and calc....

oke existing needed dus misschien via manual
    $new_total_amount = $existingItem['total_amount_needed'] + $needed_amount_total;
    $new_packages_needed = $this->calculatePackagesNeeded($new_total_amount, $package_size);
    $this->updateShoppingListItem($existingItem['id'], $new_packages_needed, $new_total_amount);
............................................... 

oke rest via script uitproberen
*/

