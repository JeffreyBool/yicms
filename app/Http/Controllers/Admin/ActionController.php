<?php

namespace App\Http\Controllers\Admin;

use App\Models\ActionLog;
use App\Services\ActionLogsService;

class ActionController extends Controller
{
    protected $actionLogsService;

    /**
     * ActionController constructor.
     * @param ActionLogsService $actionLogsService
     */
    public function __construct(ActionLogsService $actionLogsService)
    {
        $this->actionLogsService = $actionLogsService;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $actions = $this->actionLogsService->getActionLogs();

        return $this->view(null, compact('actions'));
    }


    /**
     * @param ActionLog $actionLog
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(ActionLog $actionLog)
    {
        $actionLog->delete();

        flash('删除日志成功')->success()->important();

        return redirect()->route('actions.index');
    }
}
