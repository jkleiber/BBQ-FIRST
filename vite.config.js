import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import { createClient } from '@supabase/supabase-js'

import { supabase_api_key, supabase_url } from './src/credentials/public_credentials.js'
import { buildSearchIndex } from './src/lib/search/search-index.js'

// Generate search-index.json at build time so the site can serve the search data as a static file,
// rather than every visitor downloading the Team and Event tables from supabase.
// If the index can't be generated, the build continues and the site falls back to querying supabase.
function searchIndexPlugin() {
  return {
    name: 'bbq-search-index',
    apply: 'build',
    async generateBundle() {
      try {
        const supabase = createClient(supabase_url, supabase_api_key, { auth: { persistSession: false } });
        const searchIndex = await buildSearchIndex(supabase);

        this.emitFile({
          type: 'asset',
          fileName: 'search-index.json',
          source: JSON.stringify(searchIndex)
        });
        console.log(`\nsearch-index.json: ${searchIndex.teams.length} teams, ${searchIndex.events.length} events`);
      } catch (error) {
        this.warn(`Failed to generate search-index.json, the site will query supabase instead: ${error.message ?? error}`);
      }
    }
  }
}

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [
    vue({
      template: {
        compilerOptions: {
          // treat all tags with a dash as custom elements
          isCustomElement: (tag) => tag.includes('-')
        }
      }
    }),
    searchIndexPlugin(),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    }
  }
})
