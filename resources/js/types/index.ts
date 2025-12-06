export interface User {
  id: number;
  name: string;
  email: string;
  role: 'agent' | 'client';
}

export interface Message {
  id: number;
  content: string;
  type: 'text' | 'quote_proposal' | 'system';
  metadata?: {
    monthly_premium?: number;
    deductible?: number;
    coverage_type?: string;
    status?: 'pending' | 'approved' | 'rejected';
  };
  sender: {
    id: number;
    name: string;
    role: string;
  };
  created_at: string;
}

export interface Document {
  id: number;
  filename: string;
  file_size: string;
  document_type: 'policy' | 'quote' | 'form' | 'other';
  uploader: {
    id: number;
    name: string;
  };
  created_at: string;
}

export interface Conversation {
  id: number;
  title: string;
  status: 'active' | 'archived';
  participants: User[];
  latest_message?: {
    content: string;
    sender_name: string;
    created_at: string;
  };
  messages_count?: number;
  messages?: Message[];
  documents?: Document[];
  created_at: string;
}
