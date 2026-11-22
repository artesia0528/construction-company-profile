<?php

namespace Database\Seeders;

use App\Models\CompanySetting;
use App\Models\ContactMessage;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\Service;
use App\Models\Statistic;
use App\Models\Team;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@nusantarabuild.co.id'],
            [
                'name' => 'Admin Nusantara Build',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        );

        CompanySetting::query()->updateOrCreate(['id' => 1], CompanySetting::factory()->definition());

        Statistic::query()->delete();
        Statistic::query()->insert([
            ['label' => 'Years of Experience', 'value' => '15+', 'icon' => 'calendar', 'display_order' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['label' => 'Completed Projects', 'value' => '250+', 'icon' => 'building-office', 'display_order' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['label' => 'Professional Team', 'value' => '80+', 'icon' => 'users', 'display_order' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['label' => 'Satisfied Clients', 'value' => '120+', 'icon' => 'hand-thumb-up', 'display_order' => 4, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        Service::query()->delete();
        $services = [
            ['name' => 'General Construction', 'slug' => 'general-construction', 'short_description' => 'End-to-end construction delivery for commercial and industrial developments.', 'full_description' => "Nusantara Build Construction provides comprehensive general construction services from planning to handover. Our team manages procurement, scheduling, quality control, and site safety to ensure every project is delivered on time and to specification.\n\nWe coordinate multidisciplinary teams and trusted suppliers to maintain consistent workmanship across every phase of construction.", 'icon' => 'building-office-2', 'display_order' => 1],
            ['name' => 'Building Construction', 'slug' => 'building-construction', 'short_description' => 'High-quality building construction for offices, facilities, and residential projects.', 'full_description' => "We construct durable and functional buildings tailored to client requirements. From structural works to finishing details, our engineers and site teams maintain strict quality standards.\n\nOur building construction service emphasizes safety, efficiency, and long-term performance.", 'icon' => 'home-modern', 'display_order' => 2],
            ['name' => 'Infrastructure Development', 'slug' => 'infrastructure-development', 'short_description' => 'Reliable infrastructure solutions that support communities and business growth.', 'full_description' => "Our infrastructure team delivers roads, drainage, utilities, and supporting civil works with strong technical expertise. We plan each project around durability, compliance, and environmental responsibility.\n\nNusantara Build Construction works closely with stakeholders to minimize disruption and maximize long-term value.", 'icon' => 'road', 'display_order' => 3],
            ['name' => 'Renovation and Remodeling', 'slug' => 'renovation-and-remodeling', 'short_description' => 'Modern renovation services that improve functionality, safety, and aesthetics.', 'full_description' => "We transform existing spaces through careful renovation and remodeling. Whether upgrading commercial facilities or revitalizing residential properties, we focus on practical design, quality materials, and efficient execution.\n\nOur team manages structural improvements, interior updates, and system upgrades with minimal downtime.", 'icon' => 'wrench-screwdriver', 'display_order' => 4],
            ['name' => 'Architecture and Design', 'slug' => 'architecture-and-design', 'short_description' => 'Practical and modern design solutions aligned with construction realities.', 'full_description' => "Our architecture and design service bridges creative vision and buildable solutions. We produce functional layouts, technical drawings, and material recommendations that support efficient construction.\n\nWe collaborate with clients to ensure every design reflects operational needs, brand identity, and future growth.", 'icon' => 'pencil-square', 'display_order' => 5],
            ['name' => 'Project Management', 'slug' => 'project-management', 'short_description' => 'Professional project oversight from planning through completion.', 'full_description' => "We provide structured project management to keep scope, budget, timeline, and quality under control. Our project managers coordinate stakeholders, contractors, and reporting to ensure transparent progress.\n\nWith proactive risk management and clear communication, we help clients achieve predictable project outcomes.", 'icon' => 'clipboard-document-check', 'display_order' => 6],
        ];
        foreach ($services as $service) {
            Service::query()->create($service + ['is_active' => true, 'thumbnail_image' => null]);
        }

        Project::query()->delete();
        $projects = [
            ['title' => 'Jakarta Business Tower', 'slug' => 'jakarta-business-tower', 'category' => 'Commercial', 'location' => 'Jakarta Selatan, Indonesia', 'client_name' => 'PT Artha Prima', 'completion_year' => 2025, 'status' => 'completed', 'is_featured' => true, 'short_description' => 'A modern 18-story office tower designed for efficiency and corporate performance.', 'full_description' => "Jakarta Business Tower is a commercial office development featuring efficient floor plates, modern facade systems, and integrated building services. Nusantara Build Construction managed structural works, architectural finishes, and MEP coordination.\n\nThe project was delivered with strict quality control and safety compliance in a dense urban environment."],
            ['title' => 'Surabaya Logistics Center', 'slug' => 'surabaya-logistics-center', 'category' => 'Infrastructure', 'location' => 'Surabaya, Indonesia', 'client_name' => 'Nusantara Logistics', 'completion_year' => 2024, 'status' => 'completed', 'is_featured' => true, 'short_description' => 'A large-scale logistics facility built for operational efficiency and future expansion.', 'full_description' => "The Surabaya Logistics Center provides high-capacity warehousing and distribution infrastructure. Our scope included civil works, steel structure erection, loading docks, and utility systems.\n\nThe facility was designed to support heavy-duty operations while allowing phased expansion."],
            ['title' => 'Bandung Residential Estate', 'slug' => 'bandung-residential-estate', 'category' => 'Residential', 'location' => 'Bandung, Indonesia', 'client_name' => 'Greenland Development', 'completion_year' => 2023, 'status' => 'completed', 'is_featured' => true, 'short_description' => 'A residential community combining comfort, durability, and modern design.', 'full_description' => "Bandung Residential Estate delivers a thoughtfully planned housing community with durable construction and modern amenities. Nusantara Build Construction handled land development, housing units, and supporting infrastructure.\n\nThe project prioritized quality materials, efficient layouts, and a comfortable living environment."],
            ['title' => 'Semarang Public Health Center Renovation', 'slug' => 'semarang-public-health-center-renovation', 'category' => 'Renovation', 'location' => 'Semarang, Indonesia', 'client_name' => 'Semarang City Government', 'completion_year' => 2024, 'status' => 'completed', 'is_featured' => false, 'short_description' => 'A functional renovation improving patient flow, safety, and facility standards.', 'full_description' => "This renovation project upgraded an active public health center while maintaining essential services. We improved patient circulation, updated interiors, and enhanced building safety systems.\n\nCareful phasing and site coordination minimized disruption to daily operations."],
            ['title' => 'Balikpapan Industrial Workshop', 'slug' => 'balikpapan-industrial-workshop', 'category' => 'Commercial', 'location' => 'Balikpapan, Indonesia', 'client_name' => 'Karya Teknik Mandiri', 'completion_year' => 2026, 'status' => 'in_progress', 'is_featured' => false, 'short_description' => 'An industrial workshop designed for heavy equipment maintenance and operations.', 'full_description' => "The Balikpapan Industrial Workshop is an ongoing project focused on structural durability and operational efficiency. The scope includes reinforced foundations, steel framing, and specialized utility installations.\n\nOur team is delivering the project with close attention to safety and technical performance."],
            ['title' => 'Yogyakarta Community Bridge', 'slug' => 'yogyakarta-community-bridge', 'category' => 'Infrastructure', 'location' => 'Yogyakarta, Indonesia', 'client_name' => 'Regional Public Works Office', 'completion_year' => 2022, 'status' => 'completed', 'is_featured' => false, 'short_description' => 'A community bridge project improving connectivity and local mobility.', 'full_description' => "The Yogyakarta Community Bridge enhances local transportation access and supports safer daily mobility. Nusantara Build Construction delivered foundation works, structural concrete, and finishing with strict quality inspection.\n\nThe project demonstrates our commitment to reliable public infrastructure."],
        ];
        foreach ($projects as $project) {
            $created = Project::query()->create($project + ['is_published' => true, 'cover_image' => null]);
            ProjectImage::query()->create(['project_id' => $created->id, 'image_path' => 'projects/placeholders/project-1.jpg', 'alt_text' => $created->title.' overview', 'sort_order' => 1]);
            ProjectImage::query()->create(['project_id' => $created->id, 'image_path' => 'projects/placeholders/project-2.jpg', 'alt_text' => $created->title.' detail', 'sort_order' => 2]);
        }

        Testimonial::query()->delete();
        Testimonial::query()->insert([
            ['client_name' => 'Andi Pratama', 'client_position' => 'Operations Director', 'company_name' => 'PT Artha Prima', 'testimonial_content' => 'Nusantara Build Construction delivered our office tower with outstanding professionalism. Their team communicated clearly and maintained excellent quality throughout the project.', 'client_photo' => null, 'rating' => 5, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['client_name' => 'Siti Rahmawati', 'client_position' => 'Development Manager', 'company_name' => 'Greenland Development', 'testimonial_content' => 'The residential estate project was completed on schedule and exceeded our expectations. Their attention to detail and site management were impressive.', 'client_photo' => null, 'rating' => 5, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['client_name' => 'Budi Santoso', 'client_position' => 'Facility Head', 'company_name' => 'Nusantara Logistics', 'testimonial_content' => 'We trusted Nusantara Build with our logistics center, and they delivered a robust facility that supports our daily operations perfectly.', 'client_photo' => null, 'rating' => 5, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        Team::query()->delete();
        Team::query()->insert([
            ['name' => 'Rizky Mahendra', 'position' => 'Managing Director', 'biography' => 'Rizky leads company strategy and ensures every project reflects Nusantara Build\'s commitment to quality and integrity.', 'profile_photo' => null, 'display_order' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dewi Kartika', 'position' => 'Head of Architecture', 'biography' => 'Dewi oversees design coordination, ensuring practical, modern, and buildable solutions for clients.', 'profile_photo' => null, 'display_order' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Agus Firmansyah', 'position' => 'Senior Project Manager', 'biography' => 'Agus manages complex construction schedules, contractor coordination, and on-site execution.', 'profile_photo' => null, 'display_order' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Nadia Putri', 'position' => 'Civil Engineering Lead', 'biography' => 'Nadia ensures structural quality, technical compliance, and engineering excellence across projects.', 'profile_photo' => null, 'display_order' => 4, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        ContactMessage::query()->delete();
        ContactMessage::query()->create([
            'name' => 'Hendra Wijaya',
            'email' => 'hendra.wijaya@example.com',
            'phone' => '+62 811 2233 4455',
            'subject' => 'Office Building Consultation',
            'message' => 'Hello Nusantara Build team, we are planning a new office building in Jakarta and would like to discuss project scope, timeline, and budget estimation.',
            'is_read' => false,
        ]);
    }
}
