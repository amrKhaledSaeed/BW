<?php

namespace App\Http\Controllers\V1\Api\Admin;

use App\Http\Resources\GeneralCollection;
use Exception;
use App\helpers;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\SubCategoryRequest;
use App\Http\RepositoryInterface\RepositoryCategoryInterface;

class CategoryController extends Controller
{
    use helpers;
    private $categoryInterface;
    public function __construct(RepositoryCategoryInterface $categoryInterface)
    {
        $this->categoryInterface = $categoryInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       
        $category = $this->categoryInterface->index();
        if( $category)
        {
            return $this->apiResponse(new GeneralCollection($category, CategoryResource::class));
        }else{

            return $this->apiResponse(['message' => 'not found']);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {

try{
    $isUtharize = $this->authorize('create-transaction-categories');
}catch (Exception $e){
    return $this->apiResponse($e->getMessage());
}
        $category = $this->categoryInterface->store($request);
        if( $category)
        {
            return $this->apiResponse(['data' => new CategoryResource($category)]);
        }else{

            return $this->apiResponse(['message' => 'failed created']);
        }

    }
    /**
     * Store a newly created resource in storage.
     */
    public function storeSubCategory(SubCategoryRequest $request)
    {

try{
    $isUtharize = $this->authorize('create-transaction-subcategorie');
}catch (Exception $e){
    return $this->apiResponse($e->getMessage());
}
        $subCategory = $this->categoryInterface->storeSubCategory($request);
        if( $subCategory)
        {
            return $this->apiResponse(['data' => new CategoryResource($subCategory)]);
        }else{

            return $this->apiResponse(['message' => 'failed created']);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
