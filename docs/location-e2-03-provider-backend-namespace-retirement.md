# E2-03 / Provider backend namespace retirement

Retired bounded Smartresponsor infrastructure-interface cluster for live provider backend edge:
- AddressSuggestProviderInterface
- ReverseHttpClientInterface
- MetricSnapshotProviderInterface

Replaced with App-owned legacy bridge contracts under `App\Bridge\Legacy\Provider\Location` and rewired active backend adapters and legacy implementations/tests.
