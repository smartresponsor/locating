# E2-11 — Provider contract cluster namespace retirement

Retires the remaining bounded `Smartresponsor\Contract\Locator\Provider*` cluster by replacing it with `App\Bridge\Legacy\Contract\Location\...` bridge contracts and rewiring the live integration/provider registry surface onto them.
