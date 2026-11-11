# Stellenbosch Weather

One checkout contains the public website and PHP API, scheduled weather collectors, textures, icons, and the xplanet executable. Apache serves only `public/`. The `app/`, `services/`, `resources/`, `bin/`, and `deploy/` directories must remain outside the document root.

## Layout and local setup

`public/api/` holds the existing URL entry points. `app/` holds shared PHP code. `services/` holds importers and shell wrappers. `resources/` holds image textures. `bin/xplanet` is the bundled Linux x86-64 executable. `migrations/` contains historical SQL changes. `settings.conf` and `var/` are ignored local state in the checkout root.

For local development, copy `settings.conf.template` to `settings.conf`, fill in the existing database and Sonbesie settings, and set `[paths] data_dumps` to the historical ZIP directory. Create `var/log`, `var/cache`, `var/run`, and `var/tmp` with permissions appropriate for the local PHP and collector users. Do not put credentials in `public/` or Git.

Scheduled HTML collectors use Python 2.7 or Python 3 with a working MySQLdb driver. The wrappers use `${WEATHER_PYTHON:-python}`. The production value must be set to the verified interpreter in the crontab or environment. The PakBus and SAURAN utilities have separate optional dependencies and are not needed for the scheduled HTML collectors. The older `*Dat.py` utilities retain historical absolute input paths and are manual, unsupported tools, not part of the scheduled deployment.

## Deployment

The intended checkout is `/home/weather/weather-site`. Keep the existing private `/home/weather/settings.conf` and `/home/weather/data-dumps`. Create `/home/weather/var/{log,cache,run,tmp}`. Link `weather-site/settings.conf` to `../settings.conf` and `weather-site/var` to `../var`. Configure Apache's served path as `/home/weather/public_html`, linked to `weather-site/public`, only after confirming symlink handling, `open_basedir`, and access under the web PHP user. Do not guess ownership or use world-writable permissions.

Use `deploy/crontab.example` to replace only the three weather import entries, retaining unrelated jobs. Set `WEATHER_PYTHON` to the verified interpreter, for example by adding an assignment at the top of the user's crontab. Confirm the server's cron timezone keeps the intended Africa/Johannesburg schedule.

Before cutover, compare production files, hidden files, and revisions with this checkout. Back up the existing served directory and crontab. Pause old weather cron entries and wait for active imports to finish before switching the served path and enabling new entries. Smoke-test current, history, forecast, downloads, moon, and earth endpoints through Apache. Confirm imports advance database timestamps and logs appear in `/home/weather/var/log`. On failure, pause the new entries, wait for active jobs, restore the old served path and cron entries, and verify recovery. Leave old checkouts intact until verification is complete.

For later updates, pause collectors, check out one tested tag, account for opcode cache behavior, smoke-test, and resume collectors. An in-place Git checkout changes files over time, so use a short maintenance window. Rollback restores code and scheduling; it does not rewind data.

## Checks and maintenance

Run `python3 -m unittest discover -s services/sonbesie/tests -q` for the collector regression suite. Run `php -l` on PHP files using the confirmed server-compatible PHP version. Verify `bin/xplanet --version` on the server and test execution through PHP. Confirm a plain clone has no Git submodules or gitlinks. Check `git status --short` after normal operation; it should be clean.

Keep logs outside Git and configure retention with the server's available logrotate or a reviewed cron cleanup. Review files in `/home/weather/var/log` before deleting old output. The two rain migrations in `migrations/` are not repeatable; inspect the database columns before applying either, and do not run migrations during the repository cutover.

See `deploy/import-history.md` for source revisions and `RESTRUCTURING_PLAN.md` in the parent workspace for the full migration plan and server diagnostic commands.
