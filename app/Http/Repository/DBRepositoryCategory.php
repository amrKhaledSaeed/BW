<?php
namespace App\Http\Repository;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\RepositoryInterface\RepositoryCategoryInterface;
use App\Models\Category;
use App\Models\SubCategory;

class DBRepositoryCategory implements RepositoryCategoryInterface
{
    private $categoryModel;
    private $subCategoryModel;
    public function __construct(Category $categoryModel,SubCategory $subCategoryModel)
    {
        $this->categoryModel = $categoryModel;
        $this->subCategoryModel = $subCategoryModel;
    }
    public function index()
    {
        return $this->categoryModel->with('subCategory')->get();
    }


    public function store($request)
    {
        $category = $this->categoryModel->create($request->all());
        return $category;
    }
    public function storeSubCategory($request)
    {
        $subCategory = $this->subCategoryModel->create($request->all());
        return $subCategory;
    }
}

