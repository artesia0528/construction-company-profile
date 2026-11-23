<?php

namespace App\Support\Seo;

use App\Models\CompanySetting;
use App\Models\Project;
use App\Models\Service;

class SchemaBuilder
{
    /**
     * @return array<string, mixed>|null
     */
    public function organization(CompanySetting $settings): ?array
    {
        $name = $this->string($settings->company_name);
        $url = $this->url(url('/'));

        if ($name === null || $url === null) {
            return null;
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $name,
            'url' => $url,
        ];

        $logo = $this->url($settings->logoUrl());
        if ($logo !== null) {
            $schema['logo'] = $logo;
        }

        $description = $this->string($settings->company_description);
        if ($description !== null) {
            $schema['description'] = $description;
        }

        $email = $this->string($settings->email_address);
        if ($email !== null) {
            $schema['email'] = $email;
        }

        $phone = $this->string($settings->phone_number);
        if ($phone !== null) {
            $schema['telephone'] = $phone;
        }

        $address = $this->string($settings->company_address);
        if ($address !== null) {
            $schema['address'] = [
                '@type' => 'PostalAddress',
                'streetAddress' => $address,
            ];
        }

        $sameAs = array_values(array_filter([
            $this->url($settings->facebook_url),
            $this->url($settings->instagram_url),
            $this->url($settings->linkedin_url),
        ]));

        if ($sameAs !== []) {
            $schema['sameAs'] = $sameAs;
        }

        $contactPoint = array_filter([
            '@type' => 'ContactPoint',
            'telephone' => $phone,
            'email' => $email,
            'contactType' => ($phone !== null || $email !== null) ? 'customer service' : null,
        ], fn ($value) => $value !== null);

        if (isset($contactPoint['contactType'])) {
            $schema['contactPoint'] = [$contactPoint];
        }

        return $schema;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function website(CompanySetting $settings): ?array
    {
        $name = $this->string($settings->company_name) ?? config('app.name');
        $url = $this->url(url('/'));

        if ($name === null || $url === null) {
            return null;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => $name,
            'url' => $url,
        ];
    }

    /**
     * @param  array<int, array{name?: string|null, url?: string|null, label?: string|null}>  $items
     * @return array<string, mixed>|null
     */
    public function breadcrumbList(array $items): ?array
    {
        $elements = [];
        $position = 1;

        foreach ($items as $item) {
            $name = $this->string($item['label'] ?? $item['name'] ?? null);
            if ($name === null) {
                continue;
            }

            $element = [
                '@type' => 'ListItem',
                'position' => $position,
                'name' => $name,
            ];

            $itemUrl = $this->url($item['url'] ?? null);
            if ($itemUrl !== null) {
                $element['item'] = $itemUrl;
            }

            $elements[] = $element;
            $position++;
        }

        if ($elements === []) {
            return null;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $elements,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function webPage(string $type, string $name, string $url, ?string $description = null): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => $type,
            'name' => $name,
            'url' => $url,
        ];

        if ($description !== null && $description !== '') {
            $schema['description'] = $description;
        }

        return $schema;
    }

    /**
     * @return array<string, mixed>
     */
    public function service(Service $service, string $url): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $service->name,
            'url' => $url,
        ];

        $description = $this->string($service->short_description);
        if ($description !== null) {
            $schema['description'] = $description;
        }

        $image = $this->url($service->thumbnailUrl());
        if ($image !== null) {
            $schema['image'] = $image;
        }

        $providerName = $this->string(CompanySetting::current()->company_name);
        if ($providerName !== null) {
            $schema['provider'] = [
                '@type' => 'Organization',
                'name' => $providerName,
            ];
        }

        return $schema;
    }

    /**
     * @return array<string, mixed>
     */
    public function project(Project $project, string $url): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Project',
            'name' => $project->title,
            'url' => $url,
        ];

        $description = $this->string($project->short_description);
        if ($description !== null) {
            $schema['description'] = $description;
        }

        $image = $this->url($project->coverImageUrl());
        if ($image !== null) {
            $schema['image'] = $image;
        }

        $location = $this->string($project->location);
        if ($location !== null) {
            $schema['location'] = $location;
        }

        $client = $this->string($project->client_name);
        if ($client !== null) {
            $schema['sponsor'] = [
                '@type' => 'Organization',
                'name' => $client,
            ];
        }

        return $schema;
    }

    private function string(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $value = trim(strip_tags($value));

        return $value === '' ? null : $value;
    }

    private function url(mixed $value): ?string
    {
        $value = $this->string($value);

        return $value === null ? null : $value;
    }
}
