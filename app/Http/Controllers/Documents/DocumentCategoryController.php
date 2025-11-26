<?php

declare(strict_types=1);

namespace App\Http\Controllers\Documents;

use App\Domain\Documents\Actions\DocumentCategory\CreateCategory;
use App\Domain\Documents\Actions\DocumentCategory\GetTree;
use App\Domain\Documents\Requests\DocumentCategoryRequest;
use App\Http\Controllers\Controller;
use App\Domain\Documents\Models\DocumentCategory;
use Inertia\Inertia;

class DocumentCategoryController extends Controller
{
    public function index(GetTree $getTree)
    {
        $this->authorize('viewAny', DocumentCategory::class);

        return Inertia::render(
            'Documents/Categories/Index',
            [
                'categories' => $getTree->asTreeNodes(),
            ]
        );
    }

    public function store(DocumentCategoryRequest $request, CreateCategory $createCategory)
    {
        $this->authorize('create', DocumentCategory::class);

        $createCategory->execute($request->validated());
    }

    public function show(DocumentCategory $category)
    {
        $this->authorize('view', $category);
    }

    public function update(DocumentCategoryRequest $request, DocumentCategory $category)
    {
        $this->authorize('update', $category);

        $category->update($request->validated());
    }

    public function destroy(DocumentCategory $category)
    {
        $this->authorize('delete', $category);
        return $this->destroyModel($category);
    }
}
