import { serve } from "https://deno.land/std@0.132.0/http/server.ts";
import { corsHeaders } from '../_shared/cors.ts'
import { tbaEventApi } from '../_utils/tba-client.js';

console.log("TBA function running!")
serve(async (req)=> {
    // This is needed if you're planning to invoke your function from a browser.
    if (req.method === 'OPTIONS') {
        return new Response('ok', { headers: corsHeaders })
    }

    try {
        // Get the request information.
        // The request should include the following:
        // - event_id: the event code for an FRC competition
        const { req_data } = await req.json();

        // Parse event code
        let eventId = req_data.event_id;

        // No options required for this call.
        let opts = {}

        tbaEventApi.getEvent(eventId, opts, (error, data, response) => {
            if (error) {
                return new Response(JSON.stringify({ error: error.message }), {
                    headers: { ...corsHeaders, 'Content-Type': 'application/json' },
                    status: 400,
                });
            } else {
                console.log('API called successfully. Returned data: ' + data);
                return new Response(JSON.stringify(data), {
                    headers: { ...corsHeaders, 'Content-Type': 'application/json' },
                    status: 200,
                });
            }
        });

        
    } catch (error) {
        return new Response(JSON.stringify({ error: error.message }), {
            headers: { ...corsHeaders, 'Content-Type': 'application/json' },
            status: 400,
        });
    }
});
    