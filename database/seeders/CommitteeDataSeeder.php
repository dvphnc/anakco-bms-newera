<?php

namespace Database\Seeders;

use App\Models\CommitteeActivity;
use App\Models\CommitteeAttendance;
use App\Models\CommitteeInventory;
use App\Models\CommitteePartnership;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * CommitteeDataSeeder
 * ──────────────────────────────────────────────────────────────────────────
 * Seeds realistic demo data for all 8 Barangay New Era committees so that
 * each committee tab looks populated during a capstone / portfolio demo.
 *
 * Committees seeded (matching CommitteeController slugs):
 *   peace-order · health · education · infrastructure · environment
 *   livelihood  · transport · bdrrm
 *
 * Per committee:
 *   • 4–6 Activities / Accomplishments
 *   • 4–5 Attendance / Event log entries
 *   • 4–6 Inventory items
 *   • 2–3 Partnerships
 *
 * All inserts use updateOrCreate (unique on slug + title/item_name/partner_name)
 * so the seeder is safe to re-run.
 * ──────────────────────────────────────────────────────────────────────────
 */
class CommitteeDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedPeaceOrder();
        $this->seedHealth();
        $this->seedEducation();
        $this->seedInfrastructure();
        $this->seedEnvironment();
        $this->seedLivelihood();
        $this->seedTransport();
        $this->seedBdrrm();

        $this->command->info('✅  CommitteeDataSeeder: demo data seeded for all 8 committees.');
    }

    // ──────────────────────────────────────────────────────────────────────
    // PEACE & ORDER
    // ──────────────────────────────────────────────────────────────────────
    private function seedPeaceOrder(): void
    {
        $slug = 'peace-order';

        $activities = [
            ['type' => 'Activity',       'title' => 'Barangay Watchlist Orientation',     'desc' => 'Briefed 45 tanod volunteers on updated Barangay Watchlist protocols and proper documentation procedures.', 'date' => '2025-02-10', 'loc' => 'Barangay Hall, Multi-Purpose Hall', 'count' => 45,  'status' => 'Completed'],
            ['type' => 'Activity',       'title' => 'Night Patrol Coordination Meeting',  'desc' => 'Monthly coordination meeting with barangay tanod team leads to review patrol assignments and incident reports.', 'date' => '2025-03-05', 'loc' => 'Barangay Hall, Conference Room', 'count' => 18, 'status' => 'Completed'],
            ['type' => 'Activity',       'title' => 'Drug Awareness Campaign',             'desc' => 'Partnership with PNP QC District 6 for a community drug awareness drive targeting residents aged 15–35.', 'date' => '2025-04-20', 'loc' => 'Covered Court, Barangay New Era', 'count' => 210, 'status' => 'Completed'],
            ['type' => 'Activity',       'title' => 'Tanod Skills Training',               'desc' => 'Two-day basic self-defense and first-aid training for newly inducted barangay tanod members.', 'date' => '2025-05-12', 'loc' => 'Barangay Hall', 'count' => 30, 'status' => 'Completed'],
            ['type' => 'Accomplishment', 'title' => 'Reduced Crime Index Q1 2025',         'desc' => 'Crime index for Barangay New Era decreased by 12% in Q1 2025 compared to Q1 2024, attributed to increased night patrol frequency.', 'date' => '2025-04-01', 'loc' => 'N/A', 'count' => 0, 'status' => 'Completed'],
            ['type' => 'Accomplishment', 'title' => '24/7 CCTV Monitoring Launched',       'desc' => 'Installed 8 new CCTV cameras along main thoroughfares and enabled round-the-clock monitoring at the Barangay Hall Operations Center.', 'date' => '2025-06-15', 'loc' => 'Various locations, Barangay New Era', 'count' => 0, 'status' => 'Completed'],
        ];

        $attendances = [
            ['event' => 'Monthly Peace & Order Meeting — January',  'date' => '2025-01-08', 'venue' => 'Barangay Hall',         'total' => 22, 'notes' => 'Reviewed December 2024 incident reports and set January patrol schedule.'],
            ['event' => 'PDEA Community Briefing',                   'date' => '2025-02-25', 'venue' => 'QC City Hall Annex',    'total' => 5,  'notes' => 'Representatives attended PDEA briefing on anti-drug campaign updates.'],
            ['event' => 'Monthly Peace & Order Meeting — March',     'date' => '2025-03-12', 'venue' => 'Barangay Hall',         'total' => 20, 'notes' => 'Discussed blotter case resolution rates and tanod deployment.'],
            ['event' => 'Inter-Barangay Peace Summit, District VI',  'date' => '2025-04-18', 'venue' => 'QC Sports Club',        'total' => 4,  'notes' => 'Attended district-level peace summit with Punong Barangay and committee chair.'],
            ['event' => 'Monthly Peace & Order Meeting — May',       'date' => '2025-05-14', 'venue' => 'Barangay Hall',         'total' => 24, 'notes' => 'Reviewed CCTV installation progress and approved night patrol schedule.'],
        ];

        $inventory = [
            ['name' => 'Police Baton (Rattan)', 'cat' => 'Equipment', 'qty' => 20, 'unit' => 'pcs', 'cond' => 'Good',        'rem' => 'Issued to tanod on-duty roster'],
            ['name' => 'Flashlight (Heavy Duty)', 'cat' => 'Equipment', 'qty' => 15, 'unit' => 'pcs', 'cond' => 'Good',      'rem' => 'Rechargeable, assigned per purok'],
            ['name' => 'Rain Poncho',             'cat' => 'Supplies',  'qty' => 25, 'unit' => 'pcs', 'cond' => 'Good',       'rem' => 'For tanod night patrol use'],
            ['name' => 'Incident Report Forms',   'cat' => 'Documents', 'qty' => 200,'unit' => 'sheets','cond' => 'Good',     'rem' => 'Available at Barangay Hall'],
            ['name' => 'CCTV Monitor (24")',       'cat' => 'Equipment', 'qty' => 2,  'unit' => 'units','cond' => 'Good',     'rem' => 'Operations center, 24/7 display'],
            ['name' => 'Two-way Radio',            'cat' => 'Equipment', 'qty' => 8,  'unit' => 'units','cond' => 'Fair',     'rem' => 'Some need battery replacement'],
        ];

        $partnerships = [
            ['partner' => 'PNP QC District 6',         'type' => 'Government', 'mou' => '2024-01-15', 'validity' => '2026-01-14', 'contact' => 'P/Lt. Ricardo Santos',   'contact_no' => '(02) 8924-0001', 'desc' => 'Joint anti-drug and anti-crime operations coordination.'],
            ['partner' => 'Bureau of Fire Protection', 'type' => 'Government', 'mou' => '2024-03-01', 'validity' => '2026-02-28', 'contact' => 'SF/Insp. Maria Reyes',   'contact_no' => '(02) 8924-0120', 'desc' => 'Fire prevention awareness and rapid response coordination.'],
            ['partner' => 'Quezon City DPOS',          'type' => 'Government', 'mou' => '2024-07-10', 'validity' => '2026-07-09', 'contact' => 'Ms. Carla Manalo',       'contact_no' => '(02) 8988-4242', 'desc' => 'Deployment of public order and safety officers during barangay events.'],
        ];

        $this->insertActivities($slug, $activities);
        $this->insertAttendances($slug, $attendances);
        $this->insertInventory($slug, $inventory);
        $this->insertPartnerships($slug, $partnerships);
    }

    // ──────────────────────────────────────────────────────────────────────
    // HEALTH
    // ──────────────────────────────────────────────────────────────────────
    private function seedHealth(): void
    {
        $slug = 'health';

        $activities = [
            ['type' => 'Activity',       'title' => 'Free Medical & Dental Mission',     'desc' => 'Annual free medical and dental check-up in partnership with HealthNow QC. Served 312 residents across all puroks.', 'date' => '2025-01-25', 'loc' => 'Barangay Health Center', 'count' => 312, 'status' => 'Completed'],
            ['type' => 'Activity',       'title' => 'Maternal Health Seminar',           'desc' => 'Seminar on prenatal and postnatal care conducted by midwives from the QC Health Department for 60 expecting mothers.', 'date' => '2025-02-14', 'loc' => 'Multi-Purpose Hall', 'count' => 60, 'status' => 'Completed'],
            ['type' => 'Activity',       'title' => 'Senior Citizens Health Screening',  'desc' => 'Blood pressure, blood sugar, and BMI monitoring for senior citizens in the barangay. 155 seniors participated.', 'date' => '2025-03-18', 'loc' => 'Barangay Health Center', 'count' => 155, 'status' => 'Completed'],
            ['type' => 'Activity',       'title' => 'Dengue Prevention Drive',           'desc' => 'Community-wide clean-up and misting operation targeting dengue vector breeding sites. Four puroks covered.', 'date' => '2025-05-07', 'loc' => 'Purok 1–4, Barangay New Era', 'count' => 180, 'status' => 'Completed'],
            ['type' => 'Accomplishment', 'title' => 'Zero Measles Cases — Q1 2025',       'desc' => 'No recorded measles cases in Q1 2025 following the successful immunization drive in November 2024.', 'date' => '2025-04-05', 'loc' => 'N/A', 'count' => 0, 'status' => 'Completed'],
            ['type' => 'Accomplishment', 'title' => 'Barangay Health Center Upgrade',    'desc' => 'Completed renovation of the Barangay Health Center including a new consultation room and updated equipment funded by the QC Health Department.', 'date' => '2025-06-01', 'loc' => 'Barangay Health Center', 'count' => 0, 'status' => 'Completed'],
        ];

        $attendances = [
            ['event' => 'QC Health Department Quarterly Meeting — Q1',  'date' => '2025-01-10', 'venue' => 'QC Health Dept. Main Office', 'total' => 3, 'notes' => 'Q1 health priorities and budget allocation discussed.'],
            ['event' => 'Nutrition Month Planning Session',              'date' => '2025-06-20', 'venue' => 'Barangay Hall',               'total' => 12,'notes' => 'Finalized activities for July Nutrition Month celebration.'],
            ['event' => 'Barangay Health Workers Assembly',              'date' => '2025-02-05', 'venue' => 'Multi-Purpose Hall',          'total' => 28,'notes' => 'Orientation on updated health protocols and reporting system.'],
            ['event' => 'PhilHealth Barangay Outreach Meeting',          'date' => '2025-03-22', 'venue' => 'Barangay Hall',               'total' => 8, 'notes' => 'Discussed PhilHealth enrollment for unregistered residents.'],
            ['event' => 'Mental Health Awareness Forum',                 'date' => '2025-04-28', 'venue' => 'Multi-Purpose Hall',          'total' => 55,'notes' => 'Forum facilitated by a licensed psychologist from QC General Hospital.'],
        ];

        $inventory = [
            ['name' => 'Blood Pressure Monitor',    'cat' => 'Medical Equipment', 'qty' => 4,   'unit' => 'units',   'cond' => 'Good',        'rem' => 'Digital sphygmomanometer, calibrated monthly'],
            ['name' => 'Glucometer (Blood Sugar)',  'cat' => 'Medical Equipment', 'qty' => 2,   'unit' => 'units',   'cond' => 'Good',        'rem' => 'With test strips for routine screening'],
            ['name' => 'Paracetamol 500mg',         'cat' => 'Medicine',          'qty' => 500, 'unit' => 'tablets', 'cond' => 'Good',        'rem' => 'For indigent residents, dispense with clearance'],
            ['name' => 'Amoxicillin 500mg',         'cat' => 'Medicine',          'qty' => 200, 'unit' => 'capsules','cond' => 'Good',        'rem' => 'Prescription required'],
            ['name' => 'Vitamin C 500mg',           'cat' => 'Supplements',       'qty' => 1000,'unit' => 'tablets', 'cond' => 'Good',        'rem' => 'Distributed during senior citizens health day'],
            ['name' => 'Surgical Masks (Box)',      'cat' => 'Supplies',          'qty' => 10,  'unit' => 'boxes',   'cond' => 'Good',        'rem' => '50 pcs per box; for health center use'],
            ['name' => 'Weighing Scale (Baby)',     'cat' => 'Medical Equipment', 'qty' => 1,   'unit' => 'unit',    'cond' => 'Fair',        'rem' => 'Needs recalibration; request for replacement pending'],
        ];

        $partnerships = [
            ['partner' => 'QC Health Department',       'type' => 'Government', 'mou' => '2023-12-01', 'validity' => '2025-11-30', 'contact' => 'Dr. Lucia Santos',    'contact_no' => '(02) 8988-4200', 'desc' => 'Medical missions, health center supplies, and BHW training.'],
            ['partner' => 'Philippine Red Cross – QC',  'type' => 'NGO',        'mou' => '2024-02-10', 'validity' => '2026-02-09', 'contact' => 'Ms. Ana Cruz',        'contact_no' => '(02) 8527-8385', 'desc' => 'First-aid training for barangay health workers and tanod.'],
            ['partner' => 'PhilHealth – NCR North',     'type' => 'Government', 'mou' => '2024-05-01', 'validity' => '2026-04-30', 'contact' => 'Mr. Ramon Diaz',      'contact_no' => '(02) 8441-7442', 'desc' => 'PhilHealth enrollment drives and premium subsidy for indigent members.'],
        ];

        $this->insertActivities($slug, $activities);
        $this->insertAttendances($slug, $attendances);
        $this->insertInventory($slug, $inventory);
        $this->insertPartnerships($slug, $partnerships);
    }

    // ──────────────────────────────────────────────────────────────────────
    // EDUCATION
    // ──────────────────────────────────────────────────────────────────────
    private function seedEducation(): void
    {
        $slug = 'education';

        $activities = [
            ['type' => 'Activity',       'title' => 'Back-to-School Allowance Distribution',  'desc' => 'Distributed school supplies and financial assistance to 280 scholar-beneficiaries from Grades 1–12 and college.', 'date' => '2025-06-02', 'loc' => 'Multi-Purpose Hall', 'count' => 280, 'status' => 'Completed'],
            ['type' => 'Activity',       'title' => 'Literacy Tutorial Program Launch',       'desc' => 'Launched weekly literacy and numeracy tutorial sessions for 40 out-of-school youth (OSY) in the barangay.', 'date' => '2025-02-17', 'loc' => 'Barangay Hall, Tutorial Room', 'count' => 40, 'status' => 'Completed'],
            ['type' => 'Activity',       'title' => 'Barangay Scholarship Review Board',      'desc' => 'Convened to review and rank scholarship applications for 2025–2026 academic year. Approved 35 scholars.', 'date' => '2025-05-20', 'loc' => 'Barangay Hall, Conference Room', 'count' => 8, 'status' => 'Completed'],
            ['type' => 'Activity',       'title' => 'Career Guidance Seminar for Senior HS', 'desc' => 'Career orientation seminar for Grade 12 students of Barangay New Era residents in partnership with TESDA QC.', 'date' => '2025-04-10', 'loc' => 'Multi-Purpose Hall', 'count' => 95, 'status' => 'Completed'],
            ['type' => 'Accomplishment', 'title' => '35 Scholars Enrolled — SY 2025–2026',   'desc' => 'Successfully enrolled 35 deserving students under the Barangay New Era Scholarship Program for SY 2025–2026. Total annual grant: ₱525,000.', 'date' => '2025-07-01', 'loc' => 'N/A', 'count' => 0, 'status' => 'Completed'],
            ['type' => 'Accomplishment', 'title' => 'ALS Passers — October 2024 Batch',       'desc' => '18 out-of-school youth residents successfully passed the Alternative Learning System (ALS) accreditation exam.', 'date' => '2025-01-15', 'loc' => 'N/A', 'count' => 0, 'status' => 'Completed'],
        ];

        $attendances = [
            ['event' => 'DepEd QC District 6 School Heads Meeting',  'date' => '2025-01-20', 'venue' => 'DepEd QC District 6 Office', 'total' => 2, 'notes' => 'Discussed Brigada Eskwela participation and scholar monitoring.'],
            ['event' => 'TESDA Career Fair',                          'date' => '2025-03-05', 'venue' => 'QC Quezon Memorial Circle', 'total' => 4,  'notes' => 'Brought 35 OSY residents for TESDA scholarship enrollment.'],
            ['event' => 'Education Committee Regular Meeting — Q1',   'date' => '2025-02-10', 'venue' => 'Barangay Hall',             'total' => 6,  'notes' => 'Q1 education agenda: scholarship criteria and ALS partnership renewal.'],
            ['event' => 'Brigada Eskwela 2025',                       'date' => '2025-05-26', 'venue' => 'Partner Schools',           'total' => 50, 'notes' => 'Barangay volunteers assisted in classroom repair and painting.'],
            ['event' => 'Scholar Orientation — SY 2025–2026',         'date' => '2025-07-10', 'venue' => 'Multi-Purpose Hall',        'total' => 35, 'notes' => 'Orientation on scholar obligations, attendance monitoring, and grade submission.'],
        ];

        $inventory = [
            ['name' => 'Monobloc Chairs (Foldable)',  'cat' => 'Furniture',   'qty' => 50,  'unit' => 'pcs',    'cond' => 'Good',        'rem' => 'Used for tutorial sessions and assemblies'],
            ['name' => 'Plastic Tables (Long)',        'cat' => 'Furniture',   'qty' => 8,   'unit' => 'pcs',    'cond' => 'Good',        'rem' => 'Stored in tutorial room'],
            ['name' => 'Whiteboard (4x8 ft)',          'cat' => 'Equipment',   'qty' => 2,   'unit' => 'pcs',    'cond' => 'Good',        'rem' => 'Tutorial room use'],
            ['name' => 'Projector (LCD)',              'cat' => 'Equipment',   'qty' => 1,   'unit' => 'unit',   'cond' => 'Good',        'rem' => 'For presentations and screenings'],
            ['name' => 'School Supplies Kit (boxes)',  'cat' => 'Supplies',    'qty' => 12,  'unit' => 'boxes',  'cond' => 'Good',        'rem' => 'Ready for next distribution cycle'],
            ['name' => 'Laptop Computer',              'cat' => 'Equipment',   'qty' => 2,   'unit' => 'units',  'cond' => 'Fair',        'rem' => 'One for tutorial, one for admin; both need OS update'],
        ];

        $partnerships = [
            ['partner' => 'DepEd Quezon City Division',    'type' => 'Government', 'mou' => '2024-06-01', 'validity' => '2026-05-31', 'contact' => 'Mr. Edwin Bautista',   'contact_no' => '(02) 8988-4400', 'desc' => 'Scholarship co-funding and Brigada Eskwela annual collaboration.'],
            ['partner' => 'TESDA Quezon City District',    'type' => 'Government', 'mou' => '2024-08-15', 'validity' => '2026-08-14', 'contact' => 'Ms. Lorna Ramos',      'contact_no' => '(02) 8988-4500', 'desc' => 'Free TESDA courses for OSY and unemployed barangay residents.'],
            ['partner' => 'SM Foundation, Inc.',           'type' => 'Private',    'mou' => '2024-09-01', 'validity' => '2025-08-31', 'contact' => 'Ms. Gina Lim',         'contact_no' => '(02) 8831-1000', 'desc' => 'Annual donation of school supplies for 100 Grade 1–6 beneficiaries.'],
        ];

        $this->insertActivities($slug, $activities);
        $this->insertAttendances($slug, $attendances);
        $this->insertInventory($slug, $inventory);
        $this->insertPartnerships($slug, $partnerships);
    }

    // ──────────────────────────────────────────────────────────────────────
    // INFRASTRUCTURE
    // ──────────────────────────────────────────────────────────────────────
    private function seedInfrastructure(): void
    {
        $slug = 'infrastructure';

        $activities = [
            ['type' => 'Activity',       'title' => 'Purok 2 Road Repair & Patching',         'desc' => 'Pothole patching along 3 road segments in Purok 2 - Rosal area. Used 12 bags of asphalt mix; 280 linear meters repaired.', 'date' => '2025-01-28', 'loc' => 'Purok 2 - Rosal, Barangay New Era', 'count' => 0, 'status' => 'Completed'],
            ['type' => 'Activity',       'title' => 'Barangay Hall Roof Repair',               'desc' => 'Replacement of damaged roofing sheets and gutters on the east wing of the Barangay Hall. Completed within budget at ₱85,000.', 'date' => '2025-02-20', 'loc' => 'Barangay Hall', 'count' => 0, 'status' => 'Completed'],
            ['type' => 'Activity',       'title' => 'Streetlight Installation — Purok 5',      'desc' => 'Installed 12 LED streetlights along Purok 5 - Dahlia main road in coordination with Meralco and QCEPD.', 'date' => '2025-03-15', 'loc' => 'Purok 5 - Dahlia, Barangay New Era', 'count' => 0, 'status' => 'Completed'],
            ['type' => 'Activity',       'title' => 'Multi-Purpose Hall Painting',             'desc' => 'Interior and exterior repainting of the Multi-Purpose Hall. Volunteer residents assisted in preparation and painting.', 'date' => '2025-04-25', 'loc' => 'Multi-Purpose Hall', 'count' => 35, 'status' => 'Completed'],
            ['type' => 'Accomplishment', 'title' => '12 LED Streetlights Activated — Purok 5','desc' => 'Twelve new LED streetlights in Purok 5 - Dahlia are now operational, improving night visibility and reducing crime in the area.', 'date' => '2025-03-30', 'loc' => 'Purok 5 - Dahlia', 'count' => 0, 'status' => 'Completed'],
            ['type' => 'Accomplishment', 'title' => 'DPWH Road Widening — Phase 1 Complete',  'desc' => 'Phase 1 of the DPWH-funded road widening along the main barangay road successfully completed, adding 1.5m sidewalk on both sides.', 'date' => '2025-06-30', 'loc' => 'Main Road, Barangay New Era', 'count' => 0, 'status' => 'Completed'],
        ];

        $attendances = [
            ['event' => 'DPWH Road Project Coordination Meeting',    'date' => '2025-01-15', 'venue' => 'DPWH NCR Office',           'total' => 3, 'notes' => 'Technical meeting for road widening project alignment.'],
            ['event' => 'Infrastructure Committee Meeting — Q1',     'date' => '2025-02-03', 'venue' => 'Barangay Hall',             'total' => 7, 'notes' => 'Prioritized Q1 infrastructure projects and approved budgets.'],
            ['event' => 'DILG Barangay Development Fund Workshop',   'date' => '2025-03-08', 'venue' => 'DILG NCR Office, Paco',    'total' => 2, 'notes' => 'Workshop on proper BDF liquidation and procurement compliance.'],
            ['event' => 'Purok 5 Streetlight Turnover Ceremony',     'date' => '2025-03-30', 'venue' => 'Purok 5 - Dahlia',         'total' => 60,'notes' => 'Community celebration with Kagawad Freddie Marcial and Meralco rep.'],
            ['event' => 'Infrastructure Committee Meeting — Q2',     'date' => '2025-05-07', 'venue' => 'Barangay Hall',             'total' => 6, 'notes' => 'Q2 project review: Hall painting and DPWH Phase 2 planning.'],
        ];

        $inventory = [
            ['name' => 'Shovel (Round-Point)',      'cat' => 'Tools',       'qty' => 8,   'unit' => 'pcs',    'cond' => 'Good',        'rem' => 'For community projects'],
            ['name' => 'Rake (Heavy Duty)',         'cat' => 'Tools',       'qty' => 6,   'unit' => 'pcs',    'cond' => 'Good',        'rem' => 'For road and drainage cleaning'],
            ['name' => 'Wheelbarrow',               'cat' => 'Equipment',   'qty' => 3,   'unit' => 'units',  'cond' => 'Fair',        'rem' => 'One has a flat tire; for replacement'],
            ['name' => 'Paint Roller Set',          'cat' => 'Tools',       'qty' => 10,  'unit' => 'sets',   'cond' => 'Good',        'rem' => 'After multi-purpose hall project; clean and stored'],
            ['name' => 'Cement Bags (remaining)',   'cat' => 'Materials',   'qty' => 15,  'unit' => 'bags',   'cond' => 'Good',        'rem' => 'Leftover from road patching; store in dry area'],
            ['name' => 'Steel Bar (10mm)',          'cat' => 'Materials',   'qty' => 20,  'unit' => 'pcs',    'cond' => 'Good',        'rem' => 'Reserved for upcoming drainage project'],
        ];

        $partnerships = [
            ['partner' => 'DPWH NCR Third District',       'type' => 'Government', 'mou' => '2024-04-01', 'validity' => '2026-03-31', 'contact' => 'Engr. Mario Gonzales',  'contact_no' => '(02) 8927-2500', 'desc' => 'Road widening and drainage improvement projects under DPWH funding.'],
            ['partner' => 'Meralco – Quezon City North',   'type' => 'Private',    'mou' => '2024-07-01', 'validity' => '2026-06-30', 'contact' => 'Engr. Raul Domingo',    'contact_no' => '1-800-10-637-2526','desc' => 'Coordination for streetlight installation and electrical infrastructure.'],
            ['partner' => 'MWSS – QC East Zone',           'type' => 'Government', 'mou' => '2024-09-15', 'validity' => '2026-09-14', 'contact' => 'Engr. Teresa Lagman',   'contact_no' => '(02) 8921-7000', 'desc' => 'Water supply line maintenance and barangay-level connection concerns.'],
        ];

        $this->insertActivities($slug, $activities);
        $this->insertAttendances($slug, $attendances);
        $this->insertInventory($slug, $inventory);
        $this->insertPartnerships($slug, $partnerships);
    }

    // ──────────────────────────────────────────────────────────────────────
    // ENVIRONMENT
    // ──────────────────────────────────────────────────────────────────────
    private function seedEnvironment(): void
    {
        $slug = 'environment';

        $activities = [
            ['type' => 'Activity',       'title' => 'Oplan Linis — Purok 6 Clean-Up Drive',  'desc' => 'Community clean-up drive at Purok 6 - Camia with participation of 95 resident volunteers. Collected 2.3 tons of waste.', 'date' => '2025-01-18', 'loc' => 'Purok 6 - Camia', 'count' => 95, 'status' => 'Completed'],
            ['type' => 'Activity',       'title' => 'Tree Planting at Barangay Perimeter',   'desc' => 'Planted 80 native tree saplings along the barangay perimeter road in partnership with DENR and QC Environmental Protection.', 'date' => '2025-06-05', 'loc' => 'Perimeter Road, Barangay New Era', 'count' => 45, 'status' => 'Completed'],
            ['type' => 'Activity',       'title' => 'Segregation Awareness Campaign',         'desc' => 'Door-to-door campaign in all 6 puroks educating residents on proper solid waste segregation under RA 9003.', 'date' => '2025-02-22', 'loc' => 'All Puroks, Barangay New Era', 'count' => 120, 'status' => 'Completed'],
            ['type' => 'Activity',       'title' => 'Estero Rehabilitation — Purok 4',        'desc' => 'De-clogging and cleaning of the estero channel in Purok 4 - Gumamela to prevent flooding during rainy season.', 'date' => '2025-04-14', 'loc' => 'Purok 4 - Gumamela', 'count' => 60, 'status' => 'Completed'],
            ['type' => 'Accomplishment', 'title' => 'Zero Open Burning Violations — Q1',     'desc' => 'No open burning violations recorded in Q1 2025 after intensified barangay enforcement patrols and community education.', 'date' => '2025-04-01', 'loc' => 'N/A', 'count' => 0, 'status' => 'Completed'],
            ['type' => 'Accomplishment', 'title' => 'MRF Capacity Expansion Completed',       'desc' => 'Materials Recovery Facility expanded by 30 sq m with additional sorting bins, improving daily waste processing capacity by 40%.', 'date' => '2025-05-15', 'loc' => 'Barangay MRF, New Era', 'count' => 0, 'status' => 'Completed'],
        ];

        $attendances = [
            ['event' => 'DENR-EMB Community Engagement Forum',    'date' => '2025-01-28', 'venue' => 'DENR EMB Regional Office', 'total' => 2, 'notes' => 'Forum on RA 9003 compliance monitoring and MRF standards.'],
            ['event' => 'Environment Committee Meeting — Q1',     'date' => '2025-02-10', 'venue' => 'Barangay Hall',             'total' => 8, 'notes' => 'Q1 solid waste management review and upcoming activity planning.'],
            ['event' => 'World Environment Day Celebration',      'date' => '2025-06-05', 'venue' => 'Quezon Memorial Circle',    'total' => 5, 'notes' => 'Barangay represented at QC city-wide tree planting event.'],
            ['event' => 'Estero Rehabilitation Partnership Meet', 'date' => '2025-03-20', 'venue' => 'MMDA Office, Quezon City', 'total' => 3, 'notes' => 'Coordination for MMDA-assisted estero clean-up schedule.'],
            ['event' => 'MRF Operators Training',                 'date' => '2025-04-22', 'venue' => 'QC Eco-Center',            'total' => 6, 'notes' => 'Training for MRF staff on waste sorting and record-keeping.'],
        ];

        $inventory = [
            ['name' => 'Waste Segregation Bins (Set of 4)',   'cat' => 'Equipment',   'qty' => 12,  'unit' => 'sets',   'cond' => 'Good',        'rem' => 'Distributed to puroks; 2 sets per purok'],
            ['name' => 'Push Cart (MRF)',                     'cat' => 'Equipment',   'qty' => 4,   'unit' => 'units',  'cond' => 'Good',        'rem' => 'For waste collection at MRF'],
            ['name' => 'Bolo / Machete',                      'cat' => 'Tools',       'qty' => 10,  'unit' => 'pcs',    'cond' => 'Good',        'rem' => 'For estero clearing and vegetation management'],
            ['name' => 'Safety Gloves (pair)',                 'cat' => 'Supplies',    'qty' => 30,  'unit' => 'pairs',  'cond' => 'Good',        'rem' => 'For MRF and clean-up drive use'],
            ['name' => 'Weighing Scale (Platform)',           'cat' => 'Equipment',   'qty' => 1,   'unit' => 'unit',   'cond' => 'Good',        'rem' => 'At MRF for waste tonnage recording'],
            ['name' => 'IEC Posters (Segregation)',           'cat' => 'Materials',   'qty' => 50,  'unit' => 'pcs',    'cond' => 'Good',        'rem' => 'For posting in puroks and public areas'],
        ];

        $partnerships = [
            ['partner' => 'DENR-EMB Region III',          'type' => 'Government', 'mou' => '2024-03-15', 'validity' => '2026-03-14', 'contact' => 'Engr. Noel Reyes',     'contact_no' => '(02) 8920-2251', 'desc' => 'Technical assistance for MRF management and RA 9003 compliance.'],
            ['partner' => 'QC Environmental Protection',  'type' => 'Government', 'mou' => '2024-05-01', 'validity' => '2026-04-30', 'contact' => 'Ms. Cecile Torres',     'contact_no' => '(02) 8988-4100', 'desc' => 'Coordination for city-wide clean-up events and tree-planting drives.'],
            ['partner' => 'EcoPrime Waste Solutions',     'type' => 'Private',    'mou' => '2024-10-01', 'validity' => '2025-09-30', 'contact' => 'Mr. Bert Lagman',      'contact_no' => '0917-456-7890',  'desc' => 'Collection of residual waste beyond MRF capacity; quarterly hauling.'],
        ];

        $this->insertActivities($slug, $activities);
        $this->insertAttendances($slug, $attendances);
        $this->insertInventory($slug, $inventory);
        $this->insertPartnerships($slug, $partnerships);
    }

    // ──────────────────────────────────────────────────────────────────────
    // LIVELIHOOD
    // ──────────────────────────────────────────────────────────────────────
    private function seedLivelihood(): void
    {
        $slug = 'livelihood';

        $activities = [
            ['type' => 'Activity',       'title' => 'Livelihood Skills Training — Food Processing', 'desc' => 'Three-day hands-on food processing and packaging training for 40 women beneficiaries in partnership with DTI QC.', 'date' => '2025-02-03', 'loc' => 'Multi-Purpose Hall', 'count' => 40, 'status' => 'Completed'],
            ['type' => 'Activity',       'title' => 'Sewing & Dressmaking Course',                  'desc' => 'Four-week NCII dressmaking course facilitated by TESDA for 25 residents seeking employment in garments sector.', 'date' => '2025-03-10', 'loc' => 'Livelihood Training Center', 'count' => 25, 'status' => 'Completed'],
            ['type' => 'Activity',       'title' => 'Micro-Enterprise Startup Workshop',             'desc' => 'Two-day business basics workshop covering capital, pricing, and online selling. 55 participants from all 6 puroks.', 'date' => '2025-04-08', 'loc' => 'Multi-Purpose Hall', 'count' => 55, 'status' => 'Completed'],
            ['type' => 'Activity',       'title' => 'Barangay Job Fair 2025',                        'desc' => 'Annual job fair with 18 partner employers from manufacturing, BPO, and retail sectors. 120 job seekers registered.', 'date' => '2025-05-28', 'loc' => 'Covered Court, Barangay New Era', 'count' => 120, 'status' => 'Completed'],
            ['type' => 'Accomplishment', 'title' => '62 Residents Placed — Job Fair 2025',          'desc' => '62 barangay residents successfully obtained employment from the 2025 Job Fair. Highest placement rate in 3 years.', 'date' => '2025-06-30', 'loc' => 'N/A', 'count' => 0, 'status' => 'Completed'],
            ['type' => 'Accomplishment', 'title' => '15 Micro-Enterprises Registered',               'desc' => 'Assisted 15 new micro-enterprise owners from the training cohort in registering their businesses at the DTI and barangay.', 'date' => '2025-05-15', 'loc' => 'N/A', 'count' => 0, 'status' => 'Completed'],
        ];

        $attendances = [
            ['event' => 'DTI QC Livelihood Forum',              'date' => '2025-01-22', 'venue' => 'DTI QC Office',            'total' => 3, 'notes' => 'Discussed 2025 KMME and Negosyo Center programs.'],
            ['event' => 'Livelihood Committee Meeting — Q1',    'date' => '2025-02-15', 'venue' => 'Barangay Hall',             'total' => 7, 'notes' => 'Q1 livelihood calendar: training slots and partner coordination.'],
            ['event' => 'DOLE Job Placement Briefing',          'date' => '2025-03-25', 'venue' => 'DOLE NCR Field Office',    'total' => 2, 'notes' => 'Briefing on job placement programs and referral system.'],
            ['event' => 'Job Fair Pre-Event Coordination',      'date' => '2025-05-10', 'venue' => 'Barangay Hall',             'total' => 12,'notes' => 'Final employer confirmations and logistics for May 28 Job Fair.'],
            ['event' => 'Livelihood Committee Meeting — Q2',    'date' => '2025-05-20', 'venue' => 'Barangay Hall',             'total' => 8, 'notes' => 'Post-training evaluation and Q3 activity pipeline.'],
        ];

        $inventory = [
            ['name' => 'Industrial Sewing Machine',   'cat' => 'Equipment',   'qty' => 6,   'unit' => 'units',  'cond' => 'Good',        'rem' => 'For dressmaking training; stored in training center'],
            ['name' => 'Food Dehydrator',             'cat' => 'Equipment',   'qty' => 2,   'unit' => 'units',  'cond' => 'Good',        'rem' => 'Food processing training use'],
            ['name' => 'Vacuum Sealer',               'cat' => 'Equipment',   'qty' => 2,   'unit' => 'units',  'cond' => 'Good',        'rem' => 'Packaging training equipment'],
            ['name' => 'Training Modules (printed)',  'cat' => 'Materials',   'qty' => 80,  'unit' => 'copies', 'cond' => 'Good',        'rem' => 'Business basics and NCII dressmaking modules'],
            ['name' => 'Portable Projector',          'cat' => 'Equipment',   'qty' => 1,   'unit' => 'unit',   'cond' => 'Fair',        'rem' => 'Lamp nearing end of life; request for replacement submitted'],
            ['name' => 'Measuring Tape (Tailoring)',  'cat' => 'Tools',       'qty' => 15,  'unit' => 'pcs',    'cond' => 'Good',        'rem' => 'For dressmaking participants'],
        ];

        $partnerships = [
            ['partner' => 'DTI Quezon City',               'type' => 'Government', 'mou' => '2024-02-01', 'validity' => '2026-01-31', 'contact' => 'Ms. Joanna Vergara',   'contact_no' => '(02) 8988-3100', 'desc' => 'KMME training, Negosyo Center services, and business registration facilitation.'],
            ['partner' => 'TESDA Quezon City North',       'type' => 'Government', 'mou' => '2024-04-01', 'validity' => '2026-03-31', 'contact' => 'Mr. Alan Tan',         'contact_no' => '(02) 8988-4500', 'desc' => 'NCII skills training delivery and assessment vouchers for residents.'],
            ['partner' => 'DOLE NCR Field Office',         'type' => 'Government', 'mou' => '2024-06-15', 'validity' => '2026-06-14', 'contact' => 'Ms. Rowena Castillo',  'contact_no' => '(02) 8527-8000', 'desc' => 'Job placement programs, TUPAD employment, and livelihood grants.'],
        ];

        $this->insertActivities($slug, $activities);
        $this->insertAttendances($slug, $attendances);
        $this->insertInventory($slug, $inventory);
        $this->insertPartnerships($slug, $partnerships);
    }

    // ──────────────────────────────────────────────────────────────────────
    // TRANSPORT & COMMUNICATIONS
    // ──────────────────────────────────────────────────────────────────────
    private function seedTransport(): void
    {
        $slug = 'transport';

        $activities = [
            ['type' => 'Activity',       'title' => 'No-Contact Apprehension Orientation',    'desc' => 'Information drive on QC ordinances regarding illegal parking and tricycle terminal violations. 80 tricycle drivers attended.', 'date' => '2025-01-30', 'loc' => 'Multi-Purpose Hall', 'count' => 80, 'status' => 'Completed'],
            ['type' => 'Activity',       'title' => 'Tricycle Franchise Registry Update',      'desc' => 'Updated and digitized the barangay tricycle franchise registry. 165 units recorded with complete owner details.', 'date' => '2025-03-05', 'loc' => 'Barangay Hall', 'count' => 0, 'status' => 'Completed'],
            ['type' => 'Activity',       'title' => 'Road Safety Month Campaign',              'desc' => 'Partnership with LTO QC for road safety seminars and distribution of reflectorized vests to 50 tricycle drivers.', 'date' => '2025-04-17', 'loc' => 'Various locations, Barangay New Era', 'count' => 120, 'status' => 'Completed'],
            ['type' => 'Activity',       'title' => 'Cable Network Inspection Drive',          'desc' => 'Inspection of aerial cables and utility lines in coordination with DICT to identify unauthorized attachments and safety hazards.', 'date' => '2025-05-08', 'loc' => 'All Puroks, Barangay New Era', 'count' => 0, 'status' => 'Completed'],
            ['type' => 'Accomplishment', 'title' => 'Free Wi-Fi Hotspots — 3 Sites Active',   'desc' => 'Three Free Wi-Fi for All (DICT-PCARI) hotspot sites are now active at the Barangay Hall, Covered Court, and Health Center.', 'date' => '2025-02-15', 'loc' => 'Barangay Hall / Covered Court / Health Center', 'count' => 0, 'status' => 'Completed'],
            ['type' => 'Accomplishment', 'title' => 'Tricycle Terminal Relocation Approved',  'desc' => 'Successfully negotiated and implemented the relocation of the main tricycle terminal to reduce congestion at the barangay hall entrance.', 'date' => '2025-05-30', 'loc' => 'Alternate Terminal Site, New Era', 'count' => 0, 'status' => 'Completed'],
        ];

        $attendances = [
            ['event' => 'LTO QC Road Safety Month Kickoff',       'date' => '2025-04-01', 'venue' => 'LTO QC District Office',    'total' => 2, 'notes' => 'Coordination meeting for April Road Safety Month programs.'],
            ['event' => 'Transport Committee Meeting — Q1',       'date' => '2025-02-18', 'venue' => 'Barangay Hall',              'total' => 6, 'notes' => 'Reviewed franchise renewal schedule and tricycle terminal concerns.'],
            ['event' => 'DICT Free Wi-Fi Turnover Ceremony',      'date' => '2025-02-15', 'venue' => 'Barangay Hall',              'total' => 15,'notes' => 'Official turnover of 3 DICT Free Wi-Fi units to the barangay.'],
            ['event' => 'QC Transport & Traffic Management Meet', 'date' => '2025-03-18', 'venue' => 'QC City Hall',               'total' => 2, 'notes' => 'Discussion on barangay road scheme changes and signage requirements.'],
            ['event' => 'Transport Committee Meeting — Q2',       'date' => '2025-05-12', 'venue' => 'Barangay Hall',              'total' => 7, 'notes' => 'Terminal relocation and cable inspection results presented.'],
        ];

        $inventory = [
            ['name' => 'Road Safety Signage Set',          'cat' => 'Materials',   'qty' => 15,  'unit' => 'pcs',    'cond' => 'Good',        'rem' => 'Speed limit, no parking, children crossing'],
            ['name' => 'Traffic Cone (Orange)',             'cat' => 'Equipment',   'qty' => 24,  'unit' => 'pcs',    'cond' => 'Good',        'rem' => 'For events and road works'],
            ['name' => 'Reflectorized Vest',               'cat' => 'Supplies',    'qty' => 20,  'unit' => 'pcs',    'cond' => 'Good',        'rem' => 'Remaining stock after road safety campaign'],
            ['name' => 'Megaphone / Bullhorn',             'cat' => 'Equipment',   'qty' => 2,   'unit' => 'units',  'cond' => 'Good',        'rem' => 'For traffic management during events'],
            ['name' => 'Wi-Fi Router (DICT Issued)',       'cat' => 'Equipment',   'qty' => 3,   'unit' => 'units',  'cond' => 'Good',        'rem' => 'Barangay Hall, Covered Court, Health Center'],
            ['name' => 'Extension Cord (10m)',             'cat' => 'Supplies',    'qty' => 5,   'unit' => 'pcs',    'cond' => 'Fair',        'rem' => 'Some need electrical tape repair'],
        ];

        $partnerships = [
            ['partner' => 'LTO Quezon City District',       'type' => 'Government', 'mou' => '2024-03-01', 'validity' => '2026-02-28', 'contact' => 'Mr. Crispin Valera',   'contact_no' => '(02) 8924-0200', 'desc' => 'Road safety campaigns and driver orientation in the barangay.'],
            ['partner' => 'DICT – Region NCR',              'type' => 'Government', 'mou' => '2024-09-01', 'validity' => '2026-08-31', 'contact' => 'Engr. Jan Michael Cruz','contact_no' => '(02) 8920-0101', 'desc' => 'Free Wi-Fi for All program deployment and maintenance.'],
            ['partner' => 'Tricycle Operators & Drivers Assoc.', 'type' => 'Private', 'mou' => '2024-01-10', 'validity' => '2026-01-09', 'contact' => 'Mr. Dionisio Puno',   'contact_no' => '0918-777-1234',  'desc' => 'Franchise management, terminal discipline, and safety compliance.'],
        ];

        $this->insertActivities($slug, $activities);
        $this->insertAttendances($slug, $attendances);
        $this->insertInventory($slug, $inventory);
        $this->insertPartnerships($slug, $partnerships);
    }

    // ──────────────────────────────────────────────────────────────────────
    // BDRRM (Barangay Disaster Risk Reduction Management)
    // ──────────────────────────────────────────────────────────────────────
    private function seedBdrrm(): void
    {
        $slug = 'bdrrm';

        $activities = [
            ['type' => 'Activity',       'title' => 'Earthquake Drill — The Big One Simulation',  'desc' => 'Participated in Metro Manila-wide earthquake drill. 320 residents and barangay staff underwent duck-cover-hold procedures.', 'date' => '2025-02-07', 'loc' => 'Covered Court & Surrounding Streets', 'count' => 320, 'status' => 'Completed'],
            ['type' => 'Activity',       'title' => 'Fire Prevention Month Drive',                 'desc' => 'Week-long fire safety inspection of homes in all 6 puroks. Distributed fire safety brochures and identified 12 high-risk households.', 'date' => '2025-03-10', 'loc' => 'All Puroks, Barangay New Era', 'count' => 180, 'status' => 'Completed'],
            ['type' => 'Activity',       'title' => 'Typhoon Preparedness Forum',                  'desc' => 'Community forum on typhoon preparedness, evacuation routes, and emergency contact protocols before the rainy season.', 'date' => '2025-05-20', 'loc' => 'Multi-Purpose Hall', 'count' => 145, 'status' => 'Completed'],
            ['type' => 'Activity',       'title' => 'BDRRM Response Team Training',               'desc' => 'Two-day basic disaster response and search-and-rescue training for 35 BDRRM volunteers conducted by NDRRMC-NCR.', 'date' => '2025-04-24', 'loc' => 'QC DRRMO Training Center', 'count' => 35, 'status' => 'Completed'],
            ['type' => 'Accomplishment', 'title' => 'BDRRM Contingency Plan 2025 Approved',       'desc' => 'Updated Barangay Contingency Plan for 2025 approved by QC DRRMO and filed with NDRRMC. Covers earthquake, typhoon, and fire scenarios.', 'date' => '2025-01-30', 'loc' => 'N/A', 'count' => 0, 'status' => 'Completed'],
            ['type' => 'Accomplishment', 'title' => 'Early Warning System Upgraded',              'desc' => 'Installed 2 new multi-tone sirens and upgraded the barangay early warning broadcast system in partnership with QC DRRMO.', 'date' => '2025-06-10', 'loc' => 'Barangay Hall / Purok 3 Junction', 'count' => 0, 'status' => 'Completed'],
        ];

        $attendances = [
            ['event' => 'QC DRRMO Quarterly Assembly — Q1',        'date' => '2025-01-20', 'venue' => 'QC DRRMO Main Office',       'total' => 3, 'notes' => 'Q1 disaster preparedness priorities and BDRRM fund utilization review.'],
            ['event' => 'NDRRMC Metro Manila Coordination Meeting', 'date' => '2025-02-20', 'venue' => '(Virtual via Zoom)',         'total' => 2, 'notes' => 'Post-earthquake drill debrief and multi-hazard planning updates.'],
            ['event' => 'Fire Prevention Month Coordination',       'date' => '2025-03-03', 'venue' => 'BFP QC Station 6',          'total' => 4, 'notes' => 'Coordination for fire inspection schedule and IEC material distribution.'],
            ['event' => 'Search and Rescue Training — Day 1',       'date' => '2025-04-24', 'venue' => 'QC DRRMO Training Center',  'total' => 35,'notes' => 'Basic USAR and first-responder protocols. Day 1 of 2.'],
            ['event' => 'Pre-Rainy Season Preparedness Meeting',    'date' => '2025-05-08', 'venue' => 'Barangay Hall',              'total' => 18,'notes' => 'Finalized evacuation routes, identified flood-prone households.'],
        ];

        $inventory = [
            ['name' => 'Life Vest (Orange)',              'cat' => 'Emergency Equipment', 'qty' => 30,  'unit' => 'pcs',    'cond' => 'Good',        'rem' => 'Standard rescue lifevest; stored in BDRRM stockroom'],
            ['name' => 'First Aid Kit (Complete)',        'cat' => 'Medical Supplies',    'qty' => 10,  'unit' => 'kits',   'cond' => 'Good',        'rem' => 'Restocked quarterly; expiry checked'],
            ['name' => 'Emergency Food Pack (3-Day)',     'cat' => 'Relief Supplies',     'qty' => 200, 'unit' => 'packs',  'cond' => 'Good',        'rem' => 'Pre-positioned at evacuation center; rotate stock every 6 months'],
            ['name' => 'Portable Generator (2kW)',        'cat' => 'Equipment',           'qty' => 1,   'unit' => 'unit',   'cond' => 'Good',        'rem' => 'Tested monthly; fuel kept in locked cabinet'],
            ['name' => 'Folding Cots / Stretcher',       'cat' => 'Equipment',           'qty' => 20,  'unit' => 'pcs',    'cond' => 'Good',        'rem' => 'For evacuation center use'],
            ['name' => 'Megaphone / Bullhorn',            'cat' => 'Equipment',           'qty' => 3,   'unit' => 'units',  'cond' => 'Good',        'rem' => 'For evacuation announcements; 1 per sector'],
            ['name' => 'Rain Boot (various sizes)',       'cat' => 'Supplies',            'qty' => 24,  'unit' => 'pairs',  'cond' => 'Fair',        'rem' => 'Some soles worn; request for 10 replacement pairs pending'],
        ];

        $partnerships = [
            ['partner' => 'QC Disaster Risk Reduction & Mgt. Office', 'type' => 'Government', 'mou' => '2023-12-01', 'validity' => '2025-11-30', 'contact' => 'Dir. Aldrin Cuaresma',  'contact_no' => '(02) 8988-4100', 'desc' => 'BDRRM fund co-financing, rescue team training, and early warning system support.'],
            ['partner' => 'Bureau of Fire Protection — QC Station 6', 'type' => 'Government', 'mou' => '2024-02-15', 'validity' => '2026-02-14', 'contact' => 'SFO3 Dante Reyes',       'contact_no' => '(02) 8924-0120', 'desc' => 'Fire prevention inspections and rapid response to barangay fire incidents.'],
            ['partner' => 'Philippine Red Cross — QC Chapter',        'type' => 'NGO',        'mou' => '2024-06-01', 'validity' => '2026-05-31', 'contact' => 'Ms. Patricia Molina',   'contact_no' => '(02) 8527-8385', 'desc' => 'Disaster relief goods, volunteer training, and blood donation drives.'],
        ];

        $this->insertActivities($slug, $activities);
        $this->insertAttendances($slug, $attendances);
        $this->insertInventory($slug, $inventory);
        $this->insertPartnerships($slug, $partnerships);
    }

    // ──────────────────────────────────────────────────────────────────────
    // HELPERS
    // ──────────────────────────────────────────────────────────────────────

    private function insertActivities(string $slug, array $items): void
    {
        foreach ($items as $a) {
            CommitteeActivity::updateOrCreate(
                ['committee_slug' => $slug, 'title' => $a['title']],
                [
                    'activity_type'      => $a['type'],
                    'description'        => $a['desc'],
                    'activity_date'      => $a['date'],
                    'location'           => $a['loc'],
                    'participants_count' => $a['count'],
                    'status'             => $a['status'],
                    'logged_by'          => 1,
                ]
            );
        }
    }

    private function insertAttendances(string $slug, array $items): void
    {
        foreach ($items as $a) {
            CommitteeAttendance::updateOrCreate(
                ['committee_slug' => $slug, 'event_name' => $a['event']],
                [
                    'event_date'     => $a['date'],
                    'venue'          => $a['venue'],
                    'total_attendees'=> $a['total'],
                    'notes'          => $a['notes'],
                    'recorded_by'    => 1,
                ]
            );
        }
    }

    private function insertInventory(string $slug, array $items): void
    {
        foreach ($items as $i) {
            CommitteeInventory::updateOrCreate(
                ['committee_slug' => $slug, 'item_name' => $i['name']],
                [
                    'category'    => $i['cat'],
                    'quantity'    => $i['qty'],
                    'unit'        => $i['unit'],
                    'condition'   => $i['cond'],
                    'remarks'     => $i['rem'],
                    'recorded_by' => 1,
                ]
            );
        }
    }

    private function insertPartnerships(string $slug, array $items): void
    {
        foreach ($items as $p) {
            CommitteePartnership::updateOrCreate(
                ['committee_slug' => $slug, 'partner_name' => $p['partner']],
                [
                    'partner_type'   => $p['type'],
                    'mou_date'       => $p['mou'],
                    'validity_date'  => $p['validity'],
                    'contact_person' => $p['contact'],
                    'contact_number' => $p['contact_no'],
                    'description'    => $p['desc'],
                ]
            );
        }
    }
}
