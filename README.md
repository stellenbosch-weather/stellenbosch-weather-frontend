# Stellenbosch Weather

Canonical GitHub repository: `git@github.com:stellenbosch-weather/stellenbosch-weather-frontend.git`. The repository name is historical; this checkout contains the complete frontend, API, and collectors.

One checkout contains the public website and PHP API, scheduled weather collectors, textures, icons, and the xplanet executable. Apache serves only `public/`. The `app/`, `services/`, `resources/`, `bin/`, and `deploy/` directories must remain outside the document root.

## Layout and local setup

`public/api/` holds the existing URL entry points. `app/` holds shared PHP code. `services/` holds importers and shell wrappers. `resources/` holds image textures. `bin/xplanet` is the bundled Linux x86-64 executable. `migrations/` contains historical SQL changes. `settings.conf` and `var/` are ignored local state in the checkout root.

For local development, copy `settings.conf.template` to `settings.conf`, fill in the existing database and Sonbesie settings, and set `[paths] data_dumps` to the historical ZIP directory. Create `var/log`, `var/cache`, `var/run`, and `var/tmp` with permissions appropriate for the local PHP and collector users. Do not put credentials in `public/` or Git.

Scheduled HTML collectors use Python 2.7 or Python 3 with a working MySQLdb driver. The wrappers use `${WEATHER_PYTHON:-python}`. The verified production interpreter is `/usr/bin/python` (Python 2.7.5 with MySQLdb), as shown in `deploy/crontab.example`. The PakBus and SAURAN utilities have separate optional dependencies and are not needed for the scheduled HTML collectors. The older `*Dat.py` utilities retain historical absolute input paths and are manual, unsupported tools, not part of the scheduled deployment.

## Deployment

The intended checkout is `/home/weather/weather-site`. If it was initially cloned from a local Git bundle, replace that temporary `origin` with the canonical GitHub remote using `git remote set-url origin git@github.com:stellenbosch-weather/stellenbosch-weather-frontend.git`, then `git fetch origin`. Keep the existing private `/home/weather/settings.conf` and `/home/weather/data-dumps`. Create `/home/weather/var/{log,cache,run,tmp}`. Link `weather-site/settings.conf` to `../settings.conf` and `weather-site/var` to `../var`. Configure Apache's served path as `/home/weather/public_html`, linked to `weather-site/public`, only after confirming symlink handling, `open_basedir`, and access under the web PHP user. Do not guess ownership or use world-writable permissions.

Use `deploy/crontab.example` to replace only the three weather import entries, retaining unrelated jobs. Set `WEATHER_PYTHON` to the verified interpreter, for example by adding an assignment at the top of the user's crontab. Confirm the server's cron timezone keeps the intended Africa/Johannesburg schedule.

Before cutover, compare production files, hidden files, and revisions with this checkout. The observed PHP FPM setup has no OPcache but has a 120-second realpath cache. Immediately after the first `public_html` directory-to-symlink change, some API image routes and current data briefly returned HTTP 500; repeated requests later returned 200 without code changes. Arrange a PHP FPM reload with the hosting administrator for a clean switch, or plan for that cache interval as a maintenance period and verify every API route before declaring the deployment healthy. Back up the existing served directory and crontab. Pause old weather cron entries and wait for active imports to finish before switching the served path and enabling new entries. Smoke-test current, history, forecast, downloads, moon, and earth endpoints through Apache. Confirm imports advance database timestamps and logs appear in `/home/weather/var/log`. On failure, pause the new entries, wait for active jobs, restore the old served path and cron entries, and verify recovery. Leave old checkouts intact until verification is complete.

For later updates, pause collectors, check out one tested tag, account for opcode cache behavior, smoke-test, and resume collectors. An in-place Git checkout changes files over time, so use a short maintenance window. Rollback restores code and scheduling; it does not rewind data.

## Checks and maintenance

Run `python3 -m unittest discover -s services/sonbesie/tests -q` for the collector regression suite. Run `php -l` on PHP files using the confirmed server-compatible PHP version. Verify `bin/xplanet --version` on the server and test execution through PHP. Confirm a plain clone has no Git submodules or gitlinks. Check `git status --short` after normal operation; it should be clean.

Keep logs outside Git. `deploy/logrotate.conf` rotates `/home/weather/var/log/*.log` daily, retains 14 compressed copies, and uses `copytruncate` for cron redirection. The example crontab runs `/usr/sbin/logrotate` as `weather` with state in `/home/weather/var/run/logrotate.status`. Review old files in `/home/weather/services` separately before deleting or archiving them. The two rain migrations in `migrations/` are not repeatable; inspect the database columns before applying either, and do not run migrations during the repository cutover.

See `deploy/import-history.md` for source revisions and the import method.

The observed server baseline is RHEL 7.9, Apache 2.4.34, PHP 5.4.16 CLI, PHP 7.3.33 FPM under `weather`, Python 2.7.5, and Git 1.8.3.1. Web PHP can read the existing private configuration and dumps and execute the current xplanet binary; symlink serving and the new checkout context remain to be checked.
