# Search Index

The search bars on the site autocomplete team and event names. The data for this comes from a static file, `search-index.json`, which is generated when the site is built and served by Vercel. This keeps every page view from downloading the `Team` and `Event` tables from supabase, which counts against the supabase egress limit.

## How it works

1. **Build time:** `searchIndexPlugin()` in `vite.config.js` runs during `vite build`. It queries supabase for only the columns the autocomplete needs and writes them to `dist/search-index.json`:

   ```json
   {
       "teams": [{ "team_number": 1, "nickname": "The Juggernauts" }, ...],
       "events": [{ "event_id": "1992cmp", "year": 1992, "name": "FIRST Robotics Competition" }, ...]
   }
   ```

   If the query fails, the build prints a warning and continues without the file.

2. **In the browser:** `getSearchIndex()` in `src/lib/search/bbq-autocomplete.js` fetches `/search-index.json` once per page load and shares it with every search bar.

3. **Fallback:** if the file isn't available, the site queries supabase for the same columns instead. This always happens on the local dev server (`npm run dev`), which doesn't generate the file. To test with the static file locally, run `npm run build` followed by `npm run preview`.

The query code used by both the build and the fallback is in `src/lib/search/search-index.js`.

## Keeping the index up to date

The index only changes when the site is rebuilt, but the nightly data update (`.github/workflows/update_data.yaml`) adds new teams and events to the database. To keep search current, the workflow's last job, `redeploy_site`, triggers a Vercel deploy hook after the data update finishes. Vercel then rebuilds the site, which regenerates the index.

If the hook isn't configured, the job prints a message and skips. New team and event pages still work by direct URL, but they won't appear in autocomplete until the next deploy (i.e. the next push to `main`).

## Setup: Vercel deploy hook

This only needs to be done once, by someone with admin access to the Vercel project and the GitHub repository.

1. In the Vercel dashboard, open the BBQ FIRST project and go to **Settings → Git → Deploy Hooks**.
2. Create a hook with any name (e.g. `nightly-data-update`) for the `main` branch, then copy the generated URL.
3. In the GitHub repository, go to **Settings → Secrets and variables → Actions** and create a new repository secret:
   - **Name:** `VERCEL_DEPLOY_HOOK_URL`
   - **Value:** the deploy hook URL from step 2
4. To test it, run the **Nightly Data Update** workflow manually from the Actions tab (it supports `workflow_dispatch`). When it finishes, the `redeploy_site` job should show a successful `curl` call and a new deployment should appear in Vercel.

Treat the deploy hook URL like a password: anyone who has it can trigger deployments. If it leaks, delete the hook in Vercel, create a new one, and update the secret.
