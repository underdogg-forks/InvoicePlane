<?php

namespace IP\Events\Listeners;

use IP\Events\PaymentCreated;
use IP\Modules\CustomFields\Models\PaymentCustom;
use IP\Modules\MailQueue\Support\MailQueue;
use IP\Support\Contacts;
use IP\Support\Parser;

class PaymentCreatedListener
{
    public function __construct(MailQueue $mailQueue)
    {
        $this->mailQueue = $mailQueue;
    }

    public function handle(PaymentCreated $event)
    {
        // Create the default custom record.
        $event->payment->custom()->save(new PaymentCustom());

        if (auth()->guest() or auth()->user()->user_type == 'client') {
            $event->payment->invoice->activities()->create(['activity' => 'public.paid']);
        }

        if (request('email_payment_receipt') == 'true'
            or (!request()->exists('email_payment_receipt') and config('fi.automaticEmailPaymentReceipts') and $event->payment->invoice->client->email)
        ) {
            $parser = new Parser($event->payment);

            $contacts = new Contacts($event->payment->invoice->client);

            $mail = $this->mailQueue->create($event->payment, [
                'to' => $contacts->getSelectedContactsTo(),
                'cc' => $contacts->getSelectedContactsCc(),
                'bcc' => $contacts->getSelectedContactsBcc(),
                'subject' => $parser->parse('paymentReceiptEmailSubject'),
                'body' => $parser->parse('paymentReceiptBody'),
                'attach_pdf' => config('fi.attachPdf'),
            ]);

            $this->mailQueue->send($mail->id);
        }
    }
}
