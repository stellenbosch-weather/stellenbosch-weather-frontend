# Source histories

The first-parent history of `main` begins with the older 2008–2025 site at `https://github.com/stellenbosch-weather/stellenbosch-weather-2025`. Its 19 commits were rewritten to redact credential literals before joining this public repository; the archived private repository retains the unmodified originals. The rewritten site's final commit is `4e6a225192f3a0c69ddaa937eeb9b92d3fbbec3f`.

Merge `1d3ae0c` imports frontend revision `cec02630b706797a69c5763a69947ccf3b6d1191` from `https://github.com/stellenbosch-weather/stellenbosch-weather-frontend` as a second parent and switches the tree to the frontend. Merges `3ca4ff9` and `5cd990b` attach API revision `f05a37ae9827db79597afd171b6bf72a0fbfc68a` and services revision `4202ba2c95533e8b0258784707e187113e02d2e6` as second parents. The consolidation commits then import their tracked files into `public/api/` and `services/` and perform the path changes. Cherry-picked consolidation commits carry references to their original commit IDs. The original frontend, API, and services source commits remain reachable unchanged.

Weather icons were imported as ordinary tracked files from revision `89e3173756248b4696b9b10677b66c4ef435db53` at `https://github.com/metno/weathericons.git`. Their `LICENSE` remains in `public/assets/weathericons/`; their external upstream history was not merged. Future icon updates require a deliberate import.

The `bin/xplanet` binary was moved from the API checkout unchanged. Its original notes are in `deploy/xplanet-README.md`.
