<?php

namespace Modules\TomatoUserActivities\App\Tables;

use Illuminate\Http\Request;
use ProtoneMedia\Splade\AbstractTable;
use ProtoneMedia\Splade\Facades\Toast;
use ProtoneMedia\Splade\SpladeTable;
use Illuminate\Database\Eloquent\Builder;

class ActivityTable extends AbstractTable
{
    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct(public mixed $query=null)
    {
        if(!$query){
            $this->query = \Modules\TomatoUserActivities\App\Models\Activity::query();
        }
    }

    /**
     * Determine if the user is authorized to perform bulk actions and exports.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {
        return true;
    }

    /**
     * The resource or query builder.
     *
     * @return mixed
     */
    public function for()
    {
        return $this->query;
    }

    /**
     * Configure the given SpladeTable.
     *
     * @param \ProtoneMedia\Splade\SpladeTable $table
     * @return void
     */
    public function configure(SpladeTable $table)
    {
        $table
            ->withGlobalSearch(
                label: trans('tomato-admin::global.search'),
                columns: ['id',]
            )
            ->bulkAction(
                label: trans('tomato-admin::global.crud.delete'),
                each: fn (\Modules\TomatoUserActivities\App\Models\Activity $model) => $model->delete(),
                after: fn () => Toast::danger(__('Activity Has Been Deleted'))->autoDismiss(2),
                confirm: true
            )
            ->selectFilter(
                key: 'model_id',
                label: __('Account'),
                remote_url: route('admin.accounts.api')
            )
            ->defaultSort('id', 'desc')
            ->column(
                key: 'id',
                label: __('Id'),
                sortable: true
            )
            ->column(
                key: 'user',
                label: __('User'),
                sortable: true
            )
            ->column(
                key: 'user_agent',
                label: __('User agent'),
                sortable: true
            )
            ->export()
            ->paginate(10);
    }
}
