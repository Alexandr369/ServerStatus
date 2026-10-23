<?php

namespace Azuriom\Plugin\ServerStatus\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Models\Server;
use Azuriom\Plugin\ServerStatus\Models\StatusIncident;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class IncidentController extends Controller
{
    /**
     * Display the incidents list (automatic outages and admin-created incidents).
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
     * Create a staff-reported incident (maintenance, degraded performance, outage, info).
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'server_id' => ['nullable', 'integer', 'exists:servers,id'],
            'category' => ['required', 'string', Rule::in(StatusIncident::CATEGORIES)],
            'title' => ['required', 'string', 'max:255'],
            'started_at' => ['required', 'date'],
            'resolved_at' => ['nullable', 'date', 'after_or_equal:started_at'],
        ]);

        StatusIncident::create([
            'server_id' => $request->input('server_id'),
            'type' => StatusIncident::TYPE_MAINTENANCE,
            'category' => $request->input('category'),
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
            'category' => ['required', 'string', Rule::in(StatusIncident::CATEGORIES)],
            'title' => ['nullable', 'string', 'max:255'],
            'started_at' => ['required', 'date'],
            'resolved_at' => ['nullable', 'date', 'after_or_equal:started_at'],
        ]);

        $incident->update($request->only('category', 'title', 'started_at', 'resolved_at'));

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
