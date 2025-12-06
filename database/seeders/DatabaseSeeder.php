<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create users
        $agent = User::create([
            'name' => 'Sarah Johnson',
            'email' => 'agent@example.com',
            'password' => Hash::make('password'),
            'role' => 'agent',
        ]);

        $client1 = User::create([
            'name' => 'Michael Chen',
            'email' => 'client@example.com',
            'password' => Hash::make('password'),
            'role' => 'client',
        ]);

        $client2 = User::create([
            'name' => 'Emily Rodriguez',
            'email' => 'emily@example.com',
            'password' => Hash::make('password'),
            'role' => 'client',
        ]);

        // Create conversations
        $conv1 = Conversation::create([
            'title' => 'Auto Insurance Quote - Honda Civic',
            'status' => 'active',
        ]);
        $conv1->users()->attach([$agent->id, $client1->id]);

        $conv2 = Conversation::create([
            'title' => 'Home Insurance Policy Review',
            'status' => 'active',
        ]);
        $conv2->users()->attach([$agent->id, $client2->id]);

        // Add sample messages to first conversation
        Message::create([
            'conversation_id' => $conv1->id,
            'sender_id' => $client1->id,
            'content' => 'Hi! I\'m interested in getting a quote for my 2020 Honda Civic.',
            'type' => 'text',
        ]);

        Message::create([
            'conversation_id' => $conv1->id,
            'sender_id' => $agent->id,
            'content' => 'Great! I\'d be happy to help you with that. Can you tell me a bit about your driving history?',
            'type' => 'text',
        ]);

        Message::create([
            'conversation_id' => $conv1->id,
            'sender_id' => $client1->id,
            'content' => 'I\'ve been driving for 5 years with no accidents or tickets.',
            'type' => 'text',
        ]);

        Message::create([
            'conversation_id' => $conv1->id,
            'sender_id' => $agent->id,
            'content' => 'Based on your profile, I can offer you comprehensive coverage at $150/month with a $1,000 deductible. This includes liability, collision, and comprehensive coverage.',
            'type' => 'quote_proposal',
            'metadata' => [
                'monthly_premium' => 150,
                'deductible' => 1000,
                'coverage_type' => 'comprehensive',
                'status' => 'pending',
            ],
        ]);

        // Add messages to second conversation
        Message::create([
            'conversation_id' => $conv2->id,
            'sender_id' => $client2->id,
            'content' => 'Hello! I need to review my current home insurance policy. I think my coverage might be outdated.',
            'type' => 'text',
        ]);

        Message::create([
            'conversation_id' => $conv2->id,
            'sender_id' => $agent->id,
            'content' => 'Hi Emily! I\'d be glad to review your policy with you. Can you upload your current policy document so I can take a look?',
            'type' => 'text',
        ]);

        $this->command->info('Database seeded successfully!');
        $this->command->info('Agent: agent@example.com / password');
        $this->command->info('Client 1: client@example.com / password');
        $this->command->info('Client 2: emily@example.com / password');
    }
}
