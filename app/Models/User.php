<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\RollDice;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        //'succes_rate'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'succes_rate'
    ];

    public function index()
    {
        $user = auth()->user()->id;
        $games = RollDice::where('user_id',$user)->get();

        return $games; 
    }

    public function games()
    {
        return $this -> hasMany(RollDice::class,'user_id');
    }

    public function getSuccesRateAttribute()
    {

        $games = $this->games();
        $totalGames = $this->games()->count();
        $winnerGames = 0;
        $succesRate = 0;

        if ($totalGames) {
            foreach ($games->getResults() as $game) {
                if ($game->result) {
                    $winnerGames += 1;
                }
            }
            $succesRate = round($winnerGames / $totalGames * 100, 2);
            return  $succesRate . '%';
        } else {
            return  $winnerGames . '%';
        }
    }
}
