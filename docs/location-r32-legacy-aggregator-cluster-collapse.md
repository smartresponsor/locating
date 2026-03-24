# R32 — Legacy aggregator cluster collapse

This wave removes an orphaned Locator aggregation cluster that no longer participates in the active App-based runtime graph.

Removed classes:
- Smartresponsor\Service\Locator\Aggregator
- Smartresponsor\Service\Locator\ForwardAggregator
- Smartresponsor\Service\Locator\ReverseAggregator
- Smartresponsor\Service\Locator\AdaptiveRouter
- Smartresponsor\Service\Locator\AddressSuggestMetricDecorator

Removed interfaces:
- Smartresponsor\ServiceInterface\Locator\AggregatorInterface
- Smartresponsor\ServiceInterface\Locator\ForwardAggregatorInterface
- Smartresponsor\ServiceInterface\Locator\ReverseAggregatorInterface
- Smartresponsor\ServiceInterface\Locator\AdaptiveRouterInterface
- Smartresponsor\ServiceInterface\Locator\AddressSuggestMetricDecoratorInterface

Reference check on src/, config/, tests/ showed no live operational references outside the cluster itself.
