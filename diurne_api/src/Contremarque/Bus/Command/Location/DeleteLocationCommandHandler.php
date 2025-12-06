<?php

declare(strict_types=1);

namespace App\Contremarque\Bus\Command\Location;

use App\Common\Bus\Command\CommandHandler;
use App\Common\Exception\ValidationException;
use App\Contremarque\Repository\LocationRepository;
use App\Contremarque\Repository\ProjectDiRepository;
use App\Contremarque\Repository\CarpetDesignOrderRepository;
use App\Contremarque\Repository\QuoteDetailRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception as DBALException;

class DeleteLocationCommandHandler implements CommandHandler
{
    private readonly Connection $connection;

    public function __construct(
        private readonly LocationRepository $locationRepository,
        private readonly ProjectDiRepository $projectDiRepository,
        private readonly CarpetDesignOrderRepository $carpetDesignOrderRepository,
        private readonly QuoteDetailRepository $quoteDetailRepository
    ) {
        // Get the DBAL Connection from the LocationRepository's EntityManager
        $this->connection = $this->locationRepository->getEntityManager()->getConnection();
    }

    public function __invoke(DeleteLocationCommand $command): void
    {
        $location = $this->locationRepository->find($command->getLocationId());

        if (!$location) {
            throw new ValidationException(['Location not found']);
        }

        $violations = [];

        // Optional initial validation (can be removed if you want to force delete regardless)
        if ($this->carpetDesignOrderRepository->hasAssociatedCarpetDesignOrders($location)) {
            $violations[] = 'Cannot delete location because it is associated with one or more CarpetDesignOrders';
        }

        if ($this->quoteDetailRepository->hasAssociatedQuoteDetails($location)) {
            $violations[] = 'Cannot delete location because it is associated with one or more QuoteDetails';
        }

        if (!empty($violations)) {
            // Optionally throw here if you want to block deletion based on application logic
            // throw new ValidationException($violations);
        }

        try {
            // Start a transaction for atomicity
            $this->connection->beginTransaction();

            // Disable foreign key checks
            $this->connection->executeStatement('SET FOREIGN_KEY_CHECKS = 0');

            // Detach dependencies by setting foreign keys to NULL
            $detachQueries = [
                // CarpetDesignOrder
                'UPDATE carpet_design_order SET location_id = NULL WHERE location_id = :locationId',
                // QuoteDetail
                'UPDATE quote_detail SET location_id = NULL WHERE location_id = :locationId',
                // CarpetReference (delete instead of detach due to orphanRemoval intent)
                'DELETE FROM carpet_reference WHERE location_id = :locationId',
                // Contremarque
                'UPDATE location SET contremarque_id = NULL WHERE id = :locationId',
            ];

            foreach ($detachQueries as $sql) {
                $this->connection->executeStatement($sql, ['locationId' => $command->getLocationId()]);
            }

            // Delete the Location directly via SQL
            $deleteSql = 'DELETE FROM location WHERE id = :locationId';
            $affectedRows = $this->connection->executeStatement($deleteSql, ['locationId' => $command->getLocationId()]);

            if ($affectedRows === 0) {
                $this->connection->executeStatement('SET FOREIGN_KEY_CHECKS = 1');
                $this->connection->rollBack();
                throw new ValidationException(['Failed to delete location: No rows affected']);
            }

            // Re-enable foreign key checks
            $this->connection->executeStatement('SET FOREIGN_KEY_CHECKS = 1');

            $this->connection->commit();
        } catch (DBALException $e) {
            // Ensure foreign key checks are re-enabled even on failure
            $this->connection->executeStatement('SET FOREIGN_KEY_CHECKS = 1');
            $this->connection->rollBack();
            throw new ValidationException([
                'Failed to force delete location due to database error: ' . $e->getMessage()
            ]);
        } catch (\Exception $e) {
            // Ensure foreign key checks are re-enabled even on failure
            $this->connection->executeStatement('SET FOREIGN_KEY_CHECKS = 1');
            $this->connection->rollBack();
            throw new ValidationException([
                'An error occurred while force deleting the location: ' . $e->getMessage()
            ]);
        }
    }
}
