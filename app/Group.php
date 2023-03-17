<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\BelongingGroup;
use Auth;

class Group extends Model
{
    /**
     * グループに所属するメンバーのIDを取得する
     */
     public static function regist($name, $facilitie){
         $group = new Group;
         $now = Date("Y-m-d H:i:s");
         $group->name = $name;
         $group->facilitie_id = $facilitie->id;
         $group->updater_id = Auth::id();
         $group->active = true;
         $group->created_at = $now;
         $group->updated_at = $now;
         $group->save();

         return $group;
     }

    /**
     * グループに所属するメンバーのIDを取得する
     */
     public function getClientIds(){
        $client_ids = BelongingGroup::where("group_id", $this->id)->get(["client_id"]);
        $client_ids = json_decode(json_encode($client_ids), true);

        return array_column($client_ids, "client_id");
     }

     /**
      * グループ更新
      */
      public function updateGroup($name, $facilitie){
          $now = Date("Y-m-d H:i:s");
          $this->name = $name;
          $this->facilitie_id = $facilitie->id;
          $this->updater_id = Auth::id();
          $this->active = true;
          $this->created_at = $now;
          $this->updated_at = $now;
          $this->save();

          return $this;
      }

}
