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

        // 1. CodeCraft Hackathon (Technical Fests)
        $codecraft = Event::updateOrCreate(
            ['slug' => 'codecraft-hackathon'],
            [
                'title' => 'CodeCraft Hackathon',
                'description' => 'A premier 36-hour inter-college coding marathon where student developers collaborate to build high-impact web, mobile, and AI applications. Features mentorship from industry leaders, lightning tech talks, and prize pool with internship opportunities.',
                'category_id' => $techCat->id,
                'venue' => 'Innovation Hub & Turing Lab, Block A',
                'capacity' => 150,
                'available_slots' => 140,
                'start_date' => Carbon::now()->addDays(12)->setTime(9, 0),
                'end_date' => Carbon::now()->addDays(14)->setTime(18, 0),
                'registration_deadline' => Carbon::now()->addDays(10)->setTime(23, 59),
                'organizer_id' => $organizer->id,
                'organizing_department' => 'Computer Science & Engineering',
                'status' => 'approved',
                'banner_image' => 'images/events/codecraft.jpg',
                'hashtags' => '#CodeCraft #Hackathon #CampusDev #TechFest',
            ]
        );

        // 2. Pulse Music & Dance Fest (Cultural Events)
        $pulseFest = Event::updateOrCreate(
            ['slug' => 'pulse-music-and-dance-fest'],
            [
                'title' => 'Pulse Music & Dance Fest',
                'description' => 'The ultimate campus cultural festival celebrating music, dance, fashion, and theatrical arts. Enjoy live stage band battles, dynamic street dance battles, fusion choreography, and delicious campus food trucks.',
                'category_id' => $cultCat->id,
                'venue' => 'Central Amphitheatre & Open Lawn',
                'capacity' => 500,
                'available_slots' => 320,
                'start_date' => Carbon::now()->addDays(18)->setTime(17, 0),
                'end_date' => Carbon::now()->addDays(20)->setTime(22, 30),
                'registration_deadline' => Carbon::now()->addDays(16)->setTime(23, 59),
                'organizer_id' => $organizer->id,
                'organizing_department' => 'Cultural Affairs & Student Council',
                'status' => 'approved',
                'banner_image' => 'images/events/pulse-fest.jpg',
                'hashtags' => '#PulseFest #MusicAndDance #CampusVibes #CulturalNight',
            ]
        );

        // 3. RoboWars Combat Arena (Technical Fests)
        $robowars = Event::updateOrCreate(
            ['slug' => 'robowars-combat-arena'],
            [
                'title' => 'RoboWars Combat Arena',
                'description' => 'Fast-paced, high-octane robotics battle championship where student-built combat bots clash in an enclosed steel arena. Also includes autonomous obstacle navigation and line-follower sprint races.',
                'category_id' => $techCat->id,
                'venue' => 'Mechanical Engineering Arena, Hall 2',
                'capacity' => 100,
                'available_slots' => 75,
                'start_date' => Carbon::now()->addDays(22)->setTime(10, 0),
                'end_date' => Carbon::now()->addDays(23)->setTime(17, 0),
                'registration_deadline' => Carbon::now()->addDays(19)->setTime(23, 59),
                'organizer_id' => $organizer->id,
                'organizing_department' => 'Robotics & Automation Club',
                'status' => 'approved',
                'banner_image' => 'images/events/robowars.jpg',
                'hashtags' => '#RoboWars #BotArena #Engineering #Robotics',
            ]
        );

        // 4. Champions Cup Football (Sports Meets)
        $championsCup = Event::updateOrCreate(
            ['slug' => 'champions-cup-football'],
            [
                'title' => 'Champions Cup Football',
                'description' => 'The premier inter-department football tournament featuring 16 competing squads. Experience 4 days of thrilling knockout matches, penalty shootouts, and stadium action under the floodlights.',
                'category_id' => $sportsCat->id,
                'venue' => 'University Main Stadium Ground',
                'capacity' => 200,
                'available_slots' => 0,
                'start_date' => Carbon::now()->subDays(10)->setTime(15, 0),
                'end_date' => Carbon::now()->subDays(7)->setTime(20, 0),
                'registration_deadline' => Carbon::now()->subDays(14)->setTime(23, 59),
                'organizer_id' => $organizer->id,
                'organizing_department' => 'Sports & Physical Education Dept',
                'status' => 'approved',
                'banner_image' => 'images/events/champions-cup.jpg',
                'hashtags' => '#ChampionsCup #Football #CampusSports #Victory',
            ]
        );

        // 5. AI & ML Bootcamp (Workshops & Seminars)
        $aiBootcamp = Event::updateOrCreate(
            ['slug' => 'ai-and-ml-bootcamp'],
            [
                'title' => 'AI & Machine Learning Bootcamp',
                'description' => 'Comprehensive hands-on workshop on generative AI, deep learning models, prompt engineering, and building autonomous AI agents with Python and modern cloud frameworks.',
                'category_id' => $workshopCat->id,
                'venue' => 'Advanced Computing Complex, Lab 304',
                'capacity' => 80,
                'available_slots' => 12,
                'start_date' => Carbon::now()->addDays(5)->setTime(10, 0),
                'end_date' => Carbon::now()->addDays(6)->setTime(16, 0),
                'registration_deadline' => Carbon::now()->addDays(3)->setTime(23, 59),
                'organizer_id' => $organizer->id,
                'organizing_department' => 'Artificial Intelligence & Data Science',
                'status' => 'approved',
                'banner_image' => 'images/events/ai-bootcamp.jpg',
                'hashtags' => '#AIBootcamp #MachineLearning #GenAI #TechSkills',
            ]
        );

        // 6. Star Night Convocation Gala (Annual Day Functions)
        $starGala = Event::updateOrCreate(
            ['slug' => 'star-night-convocation-gala'],
            [
                'title' => 'Star Night Convocation Gala',
                'description' => 'The prestigious annual convocation and student excellence award night. Featuring keynote speeches from industry leaders, award ceremonies for top rankers, and an evening celebrity musical performance.',
                'category_id' => $annualCat->id,
                'venue' => 'Grand Convention Auditorium',
                'capacity' => 1000,
                'available_slots' => 650,
                'start_date' => Carbon::now()->addDays(30)->setTime(17, 30),
                'end_date' => Carbon::now()->addDays(30)->setTime(22, 0),
                'registration_deadline' => Carbon::now()->addDays(25)->setTime(23, 59),
                'organizer_id' => $organizer->id,
                'organizing_department' => 'University Administration',
                'status' => 'approved',
                'banner_image' => 'images/events/star-gala.jpg',
                'hashtags' => '#StarNight #Convocation #AnnualGala #ExcellenceAwards',
            ]
        );

        // 7. Apex Esports League (Intercollegiate Competitions)
        $apexEsports = Event::updateOrCreate(
            ['slug' => 'apex-campus-esports-league'],
            [
                'title' => 'Apex Campus Esports League',
                'description' => 'The definitive gaming showdown! Compete in Valorant 5v5, EA Sports FC 24, and Rocket League. Big screen projections, live match casting, RGB gaming stations, and trophy pool.',
                'category_id' => $interCat->id,
                'venue' => 'Multimedia Auditorium & Gaming Lounge',
                'capacity' => 120,
                'available_slots' => 45,
                'start_date' => Carbon::now()->addDays(15)->setTime(11, 0),
                'end_date' => Carbon::now()->addDays(16)->setTime(19, 0),
                'registration_deadline' => Carbon::now()->addDays(13)->setTime(23, 59),
                'organizer_id' => $organizer->id,
                'organizing_department' => 'Gaming & Esports Society',
                'status' => 'approved',
                'banner_image' => 'images/events/apex-esports.jpg',
                'hashtags' => '#ApexEsports #GamingChampionship #Valorant #CampusGamers',
            ]
        );

        // 8. LensCraft Photography Expo (Cultural Events)
        $lenscraft = Event::updateOrCreate(
            ['slug' => 'lenscraft-photo-and-art-expo'],
            [
                'title' => 'LensCraft Photography Expo',
                'description' => 'Annual student photography and digital art exhibition showcasing breathtaking visual storytelling, landscape photography, portrait showcases, and short film screenings.',
                'category_id' => $cultCat->id,
                'venue' => 'Fine Arts Gallery & Exhibition Hall',
                'capacity' => 150,
                'available_slots' => 90,
                'start_date' => Carbon::now()->addDays(8)->setTime(11, 0),
                'end_date' => Carbon::now()->addDays(9)->setTime(18, 0),
                'registration_deadline' => Carbon::now()->addDays(7)->setTime(23, 59),
                'organizer_id' => $organizer->id,
                'organizing_department' => 'Photography & Fine Arts Club',
                'status' => 'approved',
                'banner_image' => 'images/events/lenscraft.jpg',
                'hashtags' => '#LensCraft #Photography #ArtGallery #VisualStorytelling',
            ]
        );

        // Sample Registrations for Student
        Registration::updateOrCreate(
            ['event_id' => $codecraft->id, 'user_id' => $student->id],
            [
                'status' => 'registered',
                'qr_code_token' => 'QR-CODECRAFT-' . strtoupper(Str::random(8)) . '-' . $student->id,
                'registered_at' => Carbon::now()->subDays(2),
            ]
        );

        Registration::updateOrCreate(
            ['event_id' => $championsCup->id, 'user_id' => $student->id],
            [
                'status' => 'attended',
                'qr_code_token' => 'QR-CHAMPIONS-' . strtoupper(Str::random(8)) . '-' . $student->id,
                'certificate_fee_paid' => true,
                'certificate_fee_txn' => 'TXN-SPORTS-' . strtoupper(Str::random(6)),
                'registered_at' => Carbon::now()->subDays(15),
            ]
        );

        // Sample Attendance for Past Sports Event
        Attendance::updateOrCreate(
            ['event_id' => $championsCup->id, 'user_id' => $student->id],
            [
                'checked_in_by' => $organizer->id,
                'checked_in_at' => Carbon::now()->subDays(10)->setTime(14, 45),
            ]
        );

        // Sample Certificate for Past Sports Event
        Certificate::updateOrCreate(
            ['event_id' => $championsCup->id, 'user_id' => $student->id],
            [
                'certificate_number' => 'CERT-CHAMPIONS-2026-' . $student->id,
                'issued_at' => Carbon::now()->subDays(6),
            ]
        );

        // Sample Bookmark for Student
        Bookmark::updateOrCreate(
            ['user_id' => $student->id, 'event_id' => $pulseFest->id]
        );

        // Sample Feedback for Past Event
        Feedback::updateOrCreate(
            ['event_id' => $championsCup->id, 'user_id' => $student->id],
            [
                'user_role_title' => 'Tournament Forward & Department Captain',
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
            ['title' => 'Registration Open for CodeCraft Hackathon!'],
            [
                'message' => 'Early bird registrations for CodeCraft Hackathon are now live. Reserve your slots and form teams before the deadline.',
                'target_role' => 'all',
                'event_id' => $codecraft->id,
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
            ['user_id' => $student->id, 'title' => 'Registration Confirmed: CodeCraft Hackathon'],
            [
                'message' => 'Your pass for CodeCraft Hackathon is confirmed. View your QR check-in token on your student dashboard.',
                'type' => 'registration',
                'is_read' => false,
            ]
        );

        Notification::updateOrCreate(
            ['user_id' => $student->id, 'title' => 'Certificate Ready to Download'],
            [
                'message' => 'Your participation certificate for Champions Cup Football is generated and ready to download.',
                'type' => 'certificate',
                'is_read' => true,
            ]
        );

        // Media Gallery Items
        MediaGallery::updateOrCreate(
            ['title' => 'Grand Finale Trophy Celebration - Champions Cup'],
            [
                'event_id' => $championsCup->id,
                'media_type' => 'image',
                'file_path' => 'images/events/champions-cup.jpg',
                'category' => 'Sports Meets',
                'department' => 'Sports & Physical Education Dept',
                'year' => 2026,
                'uploaded_by' => $organizer->id,
            ]
        );

        MediaGallery::updateOrCreate(
            ['title' => 'Battle of the Bands - Pulse Fest Stage'],
            [
                'event_id' => $pulseFest->id,
                'media_type' => 'image',
                'file_path' => 'images/events/pulse-fest.jpg',
                'category' => 'Cultural Events',
                'department' => 'Cultural Affairs & Student Council',
                'year' => 2026,
                'uploaded_by' => $organizer->id,
            ]
        );

        MediaGallery::updateOrCreate(
            ['title' => 'RoboWars Bot Arena Combat Trials'],
            [
                'event_id' => $robowars->id,
                'media_type' => 'image',
                'file_path' => 'images/events/robowars.jpg',
                'category' => 'Technical Fests',
                'department' => 'Robotics & Automation Club',
                'year' => 2026,
                'uploaded_by' => $organizer->id,
            ]
        );
    }
}
