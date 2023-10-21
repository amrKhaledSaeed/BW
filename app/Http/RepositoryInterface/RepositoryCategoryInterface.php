<?php
namespace App\Http\RepositoryInterface;

interface RepositoryCategoryInterface{
    public function index();
    public function store($request);
    public function storeSubCategory($request);
}
