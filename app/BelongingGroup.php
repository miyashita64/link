<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BelongingGroup extends Model
{
    /**
     * グループメンバー登録
     */
    public static function regist($group, $client){
        $member = new BelongingGroup;
        $now = Date("Y-m-d H:i:s");
        $member->group_id = $group->id;
        $member->client_id = $client->id;
        $member->active = true;
        $member->created_at = $now;
        $member->updated_at = $now;
        $member->save();

        return $member;
    }

}
