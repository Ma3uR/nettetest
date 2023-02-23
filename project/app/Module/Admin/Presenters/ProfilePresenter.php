<?php

declare(strict_types=1);

namespace App\Module\Admin\Presenters;

use App\Repository\UserRepository;
use App\Service\UserService;
use Nette;
use Nette\Database\Explorer;

final class ProfilePresenter extends Nette\Application\UI\Presenter
{
    private Explorer $database;
    private UserRepository $userRepository;
    private UserService $userService;

    public function __construct(
        Explorer $database,
        UserRepository $userRepository,
        UserService $userService
    )
    {
        $this->database = $database;
        $this->userRepository = $userRepository;
        $this->userService = $userService;
    }

    public function renderShow(): void {
        $this->template->user = $this->userRepository->get($this->getUser()->getId());;
    }

    public function renderList(int $page = 1): void {
        $paginator = $this->userService->paginate($page);
        $users = $this->template->users = $this->userRepository->getAll($paginator->getLength(), $paginator->getOffset());

        $this->template->users = $users;
        $this->template->paginator = $paginator;
    }
}