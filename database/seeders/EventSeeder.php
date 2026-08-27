<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Category;
use App\Models\User;
use App\Models\Registration;
use App\Models\Waitlist;
use App\Models\Attendance;
use App\Models\Certificate;
use App\Models\Feedback;
use App\Models\MediaGallery;
use App\Models\Announcement;
use App\Models\Notification;
use App\Models\Bookmark;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $organizer = User::where('role', 'organizer')->first();
        $admin = User::where('role', 'admin')->first();
        $student = User::where('role', 'student')->first();

        if (!$organizer || !$student) {
            return;
        }

        $categories = Category::all()->keyBy('name');

        $techCat = $categories->get('Technical Fests') ?? Category::first();
        $cultCat = $categories->get('Cultural Events') ?? Category::first();
        $sportsCat = $categories->get('Sports Meets') ?? Category::first();
        $workshopCat = $categories->get('Workshops & Seminars') ?? Category::first();
        $annualCat = $categories->get('Annual Day Functions') ?? Category::first();
        $interCat = $categories->get('Intercollegiate Competitions') ?? Category::first();

        // 1. Upcoming Tech Hackathon Event (Approved)
        $hackathon = Event::updateOrCreate(
            ['slug' => 'hacknova-2026-inter-college-hackathon'],
            [
                'title' => 'HackNova 2026: 36-Hour National Hackathon',
                'description' => 'Unleash your creativity and engineering prowess at HackNova 2026! Over 36 exhilarating hours, build groundbreaking solutions in AI, Decentralized Systems, Climate Tech, and HealthTech. Mentorship from top industry engineers, hardware lab access, cloud credits, and exciting cash prizes up to $10,000.',
                'category_id' => $techCat->id,
                'venue' => 'Main Auditorium & Turing Innovation Lab, Block 4',
                'capacity' => 150,
                'available_slots' => 142,
                'start_date' => Carbon::now()->addDays(12)->setTime(9, 0),
                'end_date' => Carbon::now()->addDays(14)->setTime(18, 0),
                'registration_deadline' => Carbon::now()->addDays(10)->setTime(23, 59),
                'organizer_id' => $organizer->id,
                'organizing_department' => 'Computer Science & Engineering',
                'status' => 'approved',
                'hashtags' => '#HackNova2026 #Hackathon #CampusInnovate #DevCommunity',
            ]
        );

        // 2. Cultural Fest (Approved, Upcoming)
        $symphony = Event::updateOrCreate(
            ['slug' => 'symphonia-2026-annual-cultural-carnival'],
            [
                'title' => 'Symphonia 2026: Annual Cultural Carnival',
                'description' => 'The biggest cultural extravaganza of the year! Experience electric musical performances, battle of the bands, classical fusion dance, theatrical drama showcases, street play contests, and vibrant food stalls curated by campus clubs.',
                'category_id' => $cultCat->id,
                'venue' => 'Open Air Amphitheatre & Central Lawn',
                'capacity' => 500,
                'available_slots' => 380,
                'start_date' => Carbon::now()->addDays(20)->setTime(16, 0),
                'end_date' => Carbon::now()->addDays(22)->setTime(22, 0),
                'registration_deadline' => Carbon::now()->addDays(18)->setTime(23, 59),
                'organizer_id' => $organizer->id,
                'organizing_department' => 'Student Affairs & Cultural Council',
                'status' => 'approved',
                'hashtags' => '#Symphonia2026 #CulturalCarnival #CampusVibes #MusicFest',
            ]
        );

        // 3. Ongoing Workshop (Approved, Ongoing)
        $aiWorkshop = Event::updateOrCreate(
            ['slug' => 'ai-ml-bootcamp-hands-on-deep-learning'],
            [
                'title' => 'Next-Gen AI & LLM Systems Workshop',
                'description' => 'An intensive hands-on workshop covering Transformers, fine-tuning LLMs, retrieval-augmented generation (RAG), and deploying autonomous agent systems on edge infrastructure.',
                'category_id' => $workshopCat->id,
                'venue' => 'Advanced Computing Complex, Room 302',
                'capacity' => 60,
                'available_slots' => 0,
                'start_date' => Carbon::now()->subHours(2),
                'end_date' => Carbon::now()->addHours(6),
                'registration_deadline' => Carbon::now()->subDays(2),
                'organizer_id' => $organizer->id,
                'organizing_department' => 'Artificial Intelligence & Data Science',
                'status' => 'approved',
                'hashtags' => '#AIWorkshop #LLMs #DeepLearning #CampusTech',
            ]
        );

        // 4. Past Sports Event (Approved, Completed with Feedback & Attendance)
        $sportsMeet = Event::updateOrCreate(
            ['slug' => 'titan-cup-2026-inter-department-football'],
            [
                'title' => 'Titan Cup 2026: Inter-Department Football Championship',
                'description' => 'The ultimate clash for glory! 16 department teams battled over 4 action-packed days in knockout fixtures leading to the grand stadium final under the floodlights.',
                'category_id' => $sportsCat->id,
                'venue' => 'University Stadium Field A',
                'capacity' => 200,
                'available_slots' => 0,
                'start_date' => Carbon::now()->subDays(15)->setTime(14, 0),
                'end_date' => Carbon::now()->subDays(12)->setTime(20, 0),
                'registration_deadline' => Carbon::now()->subDays(18)->setTime(23, 59),
                'organizer_id' => $organizer->id,
                'organizing_department' => 'Physical Education & Athletics',
                'status' => 'approved',
                'hashtags' => '#TitanCup #FootballTournament #CampusSports #Champions',
            ]
        );

        // 5. Pending Event (Waiting for Admin approval)
        $pendingFest = Event::updateOrCreate(
            ['slug' => 'robomania-2026-autonomous-bot-wars'],
            [
                'title' => 'RoboMania 2026: Autonomous Bot Arena Challenge',
                'description' => 'High-voltage combat robotics and autonomous line-follower challenges. Teams design, fabricate, and pilot combat robots in a reinforced cage arena.',
                'category_id' => $techCat->id,
                'venue' => 'Mechanical Workshop Complex, Arena 1',
                'capacity' => 80,
                'available_slots' => 80,
                'start_date' => Carbon::now()->addDays(25)->setTime(10, 0),
                'end_date' => Carbon::now()->addDays(26)->setTime(17, 0),
                'registration_deadline' => Carbon::now()->addDays(22)->setTime(23, 59),
                'organizer_id' => $organizer->id,
                'organizing_department' => 'Robotics & Automation Society',
                'status' => 'pending',
                'hashtags' => '#RoboMania #BotWars #Robotics #EngineeringChallenge',
            ]
        );

        // 6. Annual Day Function (Upcoming, Approved)
        $annualDay = Event::updateOrCreate(
            ['slug' => 'annual-convocation-and-awards-gala-2026'],
            [
                'title' => '62nd Annual Convocation & Excellence Awards Gala',
                'description' => 'Honoring academic achievers, university medalists, distinguished alumni, and outstanding sports champions with keynote addresses from eminent global leaders.',
                'category_id' => $annualCat->id,
                'venue' => 'Grand Convention Hall',
                'capacity' => 1000,
                'available_slots' => 750,
                'start_date' => Carbon::now()->addDays(35)->setTime(10, 0),
                'end_date' => Carbon::now()->addDays(35)->setTime(18, 0),
                'registration_deadline' => Carbon::now()->addDays(30)->setTime(23, 59),
                'organizer_id' => $organizer->id,
                'organizing_department' => 'University Administration',
                'status' => 'approved',
                'hashtags' => '#AnnualGala #Convocation2026 #ExcellenceAwards',
            ]
        );

        // Sample Registrations for Student
        $regHack = Registration::updateOrCreate(
            ['event_id' => $hackathon->id, 'user_id' => $student->id],
            [
                'status' => 'registered',
                'qr_code_token' => 'QR-HACK2026-' . strtoupper(Str::random(8)) . '-' . $student->id,
                'registered_at' => Carbon::now()->subDays(2),
            ]
        );

        $regSports = Registration::updateOrCreate(
            ['event_id' => $sportsMeet->id, 'user_id' => $student->id],
            [
                'status' => 'attended',
                'qr_code_token' => 'QR-TITAN2026-' . strtoupper(Str::random(8)) . '-' . $student->id,
                'certificate_fee_paid' => true,
                'certificate_fee_txn' => 'TXN-SPORTS-' . strtoupper(Str::random(6)),
                'registered_at' => Carbon::now()->subDays(20),
            ]
        );

        // Sample Attendance for Past Sports Event
        Attendance::updateOrCreate(
            ['event_id' => $sportsMeet->id, 'user_id' => $student->id],
            [
                'checked_in_by' => $organizer->id,
                'checked_in_at' => Carbon::now()->subDays(15)->setTime(14, 15),
            ]
        );

        // Sample Certificate for Past Sports Event
        Certificate::updateOrCreate(
            ['event_id' => $sportsMeet->id, 'user_id' => $student->id],
            [
                'certificate_number' => 'CERT-TITAN-2026-' . $student->id,
                'issued_at' => Carbon::now()->subDays(11),
            ]
        );

        // Sample Bookmark for Student
        Bookmark::updateOrCreate(
            ['user_id' => $student->id, 'event_id' => $symphony->id]
        );

        // Sample Feedback for Past Event
        Feedback::updateOrCreate(
            ['event_id' => $sportsMeet->id, 'user_id' => $student->id],
            [
                'user_role_title' => 'Participant & Department Forward',
                'overall_rating' => 5,
                'venue_rating' => 5,
                'coordination_rating' => 5,
                'tech_rating' => 4,
                'hospitality_rating' => 5,
                'comments' => 'Incredible tournament atmosphere! Refereeing was unbiased and live scoreboards made following matches seamless.',
                'is_approved' => true,
            ]
        );

        // Announcements
        Announcement::updateOrCreate(
            ['title' => 'Registration Open for HackNova 2026!'],
            [
                'message' => 'Early bird registrations for HackNova 2026 are now live. Reserve your slots and form teams before the deadline.',
                'target_role' => 'all',
                'event_id' => $hackathon->id,
                'created_by' => $admin ? $admin->id : $organizer->id,
            ]
        );

        Announcement::updateOrCreate(
            ['title' => 'Auditorium Booking Guidelines for Spring Semester'],
            [
                'message' => 'All department club organizers must submit event proposals at least 14 days in advance for venue approval.',
                'target_role' => 'organizer',
                'created_by' => $admin ? $admin->id : $organizer->id,
            ]
        );

        // Notifications for Student
        Notification::updateOrCreate(
            ['user_id' => $student->id, 'title' => 'Registration Confirmed: HackNova 2026'],
            [
                'message' => 'Your pass for HackNova 2026 is confirmed. View your QR check-in token on your student dashboard.',
                'type' => 'registration',
                'is_read' => false,
            ]
        );

        Notification::updateOrCreate(
            ['user_id' => $student->id, 'title' => 'Certificate Ready to Download'],
            [
                'message' => 'Your participation certificate for Titan Cup 2026 is generated and ready to download.',
                'type' => 'certificate',
                'is_read' => true,
            ]
        );

        // Media Gallery Items
        MediaGallery::updateOrCreate(
            ['title' => 'Grand Finale Trophy Celebration - Titan Cup 2026'],
            [
                'event_id' => $sportsMeet->id,
                'media_type' => 'image',
                'file_path' => 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&w=1200&q=80',
                'category' => 'Sports Meets',
                'department' => 'Physical Education & Athletics',
                'year' => 2026,
                'uploaded_by' => $organizer->id,
            ]
        );

        MediaGallery::updateOrCreate(
            ['title' => 'Battle of the Bands - Live Stage Performance'],
            [
                'event_id' => $symphony->id,
                'media_type' => 'image',
                'file_path' => 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?auto=format&fit=crop&w=1200&q=80',
                'category' => 'Cultural Events',
                'department' => 'Student Affairs & Cultural Council',
                'year' => 2026,
                'uploaded_by' => $organizer->id,
            ]
        );

        MediaGallery::updateOrCreate(
            ['title' => 'Robotics Arena Autonomous Bot Trials'],
            [
                'event_id' => null,
                'media_type' => 'image',
                'file_path' => 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?auto=format&fit=crop&w=1200&q=80',
                'category' => 'Technical Fests',
                'department' => 'Robotics & Automation Society',
                'year' => 2026,
                'uploaded_by' => $organizer->id,
            ]
        );
    }
}
