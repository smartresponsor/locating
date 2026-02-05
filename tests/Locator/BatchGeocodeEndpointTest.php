<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use Smartresponsor\Layer\Locator\{BatchGeocodeEndpoint, ResultCache};
final class BatchGeocodeEndpointTest {
    public function testHandle(): void {
        $ep = new BatchGeocodeEndpoint(new ResultCache());
        $out = $ep->handle([['text'=>'123 Main St'], ['text'=>'1600 Amphitheatre Pkwy']]);
        assert(count($out)===2 && isset($out[0]['lat']) && isset($out[1]['lon']));
        $out2 = $ep->handle([['text'=>'123 Main St']]);
        assert($out2[0]['text'] === $out[0]['text']); // cached normalize
    }
}
