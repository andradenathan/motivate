<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Recipe;
use App\User;
use App\Challenge;
use Auth;
use App\Http\Requests\RecipeRequest;
use DB;

class RecipeController extends Controller
{
    //Create
    public function postRecipe(RecipeRequest $request){
        $user = Auth::user();
        $newRecipe = new Recipe;
        $newRecipe->createRecipe($request);
        $newRecipe->setUser($user->id);
        $challenge_id = Challenge::where('title',$request->challenge)->get()[0]->id;
        $newRecipe->setChallenge($challenge_id);
        return response()->json(['recipe' => $newRecipe], 200);
    }

    // Read
    public function getRecipe($id) {
        $recipe = Recipe::findOrFail($id);
        return response()->json(['recipe' => $recipe], 200);
    }

    public function getLikes($recipe_id){
        $recipe = Recipe::findOrFail($recipe_id);
        $likes = $recipe->likes()->count();
        return response()->json(['likes' => $likes], 200);
    }

    //Update
    public function updateRecipe(RecipeRequest $request, $id) {
        $user = Auth::user();
        $recipe = Recipe::findOrFail($id);
        $recipe->updateRecipe($request);
        return response()->json(['recipe' => $recipe], 200);
    }

    //Delete
    public function deleteRecipe($id) {
        $user = Auth::user();
        Recipe::destroy($id);
        return response()->json(['Recipe deleted'], 200);
    }

    // Relação com likes
    public function likeRecipe($recipe_id){
        $user = Auth::user();
        $recipes = Recipe::findOrFail($recipe_id);
        $status = DB::table('likes')->where('user_id', $user->id)->where('recipe_id', $recipes->id)->count()>0;
        if ($status){
            $user->likeDown($recipe_id);
            return response()->json(['dislike' => "You no longer like the recipe ". $recipes->title], 200);
        } else{
            $user->likeUp($recipe_id);
            return response()->json(['like' => "You liked the recipe ". $recipes->title], 200);
        }
    }


}
