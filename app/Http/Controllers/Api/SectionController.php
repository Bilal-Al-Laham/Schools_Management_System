<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoresectionRequest;
use App\Http\Requests\UpdatesectionRequest;
use App\Http\Responses\Response;
use App\Models\section;
use App\Services\SectionService;
use App\Services\SectionServiceInterface;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    protected SectionService $sectionService;
    
    public function __construct(SectionServiceInterface $sectionServiceInteraface) {
        $this->sectionService = $sectionServiceInteraface;
    }

    public function index(Request $request)
    {
            return $this->sectionService->allSections($request);
    }
    
    public function store(StoresectionRequest $request)
    {
        // $this->authorize('create', section::class);
        $ValidatedData = $request->validated();
        $section = $this->sectionService->createSection($ValidatedData);
        $message = "section created successfully";
        return Response::Success($section, $message, 201);
    }

    public function show(section $section)
    {
        $sectionItem = $this->sectionService->indexOneSection($section);
        $message = "$section->name retrived successfully";
        return Response::Success($sectionItem, $message, 200);
    }

    public function update(UpdatesectionRequest $request, section $section)
    {
        // $this->authorize('update', $section);
        $validatedData = $request->validated();
        $sectionItem = $this->sectionService->updateSection($validatedData, $section);
        $message = "{$section->name} updated successfully";
        return Response::Success($sectionItem, $message, 200);
    }

    public function destroy(section $section)
    {
        // $this->authorize('delete', $section);
        $sectionItem = $this->sectionService->deleteSection($section);
        $message = "Section deleted successfully";
        return Response::Success(null, $message, 200);
    }
}
