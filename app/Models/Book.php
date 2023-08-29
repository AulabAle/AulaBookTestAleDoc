<?php

namespace App\Models;

use OpenAI;
use App\Models\User;
use App\Models\Category;
use App\Models\PurchasedBook;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'cover',
        'price',
        'category_id',
        'is_published' , 
        'review_status',
    ];



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

    //Controllo se l'utente autenticato è il creatore del book
    public function isBookAuthor(){
        $user = Auth::user(); 
        
        if( !$user ){
            return false;
        } 

        return $user->id == $this->user->id;
    }

    //Controllo se l'utente autenticato ha già acquistato il libro
    public function isBookPurchased(){

        $user = Auth::user(); 
        
        if( !$user ){
            return false;
        } 

        $userHasPurchasedThisBook = $user
            ->purchasedBooks()
            ->where('book_id', $this->id)
            ->where('payment_status', 'success')
            ->exists();

        return $userHasPurchasedThisBook;
    }

    public function getDescriptionSubstring(){
        if (strlen($this->description) > 20) {
                return substr($this->description, 0 , 30) . '...';
        }
        return $this->description;
    }

    //Funzione per la pubblicazione del book
    public function setAccepted($value)
    {
        $this->is_published=$value;
        $this->save();
        return true;
    }

    public function purchasedBooks(): HasMany
    {
        return $this->hasMany(PurchasedBook::class);
    }
    
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
