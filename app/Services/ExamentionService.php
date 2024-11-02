<?php

namespace App\Services;

use App\Repositories\ExamentionRepository;
use App\Repositories\ExamentionRepositoryInterface;


class ExamentionService
{
    protected ExamentionRepositoryInterface $examentionRepository;
    public function __construct(ExamentionRepositoryInterface $examentionRepository)
    {
        $this->examentionRepository = $examentionRepository;
    }

    public function fetchExamentions()
    {
        return $this->examentionRepository->getExametions();
    }

    public function fetchExamention($id)
    {
        return $this->examentionRepository->showEXametin($id);
    }

    public function createExamenation(array $data)
    {
        return $this->examentionRepository->addExametion($data);
    }

    public function updateExamention(array $data , $id){
        return $this->examentionRepository->updateExamention($data,$id);
    }
}
