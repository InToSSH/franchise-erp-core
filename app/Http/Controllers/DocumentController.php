<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Documents\Models\Document;
use App\Domain\Documents\Requests\DocumentRequest;
use App\Domain\Documents\Resources\DocumentResource;

class DocumentController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Document::class);

        return DocumentResource::collection(Document::all());
    }

    public function store(DocumentRequest $request)
    {
        $this->authorize('create', Document::class);

        return new DocumentResource(Document::create($request->validated()));
    }

    public function show(Document $document)
    {
        $this->authorize('view', $document);

        return new DocumentResource($document);
    }

    public function update(DocumentRequest $request, Document $document)
    {
        $this->authorize('update', $document);

        $document->update($request->validated());

        return new DocumentResource($document);
    }

    public function destroy(Document $document)
    {
        $this->authorize('delete', $document);

        $document->delete();

        return response()->json();
    }
}
