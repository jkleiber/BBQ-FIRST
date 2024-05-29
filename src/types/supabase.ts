export type Json =
  | string
  | number
  | boolean
  | null
  | { [key: string]: Json | undefined }
  | Json[]

export type Database = {
  public: {
    Tables: {
      Appearance: {
        Row: {
          event_id: string | null
          id_str: string
          team_number: number | null
        }
        Insert: {
          event_id?: string | null
          id_str: string
          team_number?: number | null
        }
        Update: {
          event_id?: string | null
          id_str?: string
          team_number?: number | null
        }
        Relationships: [
          {
            foreignKeyName: "public_Appearance_event_id_fkey"
            columns: ["event_id"]
            isOneToOne: false
            referencedRelation: "Event"
            referencedColumns: ["event_id"]
          },
          {
            foreignKeyName: "public_Appearance_team_number_fkey"
            columns: ["team_number"]
            isOneToOne: false
            referencedRelation: "Team"
            referencedColumns: ["team_number"]
          },
        ]
      }
      BlueBanner: {
        Row: {
          date: string | null
          event_id: string | null
          id: number
          id_string: string
          name: string | null
          season: number
          team_number: number | null
          type: Database["public"]["Enums"]["BannerType"] | null
        }
        Insert: {
          date?: string | null
          event_id?: string | null
          id?: number
          id_string: string
          name?: string | null
          season: number
          team_number?: number | null
          type?: Database["public"]["Enums"]["BannerType"] | null
        }
        Update: {
          date?: string | null
          event_id?: string | null
          id?: number
          id_string?: string
          name?: string | null
          season?: number
          team_number?: number | null
          type?: Database["public"]["Enums"]["BannerType"] | null
        }
        Relationships: [
          {
            foreignKeyName: "public_BlueBanner_event_id_fkey"
            columns: ["event_id"]
            isOneToOne: false
            referencedRelation: "Event"
            referencedColumns: ["event_id"]
          },
          {
            foreignKeyName: "public_BlueBanner_team_number_fkey"
            columns: ["team_number"]
            isOneToOne: false
            referencedRelation: "Team"
            referencedColumns: ["team_number"]
          },
        ]
      }
      Event: {
        Row: {
          event_id: string
          name: string | null
          start_date: string | null
          type: number | null
          type_string: string | null
          week: number | null
          year: number | null
        }
        Insert: {
          event_id: string
          name?: string | null
          start_date?: string | null
          type?: number | null
          type_string?: string | null
          week?: number | null
          year?: number | null
        }
        Update: {
          event_id?: string
          name?: string | null
          start_date?: string | null
          type?: number | null
          type_string?: string | null
          week?: number | null
          year?: number | null
        }
        Relationships: []
      }
      EventData: {
        Row: {
          event_id: string
          robot_bbq: number | null
          robot_briquette: number | null
          robot_ribs: number | null
          robot_sauce: number | null
          team_bbq: number | null
          team_briquette: number | null
          team_ribs: number | null
          team_sauce: number | null
        }
        Insert: {
          event_id: string
          robot_bbq?: number | null
          robot_briquette?: number | null
          robot_ribs?: number | null
          robot_sauce?: number | null
          team_bbq?: number | null
          team_briquette?: number | null
          team_ribs?: number | null
          team_sauce?: number | null
        }
        Update: {
          event_id?: string
          robot_bbq?: number | null
          robot_briquette?: number | null
          robot_ribs?: number | null
          robot_sauce?: number | null
          team_bbq?: number | null
          team_briquette?: number | null
          team_ribs?: number | null
          team_sauce?: number | null
        }
        Relationships: [
          {
            foreignKeyName: "public_EventData_event_id_fkey"
            columns: ["event_id"]
            isOneToOne: true
            referencedRelation: "Event"
            referencedColumns: ["event_id"]
          },
        ]
      }
      Team: {
        Row: {
          country: string | null
          nickname: string | null
          province: string | null
          rookie_year: number | null
          team_number: number
        }
        Insert: {
          country?: string | null
          nickname?: string | null
          province?: string | null
          rookie_year?: number | null
          team_number: number
        }
        Update: {
          country?: string | null
          nickname?: string | null
          province?: string | null
          rookie_year?: number | null
          team_number?: number
        }
        Relationships: []
      }
      TeamData: {
        Row: {
          robot_bbq: number | null
          robot_briquette: number | null
          robot_ribs: number | null
          robot_sauce: number | null
          team_bbq: number | null
          team_briquette: number | null
          team_number: number
          team_ribs: number | null
          team_sauce: number | null
        }
        Insert: {
          robot_bbq?: number | null
          robot_briquette?: number | null
          robot_ribs?: number | null
          robot_sauce?: number | null
          team_bbq?: number | null
          team_briquette?: number | null
          team_number: number
          team_ribs?: number | null
          team_sauce?: number | null
        }
        Update: {
          robot_bbq?: number | null
          robot_briquette?: number | null
          robot_ribs?: number | null
          robot_sauce?: number | null
          team_bbq?: number | null
          team_briquette?: number | null
          team_number?: number
          team_ribs?: number | null
          team_sauce?: number | null
        }
        Relationships: [
          {
            foreignKeyName: "TeamData_team_number_fkey"
            columns: ["team_number"]
            isOneToOne: true
            referencedRelation: "Team"
            referencedColumns: ["team_number"]
          },
        ]
      }
    }
    Views: {
      [_ in never]: never
    }
    Functions: {
      [_ in never]: never
    }
    Enums: {
      BannerType: "Robot" | "Team"
    }
    CompositeTypes: {
      [_ in never]: never
    }
  }
}

