<?php

declare(strict_types=1);

namespace App\Service;

use Nette;
use Nette\Utils\Paginator;
use App\Repository\UserRepository;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function paginate(int $page): Paginator
    {
        $usersCount = $this->userRepository->getCount();
        $paginator = new Nette\Utils\Paginator;
        $paginator->setItemCount($usersCount);
        $paginator->setItemsPerPage(5);
        $paginator->setPage($page);

        return $paginator;
    }
}