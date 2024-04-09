

import TbaApiV3client from '@tba-api-v3client';

// Set up the API client.
let defaultClient = TbaApiV3client.ApiClient.instance;
let apiKey = defaultClient.authentications['apiKey'];
apiKey.apiKey = Deno.env.get("TBA_API_KEY");

// Export the TBA APIs of interest.
export const tbaEventApi = new TbaApiV3client.EventApi();
