# Source import

The consolidated repository was cloned from frontend revision `cec02630b706797a69c5763a69947ccf3b6d1191`. API revision `f05a37ae9827db79597afd171b6bf72a0fbfc68a` and services revision `4202ba2c95533e8b0258784707e187113e02d2e6` were fetched and merged with `git merge --allow-unrelated-histories -s ours`, then their tracked trees were imported into `public/api/` and `services/`. Their commit histories remain reachable through the merge parents. The original checkouts remain untouched.

Weather icons were imported as ordinary tracked files from revision `89e3173756248b4696b9b10677b66c4ef435db53`. The upstream URL is `https://github.com/metno/weathericons.git`; the imported `public/assets/weathericons/LICENSE` remains with the assets. Future icon updates require a deliberate import.

The `bin/xplanet` binary was moved from the API checkout unchanged. Its original notes are in `deploy/xplanet-README.md`.
