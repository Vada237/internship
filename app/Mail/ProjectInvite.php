<?php

namespace App\Mail;

use App\Models\Invite;
use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProjectInvite extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(Invite $invite, string $sender)
    {
        $this->invite = $invite;
        $this->sender = $sender;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Приглашение в проект',
        );
    }

    public function content()
    {
        return new Content(
            view: 'invite.projectSend',
        );
    }

    public function build()
    {
        return $this->from('denis.le2001@mail.ru')->view('invite.projectSend')->with([
            'invite' => $this->invite,
            'project' => Project::find($this->invite->invitable_id),
            'sender' => $this->sender
        ]);
    }

    public function attachments()
    {
        return [];
    }
}
