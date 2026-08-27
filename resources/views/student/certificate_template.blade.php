<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Certificate - {{ $certificate->certificate_number }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Montserrat:wght@400;600;700&family=Great+Vibes&display=swap');
        
        body {
            margin: 0;
            padding: 0;
            background-color: #0b0f19;
            color: #1e293b;
            font-family: 'Montserrat', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .cert-container {
            width: 900px;
            height: 630px;
            background: #ffffff;
            border: 12px double #4f46e5;
            padding: 40px;
            box-sizing: border-box;
            position: relative;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
            text-align: center;
            background-image: radial-gradient(circle at center, #ffffff 60%, #f8fafc 100%);
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-family: 'Cinzel', serif;
            font-size: 80px;
            color: rgba(99, 102, 241, 0.05);
            font-weight: 700;
            pointer-events: none;
            user-select: none;
            white-space: nowrap;
        }

        .cert-header {
            font-family: 'Cinzel', serif;
            font-size: 32px;
            font-weight: 700;
            color: #1e1b4b;
            letter-spacing: 2px;
            margin-top: 10px;
        }

        .cert-sub {
            font-size: 14px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-top: 8px;
            font-weight: 600;
        }

        .presented-to {
            font-size: 14px;
            color: #475569;
            margin-top: 30px;
            font-style: italic;
        }

        .student-name {
            font-family: 'Great Vibes', cursive;
            font-size: 48px;
            color: #4f46e5;
            margin: 15px 0 5px;
        }

        .enrollment {
            font-size: 13px;
            color: #64748b;
            font-weight: 600;
        }

        .reason {
            font-size: 15px;
            line-height: 1.6;
            color: #334155;
            max-width: 650px;
            margin: 25px auto;
        }

        .event-title {
            font-weight: 700;
            color: #1e1b4b;
        }

        .cert-footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            padding: 0 40px;
        }

        .sig-block {
            text-align: center;
        }

        .sig-line {
            width: 180px;
            height: 1px;
            background: #94a3b8;
            margin: 0 auto 8px;
        }

        .sig-title {
            font-size: 12px;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
        }

        .cert-no {
            font-size: 11px;
            color: #94a3b8;
            position: absolute;
            bottom: 15px;
            left: 50%;
            transform: translateX(-50%);
        }
    </style>
</head>
<body>

    <div class="cert-container">
        <div class="watermark">EVENTSPHERE OFFICIAL</div>

        <div style="font-size: 12px; font-weight: 700; color: #6366f1; letter-spacing: 2px;">EVENTSPHERE ACADEMIC CREDENTIAL</div>
        <div class="cert-header">Certificate of Participation</div>
        <div class="cert-sub">College Event Information System</div>

        <div class="presented-to">This is proudly presented to</div>

        <div class="student-name">{{ $user->name }}</div>
        <div class="enrollment">Enrolment No: {{ $user->enrolment_number }} | Department: {{ $user->department }}</div>

        <div class="reason">
            for successfully registering and participating in the college event <br>
            <span class="event-title">"{{ $certificate->event->title }}"</span><br>
            organized by the {{ $certificate->event->organizing_department ?? 'Academic Council' }} on {{ $certificate->event->start_date->format('F d, Y') }}.
        </div>

        <div class="cert-footer">
            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="sig-title">Event Organizer</div>
            </div>

            <div style="width: 70px; height: 70px; border-radius: 50%; border: 2px solid #6366f1; display: flex; align-items: center; justify-content: center; color: #6366f1; font-weight: 700; font-size: 10px; text-transform: uppercase; margin-bottom: 5px;">
                Verified Seal
            </div>

            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="sig-title">Dean of Academics</div>
            </div>
        </div>

        <div class="cert-no">Certificate Ref ID: {{ $certificate->certificate_number }} | Issued: {{ $certificate->issued_at->format('M d, Y') }}</div>
    </div>

    <script>
        // Auto print prompt when opened
        window.onload = function() {
            setTimeout(function() { window.print(); }, 500);
        }
    </script>
</body>
</html>
