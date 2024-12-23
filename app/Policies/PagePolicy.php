<?php

namespace App\Policies;

use App\Models\User;
use Flags\Flags;
use Illuminate\Auth\Access\HandlesAuthorization;
class PagePolicy
{
    use HandlesAuthorization;
    /**
     * Create a new policy instance.
     */

    public function userCan($page):void
    {

        $user = auth()->user();
        if($user->cliente=='lowcost')
        {
            $access = Flags::getFlags()[$user->cliente][$user->level];
        }
        else
        {
            $access = Flags::getFlags()['partner'][$user->level];
        }
        $can = array_filter($access,function($var) use ($page)
        {
            if(str_contains( $page, $var)!==false)
            {
                return true;
            }
            else
            {
                return redirect('/list-news');
            }
        });


    }


}
