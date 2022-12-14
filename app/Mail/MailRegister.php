<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailRegister extends Mailable
{
	use Queueable, SerializesModels;
	private $fields;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct($fields){
		$this->fields = $fields;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build(){
		return $this->from('no-reply@sitegist.com')->subject('Нова заявка на сайту EECU')->view('emails.register', ['fields' => $this->fields]);
	}
}
