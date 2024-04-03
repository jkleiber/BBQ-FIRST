import { createClient } from '@supabase/supabase-js'
import { supabase_api_key, supabase_url } from '@/credentials/public_credentials';

export const supabase = createClient(supabase_url, supabase_api_key);

// Get the active user's session.
// export async function getLoggedInUser() {
//     const { data: { user } } = await supabase.auth.getUser();

//     return user;
// }