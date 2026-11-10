<?php

namespace App\Http\Requests\PushNotification;

use App\Enums\PushNotificationTopic;
use App\Models\PushNotification;
use App\Support\MiniMicroNotificationClient;
use App\Support\PushNotificationService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StorePushNotificationRequest extends FormRequest
{
    public const CONFIRMATION_WORD = 'KIRIM';

    public function authorize(): bool
    {
        return $this->user()?->can('send-push-notification') ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'topic' => ['required', Rule::enum(PushNotificationTopic::class)],
            'title' => ['required', 'string', 'max:'.MiniMicroNotificationClient::TITLE_MAX],
            'description' => ['required', 'string', 'max:'.MiniMicroNotificationClient::DESCRIPTION_MAX],
            'source_type' => ['nullable', Rule::in([PushNotification::SOURCE_BERITA, PushNotification::SOURCE_BANNER])],
            'source_id' => ['nullable', 'required_with:source_type', 'integer', 'min:1'],
            'resend' => ['nullable', 'boolean'],
            'confirmation' => ['required', 'string', Rule::in([self::CONFIRMATION_WORD])],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.max' => 'Judul notifikasi maksimal :max karakter.',
            'description.max' => 'Isi notifikasi maksimal :max karakter.',
            'confirmation.required' => 'Ketik '.self::CONFIRMATION_WORD.' untuk mengonfirmasi broadcast.',
            'confirmation.in' => 'Ketik '.self::CONFIRMATION_WORD.' (huruf kapital) untuk mengonfirmasi broadcast.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'topic' => 'topic',
            'title' => 'judul',
            'description' => 'isi notifikasi',
            'confirmation' => 'konfirmasi',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'title' => is_string($this->input('title')) ? trim($this->input('title')) : $this->input('title'),
            'description' => is_string($this->input('description')) ? trim($this->input('description')) : $this->input('description'),
            'confirmation' => is_string($this->input('confirmation')) ? trim($this->input('confirmation')) : $this->input('confirmation'),
            'source_type' => $this->filled('source_type') ? $this->input('source_type') : null,
            'source_id' => $this->filled('source_id') ? $this->input('source_id') : null,
            'resend' => $this->boolean('resend'),
        ]);
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $service = app(PushNotificationService::class);
                $sourceType = $this->input('source_type');
                $sourceId = $this->filled('source_id') ? (int) $this->input('source_id') : null;

                if ($sourceType !== null) {
                    $source = $service->findSource($sourceType, $sourceId);

                    if ($source === null) {
                        $validator->errors()->add('source_id', 'Konten sumber notifikasi tidak ditemukan.');

                        return;
                    }

                    if (! $source->is_notification) {
                        $validator->errors()->add('source_id', 'Aktifkan "Tandai untuk Notifikasi" pada konten ini terlebih dahulu.');

                        return;
                    }

                    if ($this->input('topic') !== PushNotificationTopic::forSource($sourceType)->value) {
                        $validator->errors()->add('topic', 'Topic tidak sesuai dengan jenis konten sumber.');

                        return;
                    }

                    if (! MiniMicroNotificationClient::isDryRun()
                        && ! $this->boolean('resend')
                        && $service->lastLiveSendFor($sourceType, (int) $sourceId) !== null) {
                        $validator->errors()->add('resend', 'Konten ini sudah pernah di-broadcast. Centang "Kirim ulang" jika memang ingin mengirim lagi.');

                        return;
                    }
                }

                $remaining = $service->cooldownRemainingSeconds();

                if ($remaining > 0) {
                    $validator->errors()->add(
                        'confirmation',
                        'Broadcast terakhir baru saja dikirim. Tunggu '.max(1, (int) ceil($remaining / 60)).' menit lagi sebelum broadcast berikutnya.',
                    );
                }
            },
        ];
    }
}
