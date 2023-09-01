<?php

namespace App\Jobs;

use OpenAI;
use Throwable;
use Illuminate\Bus\Queueable;
use App\Models\GeneratedImage;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class GenerateOpenAiCoverImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $generatedImage;

    /**
     * Create a new job instance.
     */
    public function __construct(GeneratedImage $generatedImage)
    {
        $this->generatedImage = $generatedImage;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $prompt = $this->generatedImage->prompt;
        $client = OpenAI::client(env('OPEN_AI_KEY'));
        $response = $client->images()->create([
            'prompt'=> "$prompt",
            'n'=>1,
            'size'=>env('OPEN_AI_SIZE'),
            'response_format'=>'b64_json',
        ]);

        // Decodifica l'immagine in base64 in una stringa binaria
        $b64_img = base64_decode(strval($response->data[0]['b64_json']));


        // Crea un nuovo file PNG con la stringa binaria 
        $image = 'upload/'.uniqid().".png";    
        
        //Memorizzazione del percorso nello storage
        Storage::disk('public')->put($image, $b64_img);
        
        $this->generatedImage->image = $image;
        $this->generatedImage->save();
    }

    public function failed(Throwable $exception): void
    {
        $this->generatedImage->error = $exception->getMessage();
        $this->generatedImage->save();
    }
}
