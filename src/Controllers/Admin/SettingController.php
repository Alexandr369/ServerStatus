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
            'checkRetentionDays' => (int) setting('serverstatus.check_retention_days', 90),
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
            'showSitewideBanner' => (bool) setting('serverstatus.show_sitewide_banner', true),
            'maintenanceNoticeHours' => (int) setting('serverstatus.maintenance_notice_hours', 24),
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
            'check_retention_days' => ['required', 'integer', 'min:1', 'max:365'],
            'intro_text' => ['nullable', 'string', 'max:2000'],
            'discord_webhook' => ['nullable', 'url', 'max:255'],
            'discord_message_down' => ['nullable', 'string', 'max:500'],
            'discord_message_up' => ['nullable', 'string', 'max:500'],
            'show_sitewide_banner' => ['sometimes', 'string'],
            'maintenance_notice_hours' => ['required', 'integer', 'min:0', 'max:720'],
        ]);

        Setting::updateSettings([
            'serverstatus.monitored_servers' => json_encode($request->input('monitored_servers', [])),
            'serverstatus.uptime_window_days' => $request->input('uptime_window_days'),
            'serverstatus.check_retention_days' => $request->input('check_retention_days'),
            'serverstatus.intro_text' => $request->input('intro_text'),
            'serverstatus.discord_webhook' => $request->input('discord_webhook'),
            'serverstatus.discord_message_down' => $request->input('discord_message_down'),
            'serverstatus.discord_message_up' => $request->input('discord_message_up'),
            'serverstatus.show_sitewide_banner' => $request->input('show_sitewide_banner') === 'on',
            'serverstatus.maintenance_notice_hours' => $request->input('maintenance_notice_hours'),
        ]);

        return redirect()->route('serverstatus.admin.settings')->with('success', trans('admin.settings.updated'));
    }
}
