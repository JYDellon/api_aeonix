<?php

// namespace App\Service;

// use App\Repository\ProspectRepository;

// class ProspectService
// {
//     private ProspectRepository $prospectRepository;

//     public function __construct(ProspectRepository $prospectRepository)
//     {
//         $this->prospectRepository = $prospectRepository;
//     }

//     /**
//      * Récupère les emails des prospects par leurs IDs.
//      *
//      * @param array $ids
//      * @return array
//      */
//     public function getEmailsByIds(array $ids): array
//     {
//         $emails = $this->prospectRepository->findEmailsByIds($ids);

//         // Retourner un tableau simple des emails
//         return array_column($emails, 'email');
//     }
// }












namespace App\Service;

use App\Repository\ProspectRepository;

class ProspectService
{
    private ProspectRepository $repository;

    public function __construct(ProspectRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getEmailsByIds(array $ids): array
    {
        $results = $this->repository->findEmailsByIds($ids);
        return array_column($results, 'email'); // Retourne un tableau plat des emails
    }
}