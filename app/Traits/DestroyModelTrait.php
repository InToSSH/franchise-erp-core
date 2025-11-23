<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Session;

trait DestroyModelTrait
{
    /**
     * Destroys a model if foreign_key check passes otherwise returns an error
     *
     * @param Model $model
     * @return RedirectResponse
     */
    protected function destroyModel(Model $model): RedirectResponse
    {
        try {
            $model->delete();
            Session::flash('success', __('Záznam byl úspěšně smazán.'));
        } catch (\Exception $e) {
            if ($e->getCode() == '23000') {
                Session::flash('error', __('Smazání není možné. Záznam je vázán na ostatní data.'));
                return back();
            }
            Session::flash('error', __('Nastala neočekávaná chyba při mazání záznamu: ') . $e->getMessage());
        }

        return back();
    }

    /**
     * Confirms deleting the model by comparing user's password
     *
     * @param Model $model
     * @param string $password
     * @return JsonResponse
     */
    protected function destroyModelConfirmed(Model $model, string $password): JsonResponse
    {
        if (auth()->hasUser() && \Hash::check($password, auth()->user()->password)) {
            return $this->destroyModel($model);
        }

        return abort(403);
    }
}
