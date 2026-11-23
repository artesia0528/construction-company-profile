<?php

use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\Service;
use App\Support\ImageProcessor;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('image processor derives thumbnail path', function () {
    expect(ImageProcessor::thumbPath('services/foo.webp'))->toBe('services/foo_thumb.webp')
        ->and(ImageProcessor::thumbPath('services/foo.jpg'))->toBe('services/foo_thumb.webp')
        ->and(ImageProcessor::thumbPath('https://example.com/a.jpg'))->toBe('https://example.com/a.jpg')
        ->and(ImageProcessor::thumbPath(null))->toBeNull();
});

test('thumb url falls back to original when thumb missing on local disk', function () {
    config(['filesystems.media_disk' => 'public']);
    Storage::fake('public');
    Storage::disk('public')->put('services/original.jpg', 'content');

    expect(ImageProcessor::thumbUrl('services/original.jpg'))->toBe(Storage::disk('public')->url('services/original.jpg'));
});

test('thumb url uses thumb on local disk when it exists', function () {
    config(['filesystems.media_disk' => 'public']);
    Storage::fake('public');
    Storage::disk('public')->put('services/image.webp', 'content');
    Storage::disk('public')->put('services/image_thumb.webp', 'thumb');

    expect(ImageProcessor::thumbUrl('services/image.webp'))->toBe(Storage::disk('public')->url('services/image_thumb.webp'));
});

test('deleting a model removes original and thumbnail files', function () {
    config(['filesystems.media_disk' => 'public']);
    Storage::fake('public');
    Storage::disk('public')->put('services/a.webp', 'content');
    Storage::disk('public')->put('services/a_thumb.webp', 'thumb');

    $service = Service::factory()->create(['thumbnail_image' => 'services/a.webp']);
    $service->delete();

    Storage::disk('public')->assertMissing('services/a.webp');
    Storage::disk('public')->assertMissing('services/a_thumb.webp');
});

test('updating image removes old files when path changes', function () {
    config(['filesystems.media_disk' => 'public']);
    Storage::fake('public');
    Storage::disk('public')->put('services/old.webp', 'old');
    Storage::disk('public')->put('services/old_thumb.webp', 'old-thumb');
    Storage::disk('public')->put('services/new.webp', 'new');

    $service = Service::factory()->create(['thumbnail_image' => 'services/old.webp']);
    $service->update(['thumbnail_image' => 'services/new.webp']);

    Storage::disk('public')->assertMissing('services/old.webp');
    Storage::disk('public')->assertMissing('services/old_thumb.webp');
    Storage::disk('public')->assertExists('services/new.webp');
});

test('project image files are deleted when project is deleted', function () {
    config(['filesystems.media_disk' => 'public']);
    Storage::fake('public');
    Storage::disk('public')->put('projects/gallery/a.webp', 'content');
    Storage::disk('public')->put('projects/gallery/a_thumb.webp', 'thumb');

    $project = Project::factory()->create();
    ProjectImage::factory()->create(['project_id' => $project->id, 'image_path' => 'projects/gallery/a.webp']);

    $project->delete();

    Storage::disk('public')->assertMissing('projects/gallery/a.webp');
    Storage::disk('public')->assertMissing('projects/gallery/a_thumb.webp');
});

test('image processing creates webp and thumbnail when driver is available', function () {
    if (! ImageProcessor::canProcess()) {
        $this->markTestSkipped('No GD/Imagick driver available.');
    }

    config(['filesystems.media_disk' => 'public']);
    Storage::fake('public');

    $file = UploadedFile::fake()->image('photo.jpg', 1200, 800);
    $path = ImageProcessor::process($file, 'services');

    expect($path)->toEndWith('.webp');
    Storage::disk('public')->assertExists($path);
    Storage::disk('public')->assertExists(ImageProcessor::thumbPath($path));
})->skip(! ImageProcessor::canProcess(), 'No GD/Imagick driver available.');
