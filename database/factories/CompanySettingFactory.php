<?php

namespace Database\Factories;

use App\Models\CompanySetting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CompanySetting>
 */
class CompanySettingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'company_name' => 'Nusantara Build Construction',
            'company_description' => 'Nusantara Build Construction delivers reliable construction solutions for commercial, residential, and infrastructure projects across Indonesia.',
            'company_address' => 'Jl. Jendral Sudirman Kav. 45, Jakarta Selatan, Indonesia',
            'phone_number' => '+62 21 5550 1234',
            'email_address' => 'hello@nusantarabuild.co.id',
            'whatsapp_number' => '+62 812 3456 7890',
            'facebook_url' => 'https://facebook.com/nusantarabuild',
            'instagram_url' => 'https://instagram.com/nusantarabuild',
            'linkedin_url' => 'https://linkedin.com/company/nusantarabuild',
            'google_maps_embed_url' => 'https://www.google.com/maps?q=Jakarta&output=embed',
            'copyright_text' => '© 2026 Nusantara Build Construction. All rights reserved.',
            'hero_headline' => 'Building Strong Foundations for the Future',
            'hero_description' => 'We deliver high-quality construction, infrastructure, and renovation projects with precision, safety, and long-term value.',
            'hero_primary_cta_label' => 'Contact Us',
            'hero_primary_cta_url' => '/contact',
            'hero_secondary_cta_label' => 'View Our Projects',
            'hero_secondary_cta_url' => '/projects',
            'company_intro' => 'With decades of combined experience, Nusantara Build Construction is a trusted partner for complex building and infrastructure developments.',
            'company_history' => 'Founded with a commitment to quality craftsmanship, Nusantara Build Construction has grown into a full-service construction company serving public and private sector clients.',
            'vision' => 'To be Indonesia\'s most trusted construction partner, known for quality, integrity, and innovation.',
            'mission' => "Deliver safe, sustainable, and high-quality construction projects.\nBuild long-term partnerships through transparency and reliability.\nInvest in skilled people, modern methods, and continuous improvement.",
            'company_values' => ['Integrity', 'Safety', 'Quality', 'Collaboration', 'Innovation'],
        ];
    }
}
