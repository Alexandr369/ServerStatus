<?php

namespace Azuriom\Plugin\ServerStatus\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Models\Server;
use Azuriom\Plugin\ServerStatus\Models\StatusIncident;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    /**
     * Display the incidents list (automatic outages and admin-created maintenances).
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('serverstatus::admin.incidents', [
            'incidents' => StatusIncident::with('server')->latest('started_at')->paginate(20),
            'servers' => Server::orderBy('name')->get(),
        ]);
    }

    /**
     * Create a scheduled maintenance announcement.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'server_id' => ['nullable', 'integer', 'exists:servers,id'],
            'title' => ['required', 'string', 'max:255'],
            'started_at' => ['required', 'date'],
            'resolved_at' => ['nullable', 'date', 'after_or_equal:started_at'],
        ]);

        StatusIncident::create([
            'server_id' => $request->input('server_id'),
            'type' => StatusIncident::TYPE_MAINTENANCE,
            'title' => $request->input('title'),
            'started_at' => $request->input('started_at'),
            'resolved_at' => $request->input('resolved_at'),
            'created_by' => $request->user()->id,
        ]);

        return redirect()->route('serverstatus.admin.incidents')->with('success', trans('admin.settings.updated'));
    }

    /**
     * Update an incident, typically used by staff to resolve an ongoing one manually.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StatusIncident $incident)
    {
        $this->validate($request, [
            'title' => ['nullable', 'string', 'max:255'],
            'started_at' => ['required', 'date'],
            'resolved_at' => ['nullable', 'date', 'after_or_equal:started_at'],
        ]);

        $incident->update($request->only('title', 'started_at', 'resolved_at'));

        return redirect()->route('serverstatus.admin.incidents')->with('success', trans('admin.settings.updated'));
    }

    /**
     * Delete an incident.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(StatusIncident $incident)
    {
        $incident->delete();

        return redirect()->route('serverstatus.admin.incidents')->with('success', trans('admin.settings.updated'));
    }
}
