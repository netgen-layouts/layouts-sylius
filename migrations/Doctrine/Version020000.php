<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Migrations\Doctrine;

use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

use function is_numeric;

/**
 * Renames the taxon rule target types stored by 1.4 to their 2.0 names: `sylius_single_taxon`
 * (exact match) became `sylius_taxon`, and `sylius_taxon` (subtree match) became
 * `sylius_taxon_tree`. The statement order is load-bearing — swapped, the second rename would
 * feed the first one's input. The guard skips databases already on the 2.0 names, where
 * `sylius_taxon` rows are renamed exact-match targets that the first statement would corrupt.
 */
final class Version020000 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->abortIf(!$this->connection->getDatabasePlatform() instanceof MySQLPlatform, 'Migration can only be executed safely on MySQL.');

        $legacyRowCount = $this->connection->fetchOne("SELECT COUNT(*) FROM nglayouts_rule_target WHERE type = 'sylius_single_taxon'");
        $this->skipIf(!is_numeric($legacyRowCount) || (int) $legacyRowCount === 0, 'Taxon rule targets already use their 2.0 type names.');

        $this->addSql("UPDATE nglayouts_rule_target SET type = 'sylius_taxon_tree' WHERE type = 'sylius_taxon'");
        $this->addSql("UPDATE nglayouts_rule_target SET type = 'sylius_taxon' WHERE type = 'sylius_single_taxon'");
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(!$this->connection->getDatabasePlatform() instanceof MySQLPlatform, 'Migration can only be executed safely on MySQL.');

        $this->addSql("UPDATE nglayouts_rule_target SET type = 'sylius_single_taxon' WHERE type = 'sylius_taxon'");
        $this->addSql("UPDATE nglayouts_rule_target SET type = 'sylius_taxon' WHERE type = 'sylius_taxon_tree'");
    }
}
