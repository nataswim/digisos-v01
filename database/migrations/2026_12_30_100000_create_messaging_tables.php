<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Messagerie : conversations privées entre l'équipe du club (admin, éditeur, coach) et UN utilisateur.
 * Un envoi groupé (annonce) crée une conversation par destinataire : chacun ne voit que la sienne.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('message_announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('subject', 150);
            $table->text('body');
            $table->string('attachment_path')->nullable();
            $table->string('attachment_name')->nullable();
            $table->string('attachment_mime', 100)->nullable();
            $table->unsignedInteger('attachment_size')->nullable();
            $table->json('targets')->nullable();              // description des destinataires choisis
            $table->unsignedInteger('recipients_count')->default(0);
            $table->timestamps();
        });

        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->string('subject', 150);
            $table->foreignId('staff_id')->nullable()->constrained('users')->nullOnDelete();   // côté club
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();    // l'utilisateur
            $table->foreignId('announcement_id')->nullable()->constrained('message_announcements')->nullOnDelete();
            $table->foreignId('started_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('last_message_at')->nullable();
            $table->unsignedBigInteger('last_message_by')->nullable();
            $table->timestamp('staff_read_at')->nullable();
            $table->timestamp('user_read_at')->nullable();
            $table->timestamps();

            $table->index(['staff_id', 'last_message_at']);
            $table->index(['user_id', 'last_message_at']);
        });

        Schema::create('conversation_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('body');
            $table->string('attachment_path')->nullable();
            $table->string('attachment_name')->nullable();
            $table->string('attachment_mime', 100)->nullable();
            $table->unsignedInteger('attachment_size')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversation_messages');
        Schema::dropIfExists('conversations');
        Schema::dropIfExists('message_announcements');
    }
};
