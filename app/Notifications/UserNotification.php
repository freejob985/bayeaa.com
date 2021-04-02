<?php


namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Jenssegers\Date\Date;

class UserNotification extends Notification implements ShouldQueue
{
	use Queueable;
	
	protected $user;
	
	public function __construct($user)
	{
		$this->user = $user;
	}
	
	public function via($notifiable)
	{
		return ['mail'];
	}
	
	public function toMail($notifiable)
	{
		return (new MailMessage)
			->subject(trans('mail.user_notification_title'))
			->greeting(trans('mail.user_notification_content_1'))
			->line(trans('mail.user_notification_content_2', ['name' => $this->user->name]))
			->line(trans('mail.user_notification_content_3', [
				'now'   => Date::now(config('timezone.id'))->formatLocalized(config('settings.app.default_date_format')),
				'time'  => Date::now(config('timezone.id'))->format('H:i'),
				'email' => $this->user->email
			]))
			->salutation(trans('mail.footer_salutation', ['appName' => config('app.name')]));
	}
}
