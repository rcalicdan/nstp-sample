<?php

declare(strict_types=1);

/**
 * Service class with class-level type alias and NO constructor
 *
 * @phpstan-type ImportStats array{imported: int, updated: int, skipped: int}
 */
class CsvServiceFixture
{
    public function process(): void
    {
        /** @var ImportStats $stats */
        $stats = ['imported' => 10, 'updated' => 5, 'skipped' => 0];

        echo "   ✅ Valid ImportStats assigned to \$stats successfully!\n";

        // Invalid assignment: 'imported' key has string 'invalid' instead of int
        $stats = ['imported' => 'invalid', 'updated' => 5, 'skipped' => 0];
    }
}

echo "=== Testing Inline @var Type Alias Resolution in Isolation ===\n\n";

$service = new CsvServiceFixture();

try {
    $service->process();
    echo "   ❌ Failed to catch invalid ImportStats assignment!\n";
} catch (TypeError $e) {
    echo "   ✅ CAUGHT EXPECTED ERROR: " . $e->getMessage() . "\n";
}

echo "\n🎉 TYPE ALIAS VARIABLE TEST COMPLETED!\n";