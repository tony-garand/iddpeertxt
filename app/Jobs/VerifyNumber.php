<?php

namespace peertxt\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use peertxt\Events\PhoneVerificationFinished;
use peertxt\models\Contact;
use peertxt\models\User;
use Twilio\Rest\Client;


class VerifyNumber implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user, $contacts, $checkType;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, Collection $contacts, $checkType = false)
    {
        $this->user = $user;
        $this->contacts = $contacts;
        $this->checkType = $checkType;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $phonesVerified = 0;
        $phoneVerified = Contact::VerifiedPhoneNo;
        $verificationList = [];
        $client = new Client(env('SMS_SID'), env('SMS_TOKEN'));

        $single = $this->contacts->count() <= 1;
        foreach ($this->contacts as $contact) {
            try {
                if ($single && $contact->verified_phone != Contact::VerifiedPhoneIsMobile) {
                    $message = 'Phone verification finished. Status: ' . ($phonesVerified ? "Verified" : "Not Verified");
                    broadcast(new PhoneVerificationFinished($message, $this->contacts->count(), $contact->verified_phone, $this->user->id));
                    return;
                }

                if ($contact->phone) {
                    $fields = ["countryCode" => "US"];
                    if ($this->checkType) {
                        $fields["type"] = ["carrier"];
                    }
                    $verification = $client->lookups->v1->phoneNumbers($contact->phone)->fetch($fields);


                    $verificationList[] = [
                        'contact_id' => $contact->id,
                        'verified' => $verification->phoneNumber ? true : false
                    ];

                    if ($verification->phoneNumber) {
                        $contact->verified_phone = Contact::VerifiedPhoneValidPhone;
                        if ($this->checkType) {
                            if ($verification->carrier && $verification->carrier['type'] == 'mobile') {
                                $contact->verified_phone = Contact::VerifiedPhoneIsMobile;
                                if ($single) {
                                    $phoneVerified = Contact::VerifiedPhoneIsMobile;
                                }
                            }
                        } else {
                            $phoneVerified = Contact::VerifiedPhoneValidPhone;
                        }
                        $phonesVerified++;
                    } else {
                        $contact->verified_phone = Contact::VerifiedPhoneNo;
                    }
                    $contact->save();
                }
            } catch (\Exception $exception) {
                \Log::error($exception);
            }
        }


        if ($single) {
            $message = 'Phone verification finished. Status: ' . ($phonesVerified ? "Verified" : "Not Verified");
            broadcast(new PhoneVerificationFinished($message, $this->contacts->count(), $phoneVerified, $this->user->id));
        } else {
            $message = 'Phones verification finished. Verified: ' . ($phonesVerified . '/' . $this->contacts->count());
            broadcast(new PhoneVerificationFinished($message, $this->contacts->count(), $verificationList, $this->user->id));
        }

    }
}
