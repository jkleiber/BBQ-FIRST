import { createClient } from '@supabase/supabase-js'

export const supabase = createClient();

// Get the active user's session.
export async function getLoggedInUser() {
    const { data: { user } } = await supabase.auth.getUser();

    return user;
}