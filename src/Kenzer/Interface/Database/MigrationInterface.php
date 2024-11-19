<?php

declare(strict_types=1);

namespace Kenzer\Interface\Database;

interface MigrationInterface
{
    public function up(): void;

    public function down(): void;
}
