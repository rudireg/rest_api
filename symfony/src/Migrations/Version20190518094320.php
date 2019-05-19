<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190518094320 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // list of tables
        $tables = ['images', 'logs'];
        // create
        foreach($tables AS $table) {
            $commands = explode(';', file_get_contents(__DIR__ . "/$table.sql"));
            foreach ($commands as $command) {
                $command = trim($command);
                if (empty($command)) continue;
                $this->addSql($command);
            };
        }
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
