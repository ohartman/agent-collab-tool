# Insurance Agent Collaboration Tool

A real-time collaboration platform connecting insurance agents with their clients. Built with Laravel 12, Vue 3, TypeScript, and WebSockets for instant messaging and document sharing.

## 🎯 Inspired by Wunderite's Mission

This project demonstrates understanding of modern insurance technology needs - facilitating "delightful collaborations between insurance agents and their customers from any device."

## Tech Stack

- **Backend:** Laravel 12, PHP 8.2+, Laravel Reverb (WebSockets)
- **Frontend:** Vue 3, TypeScript, Vite
- **Real-time:** Laravel Broadcasting with Reverb
- **Database:** MySQL
- **Styling:** Tailwind CSS
- **State Management:** Pinia

## Features

- 💬 **Real-time Chat** - Instant messaging between agents and clients
- 📎 **Document Sharing** - Upload and share policy documents, quotes, forms
- ✅ **Quote Approval Workflow** - Request and approve quotes in real-time
- 👥 **Multi-User Rooms** - Separate conversation rooms per client
- 🔔 **Live Notifications** - Real-time updates for new messages and documents
- 📱 **Responsive Design** - Works on any device
- 🔒 **Role-based Access** - Agent and Client roles with appropriate permissions

## About This Project

**Note:** This is a **demonstration/portfolio project** showcasing architectural patterns and code quality for real-time insurance collaboration features. It contains the core application logic (models, controllers, events, migrations) but requires integration into a full Laravel 12 application to run.

### What's Included

✅ **Complete Backend Logic**
- Models with relationships
- API Controllers with real-time broadcasting
- WebSocket Events (MessageSent, DocumentUploaded)
- Database migrations and seeders
- TypeScript types and interfaces

✅ **Architecture Demonstrated**
- Event-driven design for real-time features
- RESTful API patterns
- Laravel Reverb WebSocket integration
- Role-based access control
- File upload handling

### To Review This Code

Simply browse the repository structure:
- `app/Models/` - Database models and relationships
- `app/Http/Controllers/Api/` - RESTful API endpoints
- `app/Events/` - WebSocket broadcasting events
- `database/migrations/` - Database schema design
- `resources/js/types/` - TypeScript interfaces

### To Run This Project

This would need to be integrated into a fresh Laravel 12 installation:

```bash
# Start with fresh Laravel 12
composer create-project laravel/laravel my-app
cd my-app

# Copy in the demonstration files from this repo
# Then run standard Laravel setup
php artisan migrate --seed
php artisan serve
```

## Project Structure

```
├── app/
│   ├── Events/
│   │   ├── MessageSent.php
│   │   └── DocumentUploaded.php
│   ├── Models/
│   │   ├── Conversation.php
│   │   ├── Message.php
│   │   ├── Document.php
│   │   └── User.php
│   └── Http/Controllers/
│       ├── ConversationController.php
│       ├── MessageController.php
│       └── DocumentController.php
├── resources/
│   ├── js/
│   │   ├── components/
│   │   │   ├── ChatWindow.vue
│   │   │   ├── MessageList.vue
│   │   │   ├── MessageInput.vue
│   │   │   ├── DocumentPanel.vue
│   │   │   └── ConversationList.vue
│   │   ├── composables/
│   │   │   └── useWebSocket.ts
│   │   └── stores/
│   │       └── chatStore.ts
│   └── views/
│       └── app.blade.php
└── routes/
    ├── api.php
    ├── channels.php
    └── web.php
```

## Key Implementation Details

### Real-time Messaging
Uses Laravel Reverb for WebSocket connections with automatic reconnection and presence channels.

### Document Management
Secure file uploads with validation, stored locally with database tracking.

### Quote Approval Workflow
Agents can send quote proposals, clients can approve/reject with real-time status updates.

## Demo Credentials

After seeding, use these accounts:

**Agent:**
- Email: agent@example.com
- Password: password

**Client:**
- Email: client@example.com
- Password: password

## API Endpoints

```
GET    /api/conversations        - List user's conversations
POST   /api/conversations        - Create new conversation
GET    /api/conversations/{id}   - Get conversation details
POST   /api/messages             - Send message
GET    /api/messages/{conv_id}   - Get messages for conversation
POST   /api/documents            - Upload document
GET    /api/documents/{conv_id}  - Get documents for conversation
```

## WebSocket Events

```
MessageSent              - New message in conversation
DocumentUploaded         - New document shared
QuoteProposalSent        - Agent sends quote for approval
QuoteStatusUpdated       - Client approves/rejects quote
TypingIndicator          - User is typing
```

## Why This Matters for Insurance Tech

1. **Real-time Communication** - No more email chains for simple questions
2. **Document Centralization** - All policy docs, quotes, forms in one place
3. **Faster Approvals** - Instant quote approval vs. days of back-and-forth
4. **Better Customer Experience** - Modern, chat-based interface clients expect
5. **Agent Productivity** - Handle multiple clients simultaneously

## Built By

Owen Hartman - [GitHub](https://github.com/ohartman) | [LinkedIn](https://linkedin.com/in/rowen-hartman)

**View this project:** https://github.com/ohartman/agent-collab-tool

Built to demonstrate modern real-time web application development and deep understanding of insurance technology needs.
