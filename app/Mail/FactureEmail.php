<?php
// app/Mail/FactureEmail.php
namespace App\Mail;

use App\Models\Facture;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FactureEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $facture;
    public $pdfPath;
    
    public function __construct(Facture $facture, $pdfPath)
    {
        $this->facture = $facture;
        $this->pdfPath = $pdfPath;
    }
    
    public function build()
    {
        return $this->subject('Votre facture LA MI CASA #' . $this->facture->numero_facture)
                    ->view('emails.facture')
                    ->attach(storage_path('app/public/' . $this->pdfPath), [
                        'as' => 'facture-' . $this->facture->numero_facture . '.pdf',
                        'mime' => 'application/pdf',
                    ]);
    }
}