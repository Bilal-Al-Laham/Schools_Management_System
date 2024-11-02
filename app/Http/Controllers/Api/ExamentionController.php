<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Examenations\StoreExamenationRequest;
use App\Http\Requests\Examenations\UpdateExamentionRequest;
use App\Models\Examention;
use App\Http\Requests\UpdateExamResultRequest;
use App\Http\Resources\ExamenationResource;
use App\Http\Responses\Response;
use App\Services\ExamentionService;
use Exception;

class ExamentionController extends Controller
{
    protected ExamentionService $examention;

    public function __construct(ExamentionService $examention)
    {
        $this->examention = $examention;
    }
    public function index()
    {
        try {
            $examentions = $this->examention->fetchExamentions();
            $message = 'there are all examentions';
            return Response::Success($examentions, $message, 200);
        } catch (Exception $e) {
            $php_errormsg = 'error when retrive EXamentations :' . $e->getFile() . '  line: ' . $e->getLine() . ' ' . $e->getMessage();
            return Response::Error($php_errormsg);
        }
    }


    public function store(StoreExamenationRequest $request)
    {
        try {
            $examention =   $this->examention->createExamenation($request->validated());
            $message = 'Examenation created successfully! ';
            return Response::Success($examention, $message, 200);
        } catch (Exception $e) {
            $php_errormsg = 'error when create Examentations :' . $e->getFile() . ' line: ' . $e->getLine() . ' ' . $e->getMessage();
            return Response::Error($php_errormsg);
        }
    }


    public function show($id)
    {
        try {
            $examention = $this->examention->fetchExamention($id);
            $message = 'there are all examentions';
            return Response::Success($examention, $message, 200);
        } catch (Exception $e) {
            $php_errormsg = 'error when create Examentation :' . $e->getFile() . $e->getLine();
            return Response::Error($php_errormsg);
        }
    }



    public function update(UpdateExamentionRequest $request, $id)
    {
        try {
            $examention = $this->examention->updateExamention($request->validated(), $id);
            $message = 'there are all examention !';
            return Response::Success($examention, $message, 200);
        } catch (Exception $e) {
            $php_errormsg = 'error when update examention . ' . 'in file: ' . $e->getFile() . 'in line: ' . $e->getLine() . ' ' . $e->getMessage();
            return Response::Error($php_errormsg);
        }
    }


    public function destroy(Examention $examention)
    {
        //
    }
}
