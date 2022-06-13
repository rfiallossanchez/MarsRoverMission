<?php

declare(strict_types=1);

namespace App\Rover\Infrastructure\Controller;

use App\Rover\Application\Model\Command\SetRoverInitialPositionCommand;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Domain\Bus\Query\QueryBus;
use App\Shared\Infrastructure\Controller\BaseController;
use DomainException;
use Psr\Log\LoggerInterface;
use Throwable;

final class SetRoverInitialPositionController extends BaseController
{
    public function __construct(
        protected QueryBus $queryBus,
        protected CommandBus $commandBus,
        protected LoggerInterface $logger
    )
    {
        parent::__construct($queryBus, $commandBus, $logger);
    }

    public function __invoke(
        int $xAxis,
        int $yAxis,
    ): void
    {
        try {
            $this->commandBus->dispatch(
                new SetRoverInitialPositionCommand($xAxis, $yAxis)
            );
        } catch (DomainException $domainException) {
            $this->logger->error(
                'A domain exception was launched when trying to set rover initial position.',
                [
                    'message' => $domainException->getMessage(),
                    'exception' => $domainException,
                ]
            );
        } catch (Throwable $throwable) {
            $this->logger->error(
                'An error happened when trying to set rover initial position',
                [
                    'message' => $throwable->getMessage(),
                    'exception' => $throwable,
                ]
            );
        }
    }
}