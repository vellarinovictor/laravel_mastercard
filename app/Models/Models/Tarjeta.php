<?php

namespace App\Models\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarjeta extends Model
{
    use HasFactory;
    use \Znck\Eloquent\Traits\BelongsToThrough;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pin',
    ];

    public function cuenta() {
        return $this->belongsTo(Cuenta::class);
    }
    public function movimientos() {
        return $this->hasMany(Movimiento::class);
    }
    public function cliente() {
        return $this->belongsToThrough(User::class, Cuenta::class);
    }

    public function nocaducada() {
        $fechaActual = new DateTime();
        list($mes, $anio) = explode('/', $this->fechavalidez);
        $anioCompleto = 2000 + (int)$anio;
        $fechaValidezDateTime = DateTime::createFromFormat('Y-m-d', "$anioCompleto-$mes-01")->modify('last day of this month');
        return $fechaActual < $fechaValidezDateTime;
    }

}

/*
 * https://github.com/staudenmeir/belongs-to-through
 *      composer require staudenmeir/belongs-to-through:"^2.5"
 *      use \Znck\Eloquent\Traits\BelongsToThrough;
 *      se puede usar belongsToThrough
 */
