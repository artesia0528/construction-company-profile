<?php

use App\Http\Requests\ContactMessageRequest;
use App\Models\ContactMessage;
use Livewire\Component;

new class extends Component
{
    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $subject = '';

    public string $message = '';

    public bool $sent = false;

    public function mount(): void
    {
        $this->name = (string) old('name', '');
        $this->email = (string) old('email', '');
        $this->phone = (string) old('phone', '');
        $this->subject = (string) old('subject', '');
        $this->message = (string) old('message', '');
    }

    /**
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
    {
        return (new ContactMessageRequest())->rules();
    }

    public function submit(): void
    {
        $this->sent = false;

        ContactMessage::query()->create($this->validate());

        $this->reset(['name', 'email', 'phone', 'subject', 'message']);
        $this->resetValidation();
        $this->sent = true;
    }
};
?>

<div>
    @if ($sent || session('success'))
        <div class="mb-6 rounded border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700" role="status" aria-live="polite">
            {{ $sent ? 'Thank you for your message. Our team will contact you shortly.' : session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">
            Please correct the highlighted fields.
        </div>
    @endif

    <form action="{{ route('contact.store') }}" method="POST" wire:submit="submit" class="grid gap-6 md:grid-cols-2">
        @csrf

        <x-ui.form.input label="Full Name" name="name" :value="$name" required wire:model="name" />
        <x-ui.form.input label="Email Address" name="email" type="email" :value="$email" required wire:model="email" />
        <x-ui.form.input label="Phone Number" name="phone" :value="$phone" wire:model="phone" />
        <x-ui.form.input label="Subject" name="subject" :value="$subject" required wire:model="subject" />

        <div class="md:col-span-2">
            <x-ui.form.textarea label="Message" name="message" :value="$message" rows="6" required wire:model="message" />
        </div>

        <div class="md:col-span-2">
            <x-ui.button type="submit">
                <span wire:loading.remove>Send Message</span>
                <span wire:loading>Sending...</span>
            </x-ui.button>
        </div>
    </form>
</div>
