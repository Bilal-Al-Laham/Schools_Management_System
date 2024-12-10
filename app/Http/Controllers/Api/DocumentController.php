<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Http\Responses\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $documents = Document::with('subject')->get();
        return Response::Success($documents, 'These are all documents');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,png,jpg,jpeg',
            'document_name' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id'
        ]);

        $file = $request->file('file');
        $path = $file->storeAs('document', 'public');


        $document = Document::create([
            'subject_id' => $request->input('subject_id'),
            'document_name' => $request->file('file'),
            'document_path' => $path
        ]);

        return response()->json([
            'message' => 'File uploaded successfully!',
            'document' => $document
        ], 201);
    }

    public function downloadDocument($id){
        $document = Document::findOrFail($id);
        return Storage::download($document->document_path, $document->document_name);
    }

    public function getDoucumentBySubject($subjectId){
        $documents = Document::where('subject_id', $subjectId)->get();
        return response()->json($documents);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocumentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentRequest $request, Document $document)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        //
    }
}