type PublicSchema = Database[Extract<keyof Database, "public">]

export type Tables<
  PublicTableNameOrOptions extends
    | keyof (PublicSchema["Tables"] & PublicSchema["Views"])
    | { schema: keyof Database },
  TableName extends PublicTableNameOrOptions extends { schema: keyof Database }
    ? keyof (Database[PublicTableNameOrOptions["schema"]]["Tables"] &
        Database[PublicTableNameOrOptions["schema"]]["Views"])
    : never = never,
> = PublicTableNameOrOptions extends { schema: keyof Database }
  ? (Database[PublicTableNameOrOptions["schema"]]["Tables"] &
      Database[PublicTableNameOrOptions["schema"]]["Views"])[TableName] extends {
      Row: infer R
    }
    ? R
    : never
  : PublicTableNameOrOptions extends keyof (PublicSchema["Tables"] &
        PublicSchema["Views"])
    ? (PublicSchema["Tables"] &
        PublicSchema["Views"])[PublicTableNameOrOptions] extends {
        Row: infer R
      }
      ? R
      : never
    : never

export type TablesInsert<
  PublicTableNameOrOptions extends
    | keyof PublicSchema["Tables"]
    | { schema: keyof Database },
  TableName extends PublicTableNameOrOptions extends { schema: keyof Database }
    ? keyof Database[PublicTableNameOrOptions["schema"]]["Tables"]
    : never = never,
> = PublicTableNameOrOptions extends { schema: keyof Database }
  ? Database[PublicTableNameOrOptions["schema"]]["Tables"][TableName] extends {
      Insert: infer I
    }
    ? I
    : never
  : PublicTableNameOrOptions extends keyof PublicSchema["Tables"]
    ? PublicSchema["Tables"][PublicTableNameOrOptions] extends {
        Insert: infer I
      }
      ? I
      : never
    : never

export type TablesUpdate<
  PublicTableNameOrOptions extends
    | keyof PublicSchema["Tables"]
    | { schema: keyof Database },
  TableName extends PublicTableNameOrOptions extends { schema: keyof Database }
    ? keyof Database[PublicTableNameOrOptions["schema"]]["Tables"]
    : never = never,
> = PublicTableNameOrOptions extends { schema: keyof Database }
  ? Database[PublicTableNameOrOptions["schema"]]["Tables"][TableName] extends {
      Update: infer U
    }
    ? U
    : never
  : PublicTableNameOrOptions extends keyof PublicSchema["Tables"]
    ? PublicSchema["Tables"][PublicTableNameOrOptions] extends {
        Update: infer U
      }
      ? U
      : never
    : never

export type Enums<
  PublicEnumNameOrOptions extends
    | keyof PublicSchema["Enums"]
    | { schema: keyof Database },
  EnumName extends PublicEnumNameOrOptions extends { schema: keyof Database }
    ? keyof Database[PublicEnumNameOrOptions["schema"]]["Enums"]
    : never = never,
> = PublicEnumNameOrOptions extends { schema: keyof Database }
  ? Database[PublicEnumNameOrOptions["schema"]]["Enums"][EnumName]
  : PublicEnumNameOrOptions extends keyof PublicSchema["Enums"]
    ? PublicSchema["Enums"][PublicEnumNameOrOptions]
    : never
