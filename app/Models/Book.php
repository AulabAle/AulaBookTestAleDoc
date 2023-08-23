<?php

namespace App\Models;

use OpenAI;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $fillable = 
    [ 
        'title',                      
        'description', 
        'pdf',
        'user_id',
        'cover'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateImage($image, $promptTokens)
    {
            //OpenAI
            $client = OpenAI::client(env('OPEN_AI_KEY'));
            try {
                $response = $client->images()->create([
                    'prompt'=>$promptTokens,
                    'n'=>1,
                    'size'=>env('OPEN_AI_SIZE'),
                    'response_format'=>'b64_json',
                ]);

                // Decodifica l'immagine in base64 in una stringa binaria
                $b64_img = base64_decode(strval($response->data[0]['b64_json']));

                if ($image) {
                    Storage::disk('public')->delete($image);
                }
        
                // Crea un nuovo file PNG con la stringa binaria 
                $image = 'upload/'.uniqid().".png";    
                
                Storage::disk('public')->put($image, $b64_img);   
                
            } catch (\Exception $e) {//recupero errori generati dall'API
                $message=$e->getMessage();
                session()->flash('errorMessage',"$message");
            }
        return $image;

    }
}
