<?php
	/* __________ CONFIGURATION ____________ */
	if (!defined("INCLUDES_PATH")){
		require_once("config.php");
	}
/* ¯¯¯¯¯¯¯¯¯¯ CONFIGURATION ¯¯¯¯¯¯¯¯¯¯¯¯ */
require_once(INCLUDES_PATH . '/dbi_functions_sqlite3.php');


	//phpinfo();
	$error = '';
	echo 'initializing connection...';
	$db = new Sqlite3Connection();
	echo '<strong>connection initialized!</strong><br />';

	if ($db) {
		// table setup
		$dropStrings = array(
			'drop table recipes_ingredients',
			'drop table menus_recipes',
            'drop table ingredients_types',
			'drop table directions',
			'drop table recipes',
			'drop table ingredients',
			'drop table menus',
			'drop table types',
            'drop table measures'
		);

		$createStrings = array(
			'create table measures (id integer not null, name text, primary key (id))',
			'create table types (id integer not null, name text not null, primary key (id))',
			'create table menus (id integer not null, name text not null, start_date text not null,
              end_date text not null, primary key (id))',
			'create table ingredients(id integer not null, name text not null, primary key (id))',
			'create table recipes (id integer not null, name text not null, min_time integer not null,
              max_time integer not null, servings integer not null, primary key (id))',
			'create table directions (id integer not null, recipes_id integer not null, step_number integer not null,
              direction_text text not null, primary key (id), foreign key (recipes_id) references recipes (id))',
            'create table ingredients_types(ingredients_id integer not null, types_id integer not null,
              primary key (types_id, ingredients_id), foreign key (types_id) references types (id),
              foreign key(ingredients_id) references ingredients (id))',
			'create table menus_recipes(menus_id integer not null, recipes_id integer not null,
              primary key (menus_id, recipes_id), foreign key (menus_id) references menus (id),
              foreign key (recipes_id) references recipes (id))',
            'create table recipes_ingredients(recipes_id integer not null, ingredients_id integer not null,
              measures_id integer not null, measure_amount real not null, primary key (recipes_id, ingredients_id),
              foreign key (ingredients_id) references ingredients (id), foreign key (recipes_id) references recipes (id),
              foreign key (measures_id) references measures (id))'
		);
		
		$measureStrings = array(
			'insert into measures (name) values ("")',												// 1
			'insert into measures (name) values ("tbsp(s)")',										// 2
			'insert into measures (name) values ("tsp(s)")',										// 3
			'insert into measures (name) values ("cup(s)")',										// 4
			'insert into measures (name) values ("oz(s)")',											// 5
			'insert into measures (name) values ("can(s)")',										// 6
			'insert into measures (name) values ("clove(s)")',										// 7
			'insert into measures (name) values ("stick(s)")',										// 8
			'insert into measures (name) values ("lb(s)")',											// 9
			'insert into measures (name) values ("jar(s)")',										// 10
			'insert into measures (name) values ("bag(s)")',										// 11
			'insert into measures (name) values ("package(s)")'										// 12
		);
		
		$initialTypeStrings = array(
			'insert into types (name) values ("Dairy")',											// 1
			'insert into types (name) values ("Breads, Grains and Pasta")',							// 2
			'insert into types (name) values ("Prepared")',											// 3
			'insert into types (name) values ("Canned Goods")',										// 4
			'insert into types (name) values ("Liquids")',											// 5
			'insert into types (name) values ("Proteins")',											// 6
			'insert into types (name) values ("Produce")',    										// 7
			'insert into types (name) values ("Herbs, Spices and Seasonings")',						// 8
            'insert into types (name) values ("Misc.")'                                             // 9
		);

        $initialMenuStrings = array(
            // YYYY-MM-DD
            'insert into menus (name, start_date, end_date) values ("October 13-17", "2014-09-13", "2014-09-17")',
			'insert into menus (name, start_date, end_date) values ("June 10-14", "2019-06-10", "2014-06-14")',
			'insert into menus (name, start_date, end_date) values ("July 22-26", "2019-07-22", "2019-07-26")'
        );

        $initialIngredientStrings = array(
            // recipe id
            // type
            // name
            'insert into ingredients (name) values ("Ground chicken")',            // 1
            'insert into ingredients (name) values ("White navy beans")',          // 2
            'insert into ingredients (name) values ("Fire roasted tomatoes")',     // 3
            'insert into ingredients (name) values ("Chicken broth")',             // 4
			'insert into ingredients (name) values ("Buffalo wing sauce")',        // 5
			'insert into ingredients (name) values ("Ranch dressing mix")',        // 6
			'insert into ingredients (name) values ("Corn")',                      // 7
			'insert into ingredients (name) values ("Onion powder")',              // 8
			'insert into ingredients (name) values ("Garlic powder")',             // 9
			'insert into ingredients (name) values ("Celery salt")',               // 10
			'insert into ingredients (name) values ("Dried cilantro")',            // 11
			'insert into ingredients (name) values ("Salt")',                      // 12
			'insert into ingredients (name) values ("Cream cheese")',              // 13
			'insert into ingredients (name) values ("Blue cheese crumbles")'       // 14
        );

        $initialIngredientsTypesStrings = array(
            // type id
            // ingredient id
            'insert into ingredients_types(ingredients_id, types_id) values (1, 6)',
            'insert into ingredients_types(ingredients_id, types_id) values (2, 4)',
            'insert into ingredients_types(ingredients_id, types_id) values (3, 4)',
            'insert into ingredients_types(ingredients_id, types_id) values (4, 5)',
            'insert into ingredients_types(ingredients_id, types_id) values (5, 3)',
            'insert into ingredients_types(ingredients_id, types_id) values (6, 3)',
            'insert into ingredients_types(ingredients_id, types_id) values (7, 4)',
            'insert into ingredients_types(ingredients_id, types_id) values (8, 8)',
            'insert into ingredients_types(ingredients_id, types_id) values (9, 8)',
            'insert into ingredients_types(ingredients_id, types_id) values (10, 8)',
            'insert into ingredients_types(ingredients_id, types_id) values (11, 8)',
            'insert into ingredients_types(ingredients_id, types_id) values (12, 8)',
            'insert into ingredients_types(ingredients_id, types_id) values (13, 1)',
            'insert into ingredients_types(ingredients_id, types_id) values (14, 1)'
        );

		$initialRecipeStrings = array(
            //id
            //name
            //min time
            //max time
            //servings
			'insert into recipes (name, min_time, max_time, servings) values ("Slow Cooker Buffalo Chicken Chili",0,0,0)',				    // 1
			'insert into recipes (name, min_time, max_time, servings) values ("Slow Cooker Jalapeno Popper Chicken Taquitos",20,500,4)',	// 2
			'insert into recipes (name, min_time, max_time, servings) values ("Baked Chicken Shawarma",30,60,2)',							// 3
			'insert into recipes (name, min_time, max_time, servings) values ("Creamy Tomato Tortellini Soup",0,0,0)',					    // 4
			'insert into recipes (name, min_time, max_time, servings) values ("Creamy Chicken and Broccoli",0,210,6)'						// 5
		);
		
		$initialDirectionStrings = array(
            //recipe_id
            //step_number
            //direction_text
			'insert into directions (recipes_id, step_number, direction_text) values (1, 1, "Brown ground chicken until fully cooked, place in slow cooker (or brown ahead of time and store in fridge until ready to assemble).")',
			'insert into directions (recipes_id, step_number, direction_text) values (1, 2, "Add remaining ingredients except for cream cheese and blue cheese and give it all a stir to combine.")',
			'insert into directions (recipes_id, step_number, direction_text) values (1, 3, "Add block of cream cheese on the top and cover.")',
			'insert into directions (recipes_id, step_number, direction_text) values (1, 4, "Cook on high for 4 hours or low on 8.")',
			'insert into directions (recipes_id, step_number, direction_text) values (1, 5, "Stir to incorporate cream cheese and add additional wing sauce as desired")',
			'insert into directions (recipes_id, step_number, direction_text) values (1, 6, "Top individual bowls with blue cheese crumbles if desired")'
		);
		
		$initialMenusRecipesStrings = array(
			'insert into menus_recipes (menus_id, recipes_id) values (1, 1)',
			'insert into menus_recipes (menus_id, recipes_id) values (1, 2)',
			'insert into menus_recipes (menus_id, recipes_id) values (1, 3)',
			'insert into menus_recipes (menus_id, recipes_id) values (1, 4)',
			'insert into menus_recipes (menus_id, recipes_id) values (1, 5)'
		);

        $initialRecipesIngredientsStrings = array(
            //ingredients_id
            //measures_id
            //measure_amount
            'insert into recipes_ingredients (recipes_id, ingredients_id, measures_id, measure_amount) values (1, 1, 9, 1)',
            'insert into recipes_ingredients (recipes_id, ingredients_id, measures_id, measure_amount) values (1, 2, 6, 1)',
            'insert into recipes_ingredients (recipes_id, ingredients_id, measures_id, measure_amount) values (1, 3, 6, 1)',
            'insert into recipes_ingredients (recipes_id, ingredients_id, measures_id, measure_amount) values (1, 4, 4, 4)',
            'insert into recipes_ingredients (recipes_id, ingredients_id, measures_id, measure_amount) values (1, 5, 4, 0.5)',
            'insert into recipes_ingredients (recipes_id, ingredients_id, measures_id, measure_amount) values (1, 6, 12, 1)',
            'insert into recipes_ingredients (recipes_id, ingredients_id, measures_id, measure_amount) values (1, 7, 4, 1)',
            'insert into recipes_ingredients (recipes_id, ingredients_id, measures_id, measure_amount) values (1, 8, 3, 0.5)',
            'insert into recipes_ingredients (recipes_id, ingredients_id, measures_id, measure_amount) values (1, 9, 3, 0.5)',
            'insert into recipes_ingredients (recipes_id, ingredients_id, measures_id, measure_amount) values (1, 10, 3, 0.5)',
            'insert into recipes_ingredients (recipes_id, ingredients_id, measures_id, measure_amount) values (1, 11, 3, 0.5)',
            'insert into recipes_ingredients (recipes_id, ingredients_id, measures_id, measure_amount) values (1, 12, 3, 0.5)',
            'insert into recipes_ingredients (recipes_id, ingredients_id, measures_id, measure_amount) values (1, 13, 5, 8)',
            'insert into recipes_ingredients (recipes_id, ingredients_id, measures_id, measure_amount) values (1, 14, 1, 1)'
        );
		
		$checkStrings = array(
			'select * from measures',
			'select * from types',
			'select * from menus',
			'select * from ingredients',
			'select * from recipes',
			'select * from directions',
			'select * from menus_recipes',
            'select * from recipes_ingredients'
		);
		
		$checkRecipeIngredients =
			"select recipes_ingredients.measure_amount,
	                measures.name,
	                ingredients.name
            from ingredients, measures, recipes_ingredients
            where recipes_ingredients.recipes_id = 1
	          and recipes_ingredients.ingredients_id = ingredients.id
	          and recipes_ingredients.measures_id = measures.id";
		
		$checkRecipeDirections =
			'select directions.step_number step,
				directions.direction direction
			from directions
			where directions.recipe_id = 1';
			
		$checkMenusRecipes =
            'select recipes.name
            from recipes, menus_recipes
            where menus_recipes.recipe_id = recipes.id
	          and menus_recipes.menus_id = 1';

        $checkTables = "SELECT name FROM sqlite_master WHERE type='table'";
        $checkIngredientsTypes = "select * from ingredients_types";

		echo 'executing drop strings...<br />';
        $db->ExecuteQueries($dropStrings, true);
		echo '<strong>drop strings executed!</strong><br /><br />';

		echo 'executing create strings...<br />';
        $db->ExecuteQueries($createStrings);
		echo '<strong>create strings executed!</strong><br /><br />';

		echo 'executing measure strings...<br />';
        $db->ExecuteQueries($measureStrings, true);
		echo '<strong>measure strings executed!</strong><br /><br />';

		echo 'executing menu strings...<br />';
		$db->ExecuteQueries($initialMenuStrings, true);
		echo '<strong>menu strings executed!</strong><br /><br />';

		echo 'executing type strings...<br />';
		$db->ExecuteQueries($initialTypeStrings, true);
		echo '<strong>type strings executed!</strong><br /><br />';

		echo 'executing ingredient strings...<br />';
		$db->ExecuteQueries($initialIngredientStrings, true);
		echo '<strong>ingredient strings executed!</strong><br /><br />';

		echo 'executing recipe strings...<br />';
		$db->ExecuteQueries($initialRecipeStrings, true);
		echo '<strong>recipe strings executed!</strong><br /><br />';

		echo 'executing direction strings...<br />';
		$db->ExecuteQueries($initialDirectionStrings, true);
		echo '<strong>direction strings executed!</strong><br /><br />';

		echo 'executing menu-recipe strings...<br />';
		$db->ExecuteQueries($initialMenusRecipesStrings, true);
		echo '<strong>menu-recipe strings executed!</strong><br /><br />';

		echo 'executing recipe-ingredient strings...<br />';
		$db->ExecuteQueries($initialRecipesIngredientsStrings, true);
		echo '<strong>recipe-ingredient strings executed!</strong><br /><br />';

		echo 'executing ingredient-type strings...<br />';
		$db->ExecuteQueries($initialIngredientsTypesStrings, true);
		echo '<strong>ingredient-type strings executed!</strong><br /><br />';

		echo '<strong>checking recipe-ingredients...</strong><br />';
        $recipesIngredients = $db->ExecuteArrayQuery($checkRecipeIngredients, true);
        echo '<pre>';
        print_r($recipesIngredients);
        echo '</pre>';

		echo '<strong>checking tables...</strong><br />';
		$tables = $db->ExecuteArrayQuery($checkTables);
		echo '<pre>';
		print_r($tables);
		echo '</pre>';

        echo '<pre><strong>Initialized recipe database.</strong></pre>';
	}
	else {
		echo '<pre>';
		echo 'Could not find or initialize recipe database';
		echo '</pre>';
	}
	
	flush();
	
	function printArray($a) {
		echo '<pre>';
		print_r($a);
		echo '</pre>';
	}
	
	function printIngredients($ingredients) {
		echo '<pre>';
		foreach($ingredients as $ingredient) {
			$amount = $ingredient['amount'] . ' ' . $ingredient['amount_type'];
			$name = $ingredient['name'];

			echo $amount . ' ' . $name . PHP_EOL;
		}
		echo '</pre>';
	}
	
	function printDirections($directions) {
		echo '<pre>';
		foreach($directions as $direction) {
			$directionText = $direction['step'] . '. ' . $direction['direction'];

			echo $directionText . PHP_EOL;
		}
		echo '</pre>';
	}
	
	function printMenuRecipes($menuRecipes) {
		echo '<pre>';
		foreach($menuRecipes as $menuRecipe) {
			$recipe = $menuRecipe['menu_name'] . ': ' . $menuRecipe['recipe_name'];

			echo $recipe . PHP_EOL;
		}
		echo '</pre>';
	}
?>
