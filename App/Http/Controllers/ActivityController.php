<?php

namespace Modules\TomatoUserActivities\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use ProtoneMedia\Splade\Facades\Toast;
use TomatoPHP\TomatoAdmin\Facade\Tomato;
use Modules\TomatoUserActivities\App\Models\Activity;

class ActivityController extends Controller
{
    public string $model;

    public function __construct()
    {
        $this->model = \Modules\TomatoUserActivities\App\Models\Activity::class;
    }

    /**
     * @param Request $request
     * @return View|JsonResponse
     */
    public function index(Request $request): View|JsonResponse
    {
        return Tomato::index(
            request: $request,
            model: $this->model,
            view: 'tomato-user-activities::activities.index',
            table: \Modules\TomatoUserActivities\App\Tables\ActivityTable::class
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function api(Request $request): JsonResponse
    {
        return Tomato::json(
            request: $request,
            model: \Modules\TomatoUserActivities\App\Models\Activity::class,
        );
    }


    /**
     * @param Request $request
     * @return RedirectResponse|JsonResponse
     */
    public function clear(): RedirectResponse|JsonResponse
    {
        Activity::query()->truncate();

        Toast::success(__('Activities cleared successfully'))->autoDismiss(2);
        return back();
    }

    /**
     * @param \Modules\TomatoUserActivities\App\Models\Activity $model
     * @return View|JsonResponse
     */
    public function show(\Modules\TomatoUserActivities\App\Models\Activity $model): View|JsonResponse
    {
        return Tomato::get(
            model: $model,
            view: 'tomato-user-activities::activities.show',
        );
    }

    /**
     * @param \Modules\TomatoUserActivities\App\Models\Activity $model
     * @return RedirectResponse|JsonResponse
     */
    public function destroy(\Modules\TomatoUserActivities\App\Models\Activity $model): RedirectResponse|JsonResponse
    {
        $response = Tomato::destroy(
            model: $model,
            message: __('Activity deleted successfully'),
            redirect: 'admin.activities.index',
        );

        if($response instanceof JsonResponse){
            return $response;
        }

        return $response->redirect;
    }
}
