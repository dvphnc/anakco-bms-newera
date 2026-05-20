<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CommitteeSpecificSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // ────────────────────────────────────────────────────────────────
        // PEACE & ORDER — BPSO Members
        // ────────────────────────────────────────────────────────────────
        $bpsoMembers = [
            ['Rodrigo M. Dela Cruz',    'Chief Tanod',    'BT-001', '09171234567', 'Zone 1 – Purok Sampaguita', 'Active'],
            ['Ernesto S. Villanueva',   'Senior Tanod',   'BT-002', '09281234568', 'Zone 2 – Purok Rosal',      'Active'],
            ['Noel F. Reyes',           'Senior Tanod',   'BT-003', '09391234569', 'Zone 3 – Purok Gumamela',   'Active'],
            ['Antonio L. Santos',       'Tanod',          'BT-004', '09501234570', 'Zone 4 – Purok Adelfa',     'Active'],
            ['Marcelino D. Bautista',   'Tanod',          'BT-005', '09611234571', 'Zone 5 – Purok Ilang-Ilang','Active'],
            ['Renato C. Garcia',        'Tanod',          'BT-006', '09721234572', 'Zone 6 – Purok Camia',      'Active'],
            ['Domingo P. Ramos',        'Tanod',          'BT-007', '09831234573', 'Roving / All Zones',        'Active'],
            ['Federico B. Torres',      'Tanod',          'BT-008', '09941234574', 'Zone 1 – Purok Sampaguita', 'Active'],
            ['Crisanto V. Mendoza',     'Tanod',          'BT-009', '09151234575', 'Zone 2 – Purok Rosal',      'Active'],
            ['Bernardo Q. Aquino',      'Tanod',          'BT-010', '09261234576', 'Zone 3 – Purok Gumamela',   'Inactive'],
        ];
        foreach ($bpsoMembers as $m) {
            DB::table('committee_bpso')->updateOrInsert(
                ['badge_number' => $m[2]],
                ['full_name' => $m[0], 'rank' => $m[1], 'badge_number' => $m[2], 'contact_number' => $m[3],
                 'assignment' => $m[4], 'status' => $m[5], 'created_at' => $now, 'updated_at' => $now]
            );
        }

        // ────────────────────────────────────────────────────────────────
        // PEACE & ORDER — Patrol Logs
        // ────────────────────────────────────────────────────────────────
        $patrolLogs = [
            ['2026-05-18', 'Day Shift (6AM–2PM)',   'Zone 1 & Zone 2', 4, 'No untoward incident. Observed suspicious individual near the playground; advised to move along.', 'Chief Tanod Rodrigo Dela Cruz'],
            ['2026-05-18', 'Night Shift (10PM–6AM)','Zone 3 & Zone 4', 3, 'Noise complaint resolved. Coordinated with household at Purok Gumamela.', 'Sr. Tanod Ernesto Villanueva'],
            ['2026-05-17', 'Day Shift (6AM–2PM)',   'All Zones',       5, 'Routine patrol conducted. Assisted elderly resident with directions to barangay hall.', 'Chief Tanod Rodrigo Dela Cruz'],
            ['2026-05-17', 'Night Shift (10PM–6AM)','Zone 5 & Zone 6', 3, 'Minor disturbance reported near Zone 5 basketball court. Situation pacified immediately.', 'Tanod Antonio Santos'],
            ['2026-05-16', 'Afternoon (2PM–10PM)',  'Zone 2 & Zone 3', 4, 'Monitored barangay perimeter. Found unsecured vacant lot; reported to infrastructure committee.', 'Sr. Tanod Noel Reyes'],
            ['2026-05-15', 'Day Shift (6AM–2PM)',   'Zone 1',          3, 'Flag ceremony attendance monitoring. Reminded establishments to comply with anti-smoke belching ordinance.', 'Tanod Renato Garcia'],
            ['2026-05-14', 'Night Shift (10PM–6AM)','Zone 4 & Zone 5', 4, 'Patrolled during community event. No incident recorded.', 'Tanod Domingo Ramos'],
            ['2026-05-13', 'Day Shift (6AM–2PM)',   'All Zones',       6, 'Joint patrol with PNP. Two (2) residents issued community service orders for littering.', 'Chief Tanod Rodrigo Dela Cruz'],
        ];
        foreach ($patrolLogs as $i => $p) {
            DB::table('committee_patrol_logs')->updateOrInsert(
                ['patrol_date' => $p[0], 'shift' => $p[1]],
                ['patrol_date' => $p[0], 'shift' => $p[1], 'area_covered' => $p[2],
                 'personnel_count' => $p[3], 'findings' => $p[4], 'reported_by' => $p[5],
                 'created_at' => $now, 'updated_at' => $now]
            );
        }

        // ────────────────────────────────────────────────────────────────
        // PEACE & ORDER — Tanod Trainings
        // ────────────────────────────────────────────────────────────────
        $trainings = [
            ['Basic Law Enforcement Orientation',     'Training',  '2026-01-15', '2 days',  'Barangay Hall – Session Room', 'PNP Quezon City – Station 11',  20, 'Attendance certificate issued to all participants.'],
            ['Self-Defense & Arnis Training',         'Training',  '2026-02-10', '3 days',  'Barangay Basketball Court',    'Arnis Philippines Instructor',  18, 'Practical examination conducted on Day 3.'],
            ['First Aid & Basic Life Support',        'Seminar',   '2026-03-05', '1 day',   'Barangay Health Center',       'Philippine Red Cross – QC',     22, 'CPR certification awarded to completers.'],
            ['Community-Based Disaster Preparedness', 'Drill',     '2026-03-22', '2 days',  'Covered Court, New Era',       'CDRRMO Quezon City',            19, 'Simulation drill conducted on Day 2.'],
            ['Gender Sensitivity & Human Rights',     'Workshop',  '2026-04-18', '1 day',   'Barangay Hall – Main Hall',    'CHR Regional Office',           21, 'Workshop output submitted to committee.'],
            ['Cyber Crime Awareness Seminar',         'Seminar',   '2026-05-07', '½ day',   'Barangay Hall – Session Room', 'NBI Cyber Division',            15, 'Flyers for community distribution produced.'],
        ];
        foreach ($trainings as $t) {
            DB::table('committee_tanod_trainings')->updateOrInsert(
                ['title' => $t[0], 'training_date' => $t[2]],
                ['title' => $t[0], 'training_type' => $t[1], 'training_date' => $t[2], 'duration' => $t[3],
                 'venue' => $t[4], 'facilitator' => $t[5], 'participants_count' => $t[6],
                 'notes' => $t[7], 'file_path' => null, 'created_at' => $now, 'updated_at' => $now]
            );
        }

        // ────────────────────────────────────────────────────────────────
        // HEALTH — Medicine Inventory
        // ────────────────────────────────────────────────────────────────
        $medicines = [
            ['Paracetamol 500mg',  'Biogesic',   'Paracetamol',    'Analgesic/Antipyretic', 'Tablet',  'tablet',   250, 50, '2027-06-30', 'DOH – QC District',    'BT-2026-001', null],
            ['Amoxicillin 500mg',  'Amoxil',     'Amoxicillin',    'Antibiotic',            'Capsule', 'capsule',   80, 30, '2026-12-31', 'QC Health Office',     'BT-2026-002', null],
            ['Cetirizine 10mg',    'Zyrtec',     'Cetirizine HCl', 'Antihistamine',         'Tablet',  'tablet',   120, 25, '2027-03-31', 'DOH – QC District',    'BT-2026-003', null],
            ['Metformin 500mg',    'Glucophage', 'Metformin HCl',  'Antidiabetic',          'Tablet',  'tablet',    90, 20, '2027-01-31', 'PhilHealth Donation',  'BT-2026-004', null],
            ['Amlodipine 5mg',     'Norvasc',    'Amlodipine',     'Antihypertensive',      'Tablet',  'tablet',   150, 30, '2027-04-30', 'QC Health Office',     'BT-2026-005', null],
            ['Oral Rehydration Salt','ORS-Hydrite','ORS',          'Antidiarrheal',         'Sachet',  'sachet',   200, 50, '2027-08-31', 'DOH – QC District',    'BT-2026-006', null],
            ['Mefenamic Acid 500mg','Ponstan',   'Mefenamic Acid', 'Analgesic/NSAID',       'Capsule', 'capsule',   60, 20, '2026-11-30', 'QC Health Office',     'BT-2026-007', null],
            ['Vitamin C 500mg',    'Ascorbic',   'Ascorbic Acid',  'Supplement',            'Tablet',  'tablet',   300, 50, '2027-12-31', 'PhilHealth Donation',  'BT-2026-008', null],
        ];
        foreach ($medicines as $m) {
            DB::table('committee_medicine_inventory')->updateOrInsert(
                ['batch_number' => $m[11] ?? $m[10]],
                ['medicine_name' => $m[0], 'brand_name' => $m[1], 'generic_name' => $m[2],
                 'category' => $m[3], 'dosage_form' => $m[4], 'unit' => $m[5],
                 'current_stock' => $m[6], 'reorder_level' => $m[7], 'expiry_date' => $m[8],
                 'supplier' => $m[9], 'batch_number' => $m[10], 'barcode' => $m[11],
                 'created_at' => $now, 'updated_at' => $now]
            );
        }

        // ────────────────────────────────────────────────────────────────
        // HEALTH — Health Records
        // ────────────────────────────────────────────────────────────────
        $healthRecords = [
            ['Maria L. Santos',    45, 'Female', 'Purok 1-Sampaguita', 'Hypertension Stage 1',              'Senior Citizens Health Program', '2026-05-15', 'Dr. Alma Reyes',     'BP 140/90. Prescribed Amlodipine 5mg. Advised low-salt diet.'],
            ['Jose D. Cruz',       62, 'Male',   'Purok 2-Rosal',      'Type 2 Diabetes Mellitus',          'Senior Citizens Health Program', '2026-05-15', 'Dr. Alma Reyes',     'FBS 180 mg/dL. Continued Metformin. Exercise counseling given.'],
            ['Ana B. Reyes',        8, 'Female', 'Purok 3-Gumamela',   'Upper Respiratory Tract Infection', 'Child Health Program',           '2026-05-14', 'Nurse Liza Soriano', 'Temp 38.2°C. Paracetamol given. Advised rest and fluids.'],
            ['Carlos M. Dela Cruz',34, 'Male',   'Purok 4-Adelfa',     'Allergic Rhinitis',                 'General Consultation',           '2026-05-14', 'Nurse Liza Soriano', 'Prescribed Cetirizine 10mg. Allergen avoidance counseling.'],
            ['Luz P. Bautista',    28, 'Female', 'Purok 5-Ilang-Ilang','Prenatal Check-up (28 weeks)',      'Maternal & Child Health',        '2026-05-13', 'Dr. Alma Reyes',     'BP normal. FHT normal. Ferrous sulfate & folic acid prescribed.'],
            ['Pedro S. Lim',       71, 'Male',   'Purok 6-Camia',      'Osteoarthritis (knee)',             'Senior Citizens Health Program', '2026-05-12', 'Dr. Alma Reyes',     'Mefenamic acid given. Physical therapy referral recommended.'],
            ['Rosa A. Gomez',      55, 'Female', 'Purok 1-Sampaguita', 'Urinary Tract Infection',           'General Consultation',           '2026-05-11', 'Nurse Liza Soriano', 'Amoxicillin 500mg for 7 days. Advised increased fluid intake.'],
            ['Juan T. Navarro',     5, 'Male',   'Purok 2-Rosal',      'Diarrhea / Gastroenteritis',        'Child Health Program',           '2026-05-10', 'Nurse Liza Soriano', 'ORS given. No fever. Advised home management and follow-up.'],
            ['Elena C. Santos',    40, 'Female', 'Purok 3-Gumamela',   'Annual Physical Exam',              'General Consultation',           '2026-05-09', 'Dr. Alma Reyes',     'Overall results normal. Vitamin C supplementation advised.'],
            ['Ricardo V. Torres',  58, 'Male',   'Purok 4-Adelfa',     'Hypertension Stage 2',              'Senior Citizens Health Program', '2026-05-08', 'Dr. Alma Reyes',     'BP 160/100. Medication adjusted. Strict dietary compliance advised.'],
        ];
        foreach ($healthRecords as $r) {
            DB::table('committee_health_records')->updateOrInsert(
                ['patient_name' => $r[0], 'visit_date' => $r[6]],
                ['patient_name' => $r[0], 'age' => $r[1], 'gender' => $r[2], 'address' => $r[3],
                 'diagnosis' => $r[4], 'program' => $r[5], 'visit_date' => $r[6],
                 'attended_by' => $r[7], 'notes' => $r[8], 'created_at' => $now, 'updated_at' => $now]
            );
        }

        // ────────────────────────────────────────────────────────────────
        // HEALTH — Clinic Staff
        // ────────────────────────────────────────────────────────────────
        $clinicStaff = [
            ['Dr. Alma G. Reyes',    'Doctor',  'Internal Medicine',      'Quezon City Health Office',    '09171112233', 'Mon, Wed, Fri – 8AM to 12PM', 'Active'],
            ['Liza B. Soriano',      'Nurse',   'Community Health',       'Barangay New Era',             '09282223344', 'Mon to Fri – 8AM to 5PM',    'Active'],
            ['Grace T. Mendoza',     'Midwife', 'Maternal & Child Health','QC City Health Center',        '09393334455', 'Tue, Thu – 9AM to 3PM',      'Active'],
            ['Felix D. Catalan',     'Other',   'Environmental Health',   'Barangay New Era',             '09504445566', 'Mon to Fri – 7AM to 4PM',    'Active'],
            ['Marivic R. Espiritu',  'BHW',     'Community Health',       'Barangay New Era',             '09615556677', 'Mon to Sat – 8AM to 5PM',    'Active'],
        ];
        foreach ($clinicStaff as $s) {
            DB::table('committee_clinic_staff')->updateOrInsert(
                ['full_name' => $s[0]],
                ['full_name' => $s[0], 'position' => $s[1], 'specialization' => $s[2],
                 'affiliation' => $s[3], 'contact_number' => $s[4], 'schedule' => $s[5],
                 'status' => $s[6], 'created_at' => $now, 'updated_at' => $now]
            );
        }

        // ────────────────────────────────────────────────────────────────
        // EDUCATION — Scholars
        // ────────────────────────────────────────────────────────────────
        $scholars = [
            ['Kristine M. Santos',    'University of the Philippines – Diliman',        'BS Computer Science',       'Barangay Scholarship',    '2nd Year', 5000.00, 'Active',    '2025-06-15'],
            ['John Paul D. Reyes',    'Polytechnic University of the Philippines',       'BS Electrical Engineering', 'Barangay Scholarship',    '3rd Year', 5000.00, 'Active',    '2024-06-16'],
            ['Maria Angela C. Cruz',  'Quezon City University',                          'BS Nursing',                'City Scholarship (OSCY)', '2nd Year', 8000.00, 'Active',    '2025-06-15'],
            ['Ryan B. Mendoza',       'Technological University of the Philippines',     'BS Industrial Engineering', 'Barangay Scholarship',    '1st Year', 5000.00, 'Active',    '2026-06-14'],
            ['Ana Sofia L. Bautista', 'University of Santo Tomas',                       'BS Architecture',           'City Scholarship (OSCY)', '4th Year', 8000.00, 'Active',    '2023-06-17'],
            ['Michael T. Villanueva', 'Far Eastern University',                          'BS Accountancy',            'Barangay Scholarship',    '3rd Year', 5000.00, 'Active',    '2024-06-15'],
            ['Diane P. Torres',       'San Beda University',                             'AB Political Science',      'Barangay Scholarship',    '2nd Year', 5000.00, 'Active',    '2025-06-16'],
            ['Jose Carlo A. Ramos',   'National University',                             'BS Information Technology', 'Barangay Scholarship',    '1st Year', 5000.00, 'Active',    '2026-06-14'],
            ['Lovely Grace S. Lim',   'Pamantasan ng Lungsod ng Quezon City',            'BS Pharmacy',               'City Scholarship (OSCY)', '3rd Year', 8000.00, 'Active',    '2024-06-17'],
            ['Christian R. Aquino',   'De La Salle University',                          'BS Management',             'Barangay Scholarship',    '4th Year', 5000.00, 'Graduated', '2022-06-15'],
        ];
        foreach ($scholars as $s) {
            DB::table('committee_scholars')->updateOrInsert(
                ['full_name' => $s[0], 'school' => $s[1]],
                ['full_name' => $s[0], 'school' => $s[1], 'course_grade_level' => $s[2],
                 'scholarship_type' => $s[3], 'year_level' => $s[4], 'grant_amount' => $s[5],
                 'status' => $s[6], 'start_date' => $s[7], 'created_at' => $now, 'updated_at' => $now]
            );
        }

        // ────────────────────────────────────────────────────────────────
        // INFRASTRUCTURE — Projects
        // ────────────────────────────────────────────────────────────────
        $projects = [
            ['Road Concreting – Purok Sampaguita',  'Road',        'Purok 1-Sampaguita',  580000, 562000,  '2026-01-10', '2026-03-15', 100, 'Completed', 'Completed on schedule. Final inspection passed.'],
            ['Drainage Improvement – Purok Rosal',  'Drainage',    'Purok 2-Rosal',       420000, 418500,  '2026-02-01', '2026-04-30', 100, 'Completed', 'Resolved flooding issues in 3 sitios.'],
            ['Basketball Court Renovation',         'Facility',    'Purok 3-Gumamela',    350000, 340000,  '2026-03-01', '2026-05-15', 95,  'Ongoing',   'Flooring and bleachers pending final coat.'],
            ['Solar Street Lighting Installation',  'Electrical',  'All Puroks',          800000, 712000,  '2026-01-20', '2026-06-30', 85,  'Ongoing',   'Poles installed; wiring in progress for Puroks 4–6.'],
            ['Multi-Purpose Hall Roofing Repair',   'Building',    'Barangay Center',     220000, null,    '2026-04-15', '2026-06-30', 60,  'Ongoing',   'Material delivery delayed due to supply chain issues.'],
            ['Footbridge Construction – Creek Area','Bridge',      'Purok 5-Ilang-Ilang', 650000, null,    '2026-05-01', '2026-08-30', 20,  'Ongoing',   'Foundation work started. Engineering inspection on 05-20.'],
            ['Perimeter Fence – Barangay Hall',     'Fencing',     'Barangay Center',     180000, 178000,  '2025-10-01', '2025-12-15', 100, 'Completed', 'Installed 45 linear meters. COA inspection passed.'],
        ];
        foreach ($projects as $p) {
            DB::table('committee_projects')->updateOrInsert(
                ['project_name' => $p[0]],
                ['project_name' => $p[0], 'project_type' => $p[1], 'location' => $p[2],
                 'budget' => $p[3], 'actual_cost' => $p[4], 'start_date' => $p[5],
                 'end_date' => $p[6], 'completion_percentage' => $p[7], 'status' => $p[8],
                 'remarks' => $p[9], 'created_at' => $now, 'updated_at' => $now]
            );
        }

        // ────────────────────────────────────────────────────────────────
        // INFRASTRUCTURE — Contracts
        // ────────────────────────────────────────────────────────────────
        $contracts = [
            ['CTR-2026-001', 'DJMC Construction Corp.',    'Road Concreting – Purok Sampaguita',  580000, '2026-01-10', '2026-03-15', 'Completed'],
            ['CTR-2026-002', 'Dela Paz Drainage Works',    'Drainage Improvement – Purok Rosal',  420000, '2026-02-01', '2026-04-30', 'Completed'],
            ['CTR-2026-003', 'Santos General Contractors', 'Basketball Court Renovation',          350000, '2026-03-01', '2026-05-15', 'Active'],
            ['CTR-2026-004', 'Bright Solar Solutions Inc.','Solar Street Lighting Installation',   800000, '2026-01-20', '2026-06-30', 'Active'],
            ['CTR-2026-005', 'Nueva Builders & Supply',    'Multi-Purpose Hall Roofing Repair',    220000, '2026-04-15', '2026-06-30', 'Active'],
            ['CTR-2026-006', 'QC Engineering Works',       'Footbridge Construction – Creek Area', 650000, '2026-05-01', '2026-08-30', 'Active'],
        ];
        foreach ($contracts as $c) {
            DB::table('committee_infra_contracts')->updateOrInsert(
                ['contract_number' => $c[0]],
                ['contract_number' => $c[0], 'contractor_name' => $c[1], 'scope_of_work' => $c[2],
                 'contract_amount' => $c[3], 'start_date' => $c[4], 'end_date' => $c[5],
                 'status' => $c[6], 'file_path' => null, 'created_at' => $now, 'updated_at' => $now]
            );
        }

        // ────────────────────────────────────────────────────────────────
        // INFRASTRUCTURE — Financials
        // ────────────────────────────────────────────────────────────────
        $financials = [
            ['20% Development Fund – Q1 2026',       'Budget Release',  'DILG / LGU',       2500000, '2026-01-05', 'DVR-2026-001', 'Released per DILG guidelines. Allocated to 4 projects.'],
            ['Road Concreting – Mobilization',        'Disbursement',    'Barangay Fund',    174000,  '2026-01-11', 'DVR-2026-002', '30% mobilization fee per contract CTR-2026-001.'],
            ['Road Concreting – 2nd Progress Billing','Disbursement',    'Barangay Fund',    290000,  '2026-02-20', 'DVR-2026-003', '50% payment upon 50% completion.'],
            ['Road Concreting – Final Billing',       'Disbursement',    'Barangay Fund',    98000,   '2026-03-18', 'DVR-2026-004', 'Final payment upon COA inspection clearance.'],
            ['Drainage – Mobilization',               'Disbursement',    'Barangay Fund',    126000,  '2026-02-05', 'DVR-2026-005', '30% mobilization fee per contract CTR-2026-002.'],
            ['Drainage – Final Billing',              'Disbursement',    'Barangay Fund',    292500,  '2026-05-02', 'DVR-2026-006', 'Final billing cleared by engineering office.'],
            ['20% Development Fund – Q2 2026',        'Budget Release',  'DILG / LGU',       2500000, '2026-04-03', 'DVR-2026-007', 'Q2 release for remaining projects and new ones.'],
            ['Solar Lighting – Progress Billing 1',   'Disbursement',    'Barangay Fund',    320000,  '2026-03-10', 'DVR-2026-008', 'Covers materials and first phase installation.'],
        ];
        foreach ($financials as $f) {
            DB::table('committee_infra_financials')->updateOrInsert(
                ['reference_number' => $f[4]],
                ['title' => $f[0], 'type' => $f[1], 'fund_source' => $f[2], 'amount' => $f[3],
                 'date' => $f[4], 'reference_number' => $f[5], 'remarks' => $f[6],
                 'file_path' => null, 'created_at' => $now, 'updated_at' => $now]
            );
        }
        // fix: reference_number is actually field[5]
        DB::table('committee_infra_financials')->truncate();
        foreach ($financials as $f) {
            DB::table('committee_infra_financials')->insert(
                ['title' => $f[0], 'type' => $f[1], 'fund_source' => $f[2], 'amount' => $f[3],
                 'date' => $f[4], 'reference_number' => $f[5], 'remarks' => $f[6],
                 'file_path' => null, 'created_at' => $now, 'updated_at' => $now]
            );
        }

        // ────────────────────────────────────────────────────────────────
        // ENVIRONMENT — Programs
        // ────────────────────────────────────────────────────────────────
        $envPrograms = [
            ['Barangay-Wide Clean-Up Drive',         'Clean-Up',       '2026-05-17', 'All Puroks',          120, 0,   1850, 'Completed', '120 volunteers participated. 1,850 kg waste collected.'],
            ['Tree Planting Activity – Rizal Day',   'Tree Planting',  '2026-04-27', 'Quezon Memorial Park',45,  80,  0,    'Completed', 'Partnered with DENR QC. 80 endemic trees planted.'],
            ['Proper Waste Segregation Seminar',     'Education',      '2026-04-10', 'Barangay Hall',       60,  0,   0,    'Completed', 'Conducted per Ecological Solid Waste Management Act.'],
            ['Creek Clearing & Dredging',            'Clean-Up',       '2026-03-22', 'Creek – Zone 3 & 4',  55,  0,   2200, 'Completed', 'Coordinated with MMDA. Creek depth restored.'],
            ['Composting Workshop',                  'Education',      '2026-03-05', 'Barangay Hall',       40,  0,   0,    'Completed', 'Distributed 30 composting kits to participating households.'],
            ['Material Recovery Facility (MRF) Day', 'Recycling',      '2026-02-21', 'Barangay MRF',        80,  0,   950,  'Completed', 'Recyclables sorted and sold to junk shop. Revenue: ₱4,200.'],
            ['Anti-Plastic Campaign – Purok Level',  'Advocacy',       '2026-05-30', 'All Puroks',          0,   0,   0,    'Planned',   'To be conducted in coordination with QC ENRO.'],
        ];
        foreach ($envPrograms as $p) {
            DB::table('committee_environment_programs')->updateOrInsert(
                ['program_name' => $p[0], 'program_date' => $p[2]],
                ['program_name' => $p[0], 'program_type' => $p[1], 'program_date' => $p[2],
                 'location' => $p[3], 'volunteers' => $p[4], 'trees_planted' => $p[5],
                 'waste_collected_kg' => $p[6], 'status' => $p[7], 'notes' => $p[8],
                 'created_at' => $now, 'updated_at' => $now]
            );
        }

        // ────────────────────────────────────────────────────────────────
        // ENVIRONMENT — Street Sweepers
        // ────────────────────────────────────────────────────────────────
        $sweepers = [
            ['Rodolfo M. Castillo',   'Zone 1 – Main Road & Purok Sampaguita', '09171234001', 'Mon–Sat, 5AM–9AM', '2024-01-15', 'Active'],
            ['Teresita F. Bautista',  'Zone 2 – Purok Rosal & Adjacent Streets','09282234002', 'Mon–Sat, 5AM–9AM', '2024-01-15', 'Active'],
            ['Danilo R. Magtanggol',  'Zone 3 – Purok Gumamela',               '09393234003', 'Mon–Sat, 5AM–9AM', '2024-03-01', 'Active'],
            ['Celestina P. Aquino',   'Zone 4 – Purok Adelfa & Market Area',   '09504234004', 'Mon–Sat, 5AM–9AM', '2024-03-01', 'Active'],
            ['Florencio T. Mendoza',  'Zone 5 – Purok Ilang-Ilang',            '09615234005', 'Mon–Sat, 5AM–9AM', '2025-01-10', 'Active'],
            ['Natividad C. Reyes',    'Zone 6 – Purok Camia & Boundary',       '09726234006', 'Mon–Sat, 5AM–9AM', '2025-01-10', 'Active'],
        ];
        foreach ($sweepers as $s) {
            DB::table('committee_street_sweepers')->updateOrInsert(
                ['full_name' => $s[0]],
                ['full_name' => $s[0], 'assigned_zone' => $s[1], 'contact_number' => $s[2],
                 'schedule' => $s[3], 'date_assigned' => $s[4], 'status' => $s[5],
                 'created_at' => $now, 'updated_at' => $now]
            );
        }

        // ────────────────────────────────────────────────────────────────
        // LIVELIHOOD — Beneficiaries
        // ────────────────────────────────────────────────────────────────
        $beneficiaries = [
            ['Salvacion R. Dela Cruz',  'Purok 1-Sampaguita', '09171110001', 'Negosyo sa Barangay',          'Skills Training',   '2026-02-10', 3000,  'Active',    'Completed basic livelihood training. Selling sari-sari products.'],
            ['Nilda B. Santos',         'Purok 2-Rosal',      '09282110002', 'Negosyo sa Barangay',          'Skills Training',   '2026-02-10', 3000,  'Active',    'Enrolled in basic food processing course.'],
            ['Araceli P. Reyes',        'Purok 3-Gumamela',   '09393110003', 'DOLE Livelihood Grant',        'Cash Grant',        '2026-03-15', 10000, 'Active',    'Used grant to establish a small food stall.'],
            ['Josefina T. Mendoza',     'Purok 4-Adelfa',     '09504110004', 'DOLE Livelihood Grant',        'Cash Grant',        '2026-03-15', 10000, 'Active',    'Purchased sewing machine for dress-making livelihood.'],
            ['Remedios A. Villanueva',  'Purok 5-Ilang-Ilang','09615110005', 'Barangay Livelihood Program',  'Skills Training',   '2026-01-20', 3000,  'Active',    'Completed welding training at TESDA.'],
            ['Gloria D. Garcia',        'Purok 6-Camia',      '09726110006', 'Barangay Livelihood Program',  'Skills Training',   '2026-01-20', 3000,  'Active',    'Enrolled in beauty care NC II program.'],
            ['Milagros S. Bautista',    'Purok 1-Sampaguita', '09831110007', 'DOLE Livelihood Grant',        'Cash Grant',        '2026-03-15', 10000, 'Completed', 'Completed program. Now operating a small bakery.'],
            ['Corazon R. Aquino',       'Purok 2-Rosal',      '09171110008', 'Negosyo sa Barangay',          'Skills Training',   '2026-04-05', 3000,  'Active',    'Currently undergoing soap and candle making training.'],
            ['Lourdes F. Torres',       'Purok 3-Gumamela',   '09282110009', 'Sustainable Livelihood Prog.', 'Microenterprise',   '2026-02-28', 15000, 'Active',    'Registered microenterprise: "Aling Lourdes Kakanin".'],
            ['Teofista B. Cruz',        'Purok 4-Adelfa',     '09393110010', 'Sustainable Livelihood Prog.', 'Microenterprise',   '2026-02-28', 15000, 'Active',    'Operating "Kubo ni Teofista" — native delicacies stall.'],
            ['Caridad M. Ramos',        'Purok 5-Ilang-Ilang','09504110011', 'DOLE Livelihood Grant',        'Cash Grant',        '2026-04-20', 10000, 'Active',    'Used grant for vegetable container garden project.'],
            ['Felicidad D. Navarro',    'Purok 6-Camia',      '09615110012', 'Barangay Livelihood Program',  'Skills Training',   '2026-04-05', 3000,  'Active',    'Completing food safety hygiene seminar this month.'],
        ];
        foreach ($beneficiaries as $b) {
            DB::table('committee_livelihood_beneficiaries')->updateOrInsert(
                ['full_name' => $b[0], 'program_name' => $b[4]],
                ['full_name' => $b[0], 'address' => $b[1], 'contact_number' => $b[2],
                 'program_name' => $b[3], 'program_type' => $b[4], 'date_enrolled' => $b[5],
                 'amount_received' => $b[6], 'status' => $b[7], 'remarks' => $b[8],
                 'created_at' => $now, 'updated_at' => $now]
            );
        }

        // ────────────────────────────────────────────────────────────────
        // TRANSPORT — TODA (Tricycle Operators & Drivers Association)
        // ────────────────────────────────────────────────────────────────
        $toda = [
            ['Roberto L. Santiago',   'Roberto L. Santiago',  'Tricycle', 'LPX-001', 'New Era TODA', 'New Era – Tandang Sora Route', '2025-03-15', '2026-03-14', 'Active'],
            ['Benigno R. Macaraig',   'Benigno R. Macaraig',  'Tricycle', 'LPX-002', 'New Era TODA', 'New Era – Tandang Sora Route', '2025-03-15', '2026-03-14', 'Active'],
            ['Hernando C. Soriano',   'Hernando C. Soriano',  'Tricycle', 'LPX-003', 'New Era TODA', 'New Era – Batasan Route',      '2025-04-01', '2026-03-31', 'Active'],
            ['Virgilio T. Panganiban','Virgilio T. Panganiban','Tricycle', 'LPX-004', 'New Era TODA', 'New Era – Batasan Route',      '2025-04-01', '2026-03-31', 'Active'],
            ['Celestino M. Villafuerte','Celestino M. Villafuerte','Tricycle','LPX-005','New Era TODA','New Era – Commonwealth Route',  '2025-05-10', '2026-05-09', 'Active'],
            ['Delfin P. Abarca',      'Delfin P. Abarca',     'Tricycle', 'LPX-006', 'New Era TODA', 'New Era – Commonwealth Route',  '2025-05-10', '2026-05-09', 'Active'],
            ['Arsenio B. Castillo',   'Arsenio B. Castillo',  'Tricycle', 'LPX-007', 'New Era TODA', 'New Era – Litex Route',        '2025-06-01', '2026-05-31', 'Active'],
            ['Ceferino R. Jacinto',   'Ceferino R. Jacinto',  'Tricycle', 'LPX-008', 'New Era TODA', 'New Era – Litex Route',        '2025-06-01', '2026-05-31', 'Active'],
            ['Gaudencio T. Palma',    'Gaudencio T. Palma',   'Tricycle', 'LPX-009', 'New Era TODA', 'New Era – Tandang Sora Route', '2024-07-20', '2025-07-19', 'Expired'],
            ['Isidro M. Ferrer',      'Isidro M. Ferrer',     'Tricycle', 'LPX-010', 'New Era TODA', 'New Era – Batasan Route',      '2025-08-01', '2026-07-31', 'Active'],
            ['Juanito L. dela Rosa',  'Juanito L. dela Rosa', 'Tricycle', 'LPX-011', 'New Era TODA', 'New Era – Commonwealth Route',  '2025-09-15', '2026-09-14', 'Active'],
            ['Marcelo R. Ocampo',     'Marcelo R. Ocampo',    'Tricycle', 'LPX-012', 'New Era TODA', 'New Era – Litex Route',        '2025-10-01', '2026-09-30', 'Active'],
        ];
        foreach ($toda as $t) {
            DB::table('committee_toda')->updateOrInsert(
                ['plate_number' => $t[3]],
                ['operator_name' => $t[0], 'driver_name' => $t[1], 'vehicle_type' => $t[2],
                 'plate_number' => $t[3], 'toda_name' => $t[4], 'route' => $t[5],
                 'registration_date' => $t[6], 'expiry_date' => $t[7], 'status' => $t[8],
                 'created_at' => $now, 'updated_at' => $now]
            );
        }

        // ────────────────────────────────────────────────────────────────
        // BDRRM — Emergency Logs
        // ────────────────────────────────────────────────────────────────
        $emergencyLogs = [
            ['Flooding',         '2026-05-12', 'Purok 3-Gumamela (Creek Area)',       45,  183, 'Moderate flooding due to heavy rains. Creek overflowed. No casualties.',    'Deployed inflatable boats. Evacuated 45 families to covered court. Coordinated with CDRRMO.', 'Brgy. Captain Robert Romano', 'Resolved'],
            ['Fire Incident',    '2026-04-03', 'Purok 5-Ilang-Ilang (Blk 12, Lot 4)', 2,   8,  'House fire of undetermined origin. Two residential structures partially burned.', 'BFP responded within 8 minutes. Families temporarily housed in barangay hall.', 'Brgy. Kagawad Maria Santos', 'Resolved'],
            ['Vehicular Accident','2026-03-17', 'Purok 2-Rosal (Main Road intersection)',0,  3,  'Motorcycle-jeepney collision. Three injured, one serious.',                   'Victims transported to QMC by BCURT. Coordination with PNP Station 11.',           'Tanod on duty: R. Dela Cruz', 'Resolved'],
            ['Strong Winds',     '2026-02-08', 'All Puroks',                           12,  50, 'Signal No. 1 winds. Rooftop damages in 12 households. No casualties.',       'Damage assessment conducted. Reported to DILG for calamity fund release.',          'Brgy. Captain Robert Romano', 'Resolved'],
            ['Medical Emergency','2026-01-25', 'Purok 1-Sampaguita',                   0,   1,  'Senior citizen found unconscious. Hypertensive crisis.',                     'First aid administered. Transported to QMC ER via BCURT. Patient stabilized.',      'BHW Marivic Espiritu',        'Resolved'],
        ];
        foreach ($emergencyLogs as $l) {
            DB::table('committee_emergency_logs')->updateOrInsert(
                ['incident_type' => $l[0], 'incident_date' => $l[1], 'location' => $l[2]],
                ['incident_type' => $l[0], 'incident_date' => $l[1], 'location' => $l[2],
                 'affected_families' => $l[3], 'affected_persons' => $l[4], 'description' => $l[5],
                 'response_actions' => $l[6], 'reported_by' => $l[7], 'status' => $l[8],
                 'created_at' => $now, 'updated_at' => $now]
            );
        }

        // ────────────────────────────────────────────────────────────────
        // BDRRM — Evacuation Centers
        // ────────────────────────────────────────────────────────────────
        $evacCenters = [
            ['Barangay Covered Court',        'Purok 3-Gumamela, New Era',    600, 0,  'Available', 'Brgy. Kagawad Lino Flores', '09171234100', 'Toilets, water, generator, first-aid kit, cots (80)'],
            ['New Era Multi-Purpose Hall',    'Barangay Center, New Era',     350, 0,  'Available', 'Brgy. Secretary Ana Torres', '09282234101', 'Toilets, water, kitchen area, tables and chairs'],
            ['New Era Elementary School Gym', 'Purok 2-Rosal, New Era',       800, 0,  'Available', 'School Principal (in coord.)','09393234102', 'Spacious gym, toilets, water, generator'],
            ['Zone 4 Community Center',       'Purok 4-Adelfa, New Era',      200, 0,  'Available', 'Purok Leader Dante Cruz',   '09504234103', 'Toilets, water, benches, first-aid kit'],
        ];
        foreach ($evacCenters as $e) {
            DB::table('committee_evacuation_centers')->updateOrInsert(
                ['center_name' => $e[0]],
                ['center_name' => $e[0], 'location' => $e[1], 'capacity' => $e[2],
                 'current_occupancy' => $e[3], 'status' => $e[4], 'contact_person' => $e[5],
                 'contact_number' => $e[6], 'facilities' => $e[7],
                 'created_at' => $now, 'updated_at' => $now]
            );
        }

        // ────────────────────────────────────────────────────────────────
        // BDRRM — Relief Supplies
        // ────────────────────────────────────────────────────────────────
        $reliefSupplies = [
            ['Rice (5kg bag)',              'Food',           80,   'bag',   'DSWD QC',               '2026-05-01', 'Available', 'Stored at covered court bodega.'],
            ['Canned Goods (assorted)',     'Food',           300,  'can',   'Red Cross QC Chapter',  '2026-04-28', 'Available', '100 sardines, 100 corned beef, 100 spam.'],
            ['Instant Noodles (pack)',      'Food',           500,  'pack',  'LGU QC Donation',       '2026-04-25', 'Available', 'Assorted flavors.'],
            ['Mineral Water (1.5L)',        'Food',           120,  'bottle','LGU QC Donation',       '2026-05-03', 'Available', 'Sealed bottles.'],
            ['Hygiene Kit (soap & toothbrush)','Non-Food',   60,   'kit',   'DSWD QC',               '2026-04-20', 'Available', 'Per kit: 1 bar soap, 1 toothbrush, 1 toothpaste.'],
            ['Blanket',                     'Non-Food',       40,   'piece', 'Red Cross QC Chapter',  '2026-03-10', 'Available', 'Stored in sealed plastic bags.'],
            ['Sleeping Mat',                'Non-Food',       35,   'piece', 'LGU QC Donation',       '2026-03-10', 'Available', null],
            ['Flashlight (with batteries)', 'Non-Food',       20,   'unit',  'LGU QC Donation',       '2026-04-15', 'Available', 'Emergency use only.'],
            ['First Aid Kit (complete)',    'Medical',        10,   'kit',   'Philippine Red Cross',  '2026-02-20', 'Available', 'Each kit covers 20 persons.'],
            ['Paracetamol 500mg (blister)', 'Medical',        200,  'blister','DOH QC District',      '2026-05-05', 'Available', 'For use during disaster response only.'],
        ];
        foreach ($reliefSupplies as $r) {
            DB::table('committee_relief_supplies')->updateOrInsert(
                ['item_name' => $r[0], 'source' => $r[4]],
                ['item_name' => $r[0], 'category' => $r[1], 'quantity' => $r[2], 'unit' => $r[3],
                 'source' => $r[4], 'date_received' => $r[5], 'status' => $r[6], 'remarks' => $r[7],
                 'created_at' => $now, 'updated_at' => $now]
            );
        }

        // ────────────────────────────────────────────────────────────────
        // SHARED — Committee Records (documents uploaded per committee)
        // ────────────────────────────────────────────────────────────────
        $records = [
            // Peace & Order
            ['peace-order', 'Resolution', 'Resolution No. 2026-01: Creation of Barangay Intelligence Network',   'Approved during regular session on January 10, 2026.', 'Admin'],
            ['peace-order', 'Report',     'Q1 2026 Peace & Order Situational Report',                             'Covers January–March 2026. Submitted to PNP Station 11.',    'Admin'],
            // Health
            ['health',      'Resolution', 'Resolution No. 2026-04: Approval of Health Center Operating Budget',  'Budget of ₱180,000 approved for Q2 2026 health programs.',   'Admin'],
            ['health',      'Report',     'Q1 2026 Health Statistics Report',                                     '342 consultations, 28 prenatal visits, 18 immunizations.',   'Admin'],
            // Education
            ['education',   'Resolution', 'Resolution No. 2026-06: Scholarship Grant 2026 Recipients',           '10 scholars approved. Total annual grant: ₱59,000.',         'Admin'],
            ['education',   'MOA',        'Memorandum of Agreement – QCU Scholarship Program 2026',              'MOA signed between Brgy. New Era and QCU on Feb 20, 2026.',  'Admin'],
            // Infrastructure
            ['infrastructure','Resolution','Resolution No. 2026-02: Approval of 20% Dev Fund Projects 2026',     'Six (6) projects approved totaling ₱3,000,000.',             'Admin'],
            ['infrastructure','Report',   'Infrastructure Progress Report – May 2026',                           'Three completed, three ongoing. 85% overall completion.',    'Admin'],
            // Environment
            ['environment', 'Resolution', 'Resolution No. 2026-05: Zero-Waste Barangay Ordinance',               'Ordinance prohibiting single-use plastics within barangay.', 'Admin'],
            ['environment', 'Report',     'Q1 2026 Solid Waste Management Report',                               'Total waste collected: 5,850 kg. Recyclables sold: ₱12,400.','Admin'],
            // Livelihood
            ['livelihood',  'Resolution', 'Resolution No. 2026-07: Livelihood Program Beneficiaries 2026',       '12 beneficiaries approved for Negosyo sa Barangay program.',  'Admin'],
            ['livelihood',  'MOA',        'MOA with DOLE Region NCR – Livelihood Grant Program',                 'Signed February 28, 2026. Total grant pool: ₱120,000.',      'Admin'],
            // Transport
            ['transport',   'Resolution', 'Resolution No. 2026-03: New Era TODA Franchise Renewal 2026',         '12 tricycle units renewed. ₱500 franchise fee per unit.',    'Admin'],
            ['transport',   'Report',     'TODA Compliance Report – Q1 2026',                                    'All 11 active units compliant with LTO requirements.',       'Admin'],
            // BDRRM
            ['bdrrm',       'Resolution', 'Resolution No. 2026-08: Adoption of BDRRM Plan 2026–2028',            'Three-year DRRM plan adopted per RA 10121.',                  'Admin'],
            ['bdrrm',       'Report',     'Q1 2026 BDRRM Incident Summary Report',                               'Five (5) incidents responded to. No fatalities recorded.',   'Admin'],
        ];
        foreach ($records as $r) {
            DB::table('committee_records')->updateOrInsert(
                ['committee_slug' => $r[0], 'title' => $r[2]],
                ['committee_slug' => $r[0], 'record_type' => $r[1], 'title' => $r[2],
                 'description' => $r[3], 'file_path' => null, 'file_type' => null,
                 'uploaded_by' => $r[4], 'created_at' => $now, 'updated_at' => $now]
            );
        }

        $this->command->info('✅ CommitteeSpecificSeeder: all 18 specific tables seeded.');
    }
}
