<?php

namespace Azuriom\Plugin\ServerStatus\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Models\Server;
use Azuriom\Models\Setting;
use Azuriom\Plugin\ServerStatus\Support\MonitoredServers;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display the Server Status settings page.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('serverstatus::admin.settings', [
            'servers' => Server::orderBy('name')->get(),
            'monitoredServerIds' => MonitoredServers::ids(),
            'uptimeWindowDays' => (int) setting('serverstatus.uptime_window_days', 30),
            'introText' => setting('serverstatus.intro_text', ''),
            'discordWebhook' => setting('serverstatus.discord_webhook'),
            'discordMessageDown' => setting(
                'serverstatus.discord_message_down',
                trans('serverstatus::admin.settings.discord_message_down_default')
            ),
            'discordMessageUp' => setting(
                'serverstatus.discord_message_up',
                trans('serverstatus::admin.settings.discord_message_up_default')
            ),
        ]);
    }

    /**
     * Save the Server Status settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        $this->validate($request, [
            'monitored_servers' => ['nullable', 'array'],
            'monitored_servers.*' => ['integer', 'exists:servers,id'],
            'uptime_window_days' => ['required', 'integer', 'min:1', 'max:365'],
            'intro_text' => ['nullable', 'string', 'max:2000'],
            'discord_webhook' => ['nullable', 'url', 'max:255'],
            'discord_message_down' => ['nullable', 'string', 'max:500'],
            'discord_message_up' => ['nullable', 'string', 'max:500'],
        ]);

        Setting::updateSettings([
            'serverstatus.monitored_servers' => json_encode($request->input('monitored_servers', [])),
            'serverstatus.uptime_window_days' => $request->input('uptime_window_days'),
            'serverstatus.intro_text' => $request->input('intro_text'),
            'serverstatus.discord_webhook' => $request->input('discord_webhook'),
            'serverstatus.discord_message_down' => $request->input('discord_message_down'),
            'serverstatus.discord_message_up' => $request->input('discord_message_up'),
        ]);

        return redirect()->route('serverstatus.admin.settings')->with('success', trans('admin.settings.updated'));
    }
}
