# Sitemap rollout

The frontend at `https://www.armin.ba/sitemap.xml` is the single public sitemap. Deploy the backend retirement before or in the same coordinated release as the frontend SEO rollout.

The backend production deploy removes the ignored legacy artifact at `public/sitemap.xml`. It is not tracked by Git, so removing the command alone would not remove a previously generated file from the deployed public directory.

After the backend deploy, verify on the backend host:

```sh
set -eu
test ! -e /var/www/armin-backend/public/sitemap.xml
robots=$(curl -fsS https://<backend-host>/robots.txt)
if printf '%s\n' "$robots" | grep -qi '^sitemap:'; then
  echo "Backend robots.txt still advertises a sitemap."
  exit 1
fi
status=$(curl -sS -o /dev/null -w '%{http_code}' https://<backend-host>/sitemap.xml)
test "$status" = 404
```

Then deploy the frontend SEO rollout and verify that `https://www.armin.ba/sitemap.xml` returns the frontend-generated XML. The backend deployment workflow runs on pushes to `main`; this branch must be included in that production deployment before or alongside the frontend release.
