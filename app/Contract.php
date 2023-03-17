<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Client;
use App\Facilitie;

class Contract extends Model
{
    /**
     * 児童名を返す
     * @return string
     */
    public function getClientName(){
        return Client::find($this->client_id)->name;
    }

    /**
     * 施設名を返す
     * @return string
     */
    public function getFacilitieName(){
        return Facilitie::find($this->facilitie_id)->name;
    }
}
