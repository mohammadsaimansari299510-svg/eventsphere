<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        // 2. Events
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('venue');
            $table->integer('capacity');
            $table->integer('available_slots');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->dateTime('registration_deadline');
            $table->foreignId('organizer_id')->constrained('users')->onDelete('cascade');
            $table->string('organizing_department')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled', 'completed'])->default('pending');
            $table->string('banner_image')->nullable();
            $table->string('rulebook_file')->nullable();
            $table->string('hashtags')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });

        // 3. Registrations
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['registered', 'waitlisted', 'cancelled', 'attended'])->default('registered');
            $table->string('qr_code_token')->unique();
            $table->boolean('certificate_fee_paid')->default(false);
            $table->string('certificate_fee_txn')->nullable();
            $table->dateTime('registered_at');
            $table->dateTime('cancelled_at')->nullable();
            $table->timestamps();
        });

        // 4. Waitlists
        Schema::create('waitlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('position')->default(1);
            $table->timestamps();
        });

        // 5. Attendances
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('checked_in_by')->constrained('users')->onDelete('cascade');
            $table->dateTime('checked_in_at');
            $table->timestamps();
        });

        // 6. Certificates
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('certificate_number')->unique();
            $table->string('file_path')->nullable();
            $table->dateTime('issued_at');
            $table->timestamps();
        });

        // 7. Feedbacks
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('user_role_title')->default('Student Participant');
            $table->integer('overall_rating')->default(5);
            $table->integer('venue_rating')->default(5);
            $table->integer('coordination_rating')->default(5);
            $table->integer('tech_rating')->default(5);
            $table->integer('hospitality_rating')->default(5);
            $table->text('comments')->nullable();
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
        });

        // 8. Media Gallery
        Schema::create('media_galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->nullable()->constrained('events')->onDelete('set null');
            $table->string('title');
            $table->enum('media_type', ['image', 'video'])->default('image');
            $table->string('file_path');
            $table->string('category');
            $table->string('department')->nullable();
            $table->integer('year')->default(2026);
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // 9. Saved Media
        Schema::create('saved_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('media_id')->constrained('media_galleries')->onDelete('cascade');
            $table->timestamps();
        });

        // 10. Bookmarks
        Schema::create('bookmarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->timestamps();
        });

        // 11. Announcements
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->enum('target_role', ['all', 'student', 'organizer'])->default('all');
            $table->foreignId('event_id')->nullable()->constrained('events')->onDelete('set null');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // 12. Notifications
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('message');
            $table->string('type')->default('general');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('bookmarks');
        Schema::dropIfExists('saved_media');
        Schema::dropIfExists('media_galleries');
        Schema::dropIfExists('feedbacks');
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('waitlists');
        Schema::dropIfExists('registrations');
        Schema::dropIfExists('events');
        Schema::dropIfExists('categories');
    }
};
