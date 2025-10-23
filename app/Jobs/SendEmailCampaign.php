<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\EmailCampaign;
use App\Models\EmailSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

final class SendEmailCampaign implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public EmailCampaign $campaign,
        public EmailSubscriber $subscriber
    ) {}

    public function handle(): void
    {
        try {
            // Check if subscriber is still active
            if (!$this->subscriber->is_active) {
                return;
            }

            // Send the email
            Mail::to($this->subscriber->email)->send(
                new \App\Mail\EmailCampaignMail($this->campaign, $this->subscriber)
            );

            // Update campaign statistics
            $this->campaign->increment('sent_count');

            // Check if all emails have been sent
            if ($this->campaign->sent_count >= $this->campaign->total_recipients) {
                $this->campaign->markAsSent();
            }

        } catch (\Exception $e) {
            Log::error('Failed to send email campaign', [
                'campaign_id' => $this->campaign->id,
                'subscriber_id' => $this->subscriber->id,
                'error' => $e->getMessage()
            ]);

            // Mark job as failed
            $this->fail($e);
        }
    }
}