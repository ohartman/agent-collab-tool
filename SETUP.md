# Quick Setup Guide - Agent Collaboration Tool

## What This Project Shows

This is a **real-time insurance agent collaboration platform** built in Laravel 12 + Vue 3 + TypeScript + WebSockets.

**Key Features Implemented:**
- ✅ Real-time messaging with Laravel Reverb (WebSockets)
- ✅ Document upload and sharing
- ✅ Quote proposal workflow
- ✅ Multi-user conversation rooms
- ✅ Role-based access (agents vs clients)
- ✅ RESTful API with proper resources
- ✅ Vue 3 Composition API + TypeScript
- ✅ Pinia state management
- ✅ Broadcasting events to connected clients

## Why This Matters for Wunderite

This directly demonstrates understanding of their core product: "facilitating delightful collaborations between insurance agents and their customers."

## Quick Start (Simplified)

### Important Note
This is a **demonstration project** showing architectural patterns and real-time capabilities. For full WebSocket functionality, you'll need:
- Redis server running
- Laravel Reverb configured

### Basic Setup (Without WebSockets)

```bash
# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Configure database
# Edit .env: DB_DATABASE=agent_collab

# Run migrations
php artisan migrate --seed

# Start servers
php artisan serve          # Terminal 1
npm run dev                # Terminal 2
```

### With WebSockets (Full Experience)

1. Install Redis: `brew install redis` (Mac) or `apt-get install redis` (Linux)
2. Start Redis: `redis-server`
3. Configure `.env`:
```
BROADCAST_DRIVER=reverb
QUEUE_CONNECTION=redis
```
4. Start Laravel Reverb: `php artisan reverb:start`
5. Start Laravel: `php artisan serve`
6. Start Vite: `npm run dev`

## Demo Credentials

- **Agent:** agent@example.com / password
- **Client:** client@example.com / password

## What The Code Demonstrates

### Backend Skills
- Laravel 12 best practices
- RESTful API design
- Event broadcasting architecture
- Database relationships (many-to-many)
- File upload handling
- Authorization and security

### Frontend Skills
- Vue 3 Composition API
- TypeScript interfaces and types
- WebSocket integration
- Pinia state management
- Real-time UI updates
- Component architecture

### Architecture
- Event-driven design
- Separation of concerns
- Scalable WebSocket implementation
- Proper resource/controller patterns

## Project Structure Highlights

**Models with Relationships:**
- User → Conversations (many-to-many)
- Conversation → Messages (one-to-many)
- Conversation → Documents (one-to-many)

**Broadcasting Events:**
- `MessageSent` - Broadcasts new messages
- `DocumentUploaded` - Broadcasts new documents

**API Endpoints:**
- GET `/api/conversations` - List conversations
- POST `/api/messages` - Send message (triggers broadcast)
- POST `/api/documents` - Upload document (triggers broadcast)

## Key Files to Review

1. `app/Events/MessageSent.php` - Shows WebSocket broadcasting
2. `app/Http/Controllers/Api/MessageController.php` - Real-time message handling
3. `app/Models/Conversation.php` - Complex relationships
4. `database/migrations/*` - Proper schema design

## Production Considerations

For production deployment, you'd add:
- Laravel Horizon for queue management
- Pusher or Ably for production WebSockets
- S3 for file storage
- Rate limiting
- Message read receipts
- Typing indicators
- User presence

This project demonstrates the foundational architecture that would scale to those features.

## Pushing to GitHub

```bash
git init
git add .
git commit -m "Real-time insurance agent collaboration tool - Laravel 12 + Vue 3 + WebSockets"
git remote add origin https://github.com/ohartman/agent-collab-tool.git
git branch -M main
git push -u origin main
```

---

**Built by Owen Hartman** to demonstrate modern real-time web application development and understanding of insurance technology needs.
