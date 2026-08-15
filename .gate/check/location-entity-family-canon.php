<?php

declare(strict_types=1);

/**
 * Locating LC-05 entity-family canon gate wrapper.
 *
 * Delegates to the report-only mapper that prepares the next safe touched-file
 * Entity normalization wave without moving or deleting runtime classes.
 */

$tool = dirname(__DIR__, 2) . '/tools/canon/location-entity-family-map.php';

if (!is_file($tool)) {
    fwrite(STDERR, "Missing LC-05 entity-family map tool: {$tool}\n");
    exit(2);
}

require $tool;
