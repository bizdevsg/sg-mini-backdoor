<?php

namespace App\Http\Controllers;

use App\Enums\PushNotificationStatus;
use App\Enums\PushNotificationTopic;
use App\Http\Requests\PushNotification\StorePushNotificationRequest;
use App\Models\PushNotification;
use App\Support\MiniMicroNotificationClient;
use App\Support\PushNotificationService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PushNotificationController extends Controller
{
    public function __construct(
        private readonly PushNotificationService $pushNotificationService,
    ) {}

    public function index(Request $request): View
    {
        $status = PushNotificationStatus::tryFrom($request->string('status')->toString());

        $notifications = PushNotification::query()
            ->with('user')
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($request->string('search')->isNotEmpty(), function ($query) use ($request): void {
                $search = $request->string('search')->toString();

                $query->where(function ($builder) use ($search): void {
                    $builder->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $counts = PushNotification::query()
            ->selectRaw('status, count(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        return view('push-notifications.index', [
            'notifications' => $notifications,
            'counts' => $counts,
            'activeStatus' => $status,
            'isDryRun' => MiniMicroNotificationClient::isDryRun(),
            'targetHost' => MiniMicroNotificationClient::baseUrlHost(),
        ]);
    }

    public function create(Request $request): View|RedirectResponse
    {
        $sourceType = $request->string('source_type')->toString() ?: null;
        $sourceId = $request->integer('source_id') ?: null;
        $draft = null;

        if ($sourceType !== null) {
            $draft = $this->pushNotificationService->draftFromSource($sourceType, $sourceId);

            if ($draft === null) {
                return redirect()
                    ->route('push-notifications.create')
                    ->with('status', 'Konten sumber notifikasi tidak ditemukan.')
                    ->with('status_type', 'error');
            }
        }

        return view('push-notifications.create', [
            'draft' => $draft,
            'topics' => PushNotificationTopic::cases(),
            'lastLiveSend' => $draft
                ? $this->pushNotificationService->lastLiveSendFor($draft['source_type'], $draft['source_id'])
                : null,
            'cooldownSeconds' => $this->pushNotificationService->cooldownRemainingSeconds(),
            'isDryRun' => MiniMicroNotificationClient::isDryRun(),
            'targetHost' => MiniMicroNotificationClient::baseUrlHost(),
            'titleMax' => MiniMicroNotificationClient::TITLE_MAX,
            'descriptionMax' => MiniMicroNotificationClient::DESCRIPTION_MAX,
            'confirmationWord' => StorePushNotificationRequest::CONFIRMATION_WORD,
        ]);
    }

    public function store(StorePushNotificationRequest $request): RedirectResponse
    {
        $notification = $this->pushNotificationService->dispatchBroadcast(
            $request->user(),
            $request->safe()->only(['topic', 'title', 'description', 'source_type', 'source_id']),
        );

        return redirect()
            ->route('push-notifications.index')
            ->with('status', $this->statusMessage($notification->refresh()))
            ->with('status_type', $notification->status === PushNotificationStatus::Failed ? 'error' : null);
    }

    public function retry(PushNotification $pushNotification): RedirectResponse
    {
        if ($pushNotification->status !== PushNotificationStatus::Failed) {
            return back()
                ->with('status', 'Hanya notifikasi berstatus Gagal yang bisa dikirim ulang.')
                ->with('status_type', 'error');
        }

        $remaining = $this->pushNotificationService->cooldownRemainingSeconds();

        if ($remaining > 0) {
            return back()
                ->with('status', 'Tunggu '.max(1, (int) ceil($remaining / 60)).' menit lagi sebelum broadcast berikutnya.')
                ->with('status_type', 'error');
        }

        $this->pushNotificationService->retry($pushNotification);

        return redirect()
            ->route('push-notifications.index')
            ->with('status', $this->statusMessage($pushNotification->refresh()))
            ->with('status_type', $pushNotification->status === PushNotificationStatus::Failed ? 'error' : null);
    }

    private function statusMessage(PushNotification $notification): string
    {
        return match ($notification->status) {
            PushNotificationStatus::Sent => 'Notifikasi berhasil di-broadcast ke semua pengguna.',
            PushNotificationStatus::DryRun => 'Dry run berhasil: server menerima payload, tetapi notifikasi tidak dikirim ke pengguna.',
            PushNotificationStatus::Failed => 'Broadcast gagal: '.$notification->error_message,
            PushNotificationStatus::Queued => 'Notifikasi masuk antrean dan akan dikirim dalam beberapa saat.',
        };
    }
}
