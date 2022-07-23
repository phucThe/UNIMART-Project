<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserRole;

class CheckUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user_id = Auth::user()->id;
        if(UserRole::where('user_id',$user_id)->count() == 0){
            $request->session()->put('alert', "Bạn chưa được sự cho phép để đăng nhập vào hệ thống.");
            return redirect('/logout');
        }
        if(UserRole::where('user_id',$user_id)->count() > 0){
            $user_role = User::find($user_id)->roles()->first()->id;
            if(($user_role == 2)){
                if($request->id != $user_id){
                    return redirect(route('user_edit', $user_id));
                }
            }
        }
        return $next($request);
    }
}
