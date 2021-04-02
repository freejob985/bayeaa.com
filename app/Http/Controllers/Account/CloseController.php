<?php


namespace App\Http\Controllers\Account;

use App\Models\Permission;
use App\Models\User;

class CloseController extends AccountBaseController
{
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		view()->share('pagePath', 'close');
		return appView('account.close');
	}
	
	/**
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function submit()
	{
		if (request()->input('close_account_confirmation') == 1) {
			// Get User
			$user = User::findOrFail(auth()->user()->id);
			
			// Don't delete admin users
			if ($user->can(Permission::getStaffPermissions())) {
				flash(t('admin_users_cannot_be_deleted'))->error();
				return redirect(config('app.locale') . '/account');
			}
			
			// Delete User
			$user->delete();
			
			// Close User's session
			auth()->logout();
			
			$message = t("Your account has been deleted. We regret you. <a href=\":url\">Re-register</a> if that is a mistake.", [
				'url' => lurl(trans('routes.register')),
			]);
			flash($message)->success();
		}
		
		return redirect(config('app.locale') . '/');
	}
}
