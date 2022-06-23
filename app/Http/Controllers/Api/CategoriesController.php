<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Category::all();
        // return Category::when($request->query('parent_id'), function($query, $value) {
        //     $query->where('parent_id', '=', $value);
        // })
        // ->paginate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // if (!$request->user()->tokenCan('categories.create')) {
        //     abort(403, 'Not allowed');
        // }



        $category = Category::create($request->all());
        $category->refresh(); // SELECT * FROM categories WHERE id = ?
        return new JsonResponse($category, 201, [
            'x-application-name' => config('app.name'),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Category::with('child_cat')->findOrFail($id);
        // return Category::select([
        //     'id', 'title'
        // ])->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {

        $category = Category::findOrFail($id);
        $category ->update($request->all());
        return Response::json([
            'message' => 'Category update',
            'category' => $category,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category ->delete();
        return Response::json([
            'message' => 'Category deleted',
        ]);
    }
}
