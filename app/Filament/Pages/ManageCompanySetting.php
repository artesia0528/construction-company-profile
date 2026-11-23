<?php

namespace App\Filament\Pages;

use App\Models\CompanySetting;
use App\Support\ImageProcessor;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageCompanySetting extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Company Settings';

    protected static ?string $title = 'Company Settings';

    protected string $view = 'filament.pages.manage-company-setting';

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $setting = CompanySetting::current();
        $data = $setting->attributesToArray();
        $data['company_values_text'] = is_array($setting->company_values) ? implode("\n", $setting->company_values) : '';
        $this->form->fill($data);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Company Profile')
                    ->columns(2)
                    ->schema([
                        TextInput::make('company_name')->required()->maxLength(255),
                        TextInput::make('email_address')->email()->maxLength(255),
                        TextInput::make('phone_number')->maxLength(255),
                        TextInput::make('whatsapp_number')->maxLength(255),
                        TextInput::make('company_address')->columnSpanFull()->maxLength(255),
                        Textarea::make('company_description')->rows(4)->columnSpanFull(),
                        FileUpload::make('company_logo')
                            ->image()
                            ->disk(config('filesystems.media_disk', 'public'))
                            ->visibility('public')
                            ->directory('settings')
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->saveUploadedFileUsing(fn ($file): string => ImageProcessor::process($file, 'settings'))
                            ->deleteUploadedFileUsing(fn (?string $file): ?bool => $file ? (ImageProcessor::delete($file) ?? true) : null),
                    ]),
                Section::make('Hero & Intro')
                    ->columns(2)
                    ->schema([
                        TextInput::make('hero_headline')->maxLength(255)->columnSpanFull(),
                        Textarea::make('hero_description')->rows(3)->columnSpanFull(),
                        FileUpload::make('hero_image')
                            ->image()
                            ->disk(config('filesystems.media_disk', 'public'))
                            ->visibility('public')
                            ->directory('settings')
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->saveUploadedFileUsing(fn ($file): string => ImageProcessor::process($file, 'settings'))
                            ->deleteUploadedFileUsing(fn (?string $file): ?bool => $file ? (ImageProcessor::delete($file) ?? true) : null),
                        TextInput::make('hero_alt_text')->maxLength(255),
                        TextInput::make('hero_primary_cta_label')->maxLength(255),
                        TextInput::make('hero_primary_cta_url')->maxLength(255),
                        TextInput::make('hero_secondary_cta_label')->maxLength(255),
                        TextInput::make('hero_secondary_cta_url')->maxLength(255),
                        Textarea::make('company_intro')->rows(4)->columnSpanFull(),
                    ]),
                Section::make('About Content')
                    ->columns(2)
                    ->schema([
                        Textarea::make('company_history')->rows(4)->columnSpanFull(),
                        Textarea::make('vision')->rows(3),
                        Textarea::make('mission')->rows(4),
                        Textarea::make('company_values_text')
                            ->label('Company Values (one per line)')
                            ->rows(5)
                            ->dehydrated(false)
                            ->columnSpanFull(),
                    ]),
                Section::make('Social & Footer')
                    ->columns(2)
                    ->schema([
                        TextInput::make('facebook_url')->maxLength(255),
                        TextInput::make('instagram_url')->maxLength(255),
                        TextInput::make('linkedin_url')->maxLength(255),
                        TextInput::make('copyright_text')->maxLength(255),
                        Textarea::make('google_maps_embed_url')->rows(3)->columnSpanFull(),
                    ]),
                Section::make('SEO')
                    ->columns(2)
                    ->schema([
                        TextInput::make('seo_title')->maxLength(255),
                        TextInput::make('canonical_url')->maxLength(2048)->url(),
                        Textarea::make('seo_description')->rows(3)->columnSpanFull(),
                        FileUpload::make('seo_image')
                            ->image()
                            ->disk(config('filesystems.media_disk', 'public'))
                            ->visibility('public')
                            ->directory('settings')
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->saveUploadedFileUsing(fn ($file): string => ImageProcessor::process($file, 'settings'))
                            ->deleteUploadedFileUsing(fn (?string $file): ?bool => $file ? (ImageProcessor::delete($file) ?? true) : null),
                        Toggle::make('noindex')->label('Noindex this site page context')->helperText('Sets meta robots to noindex,follow when used.'),
                    ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $valuesText = (string) ($this->data['company_values_text'] ?? '');
        $data['company_values'] = collect(preg_split('/\r\n|\r|\n/', $valuesText))
            ->map(fn ($value) => trim((string) $value))
            ->filter()
            ->values()
            ->all();

        $setting = CompanySetting::query()->first();
        if ($setting) {
            $setting->update($data);
        } else {
            CompanySetting::query()->create($data);
        }

        CompanySetting::clearCache();

        Notification::make()
            ->title('Company settings saved')
            ->success()
            ->send();
    }
}
