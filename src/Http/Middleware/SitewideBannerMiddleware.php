<?php

namespace Azuriom\Plugin\ServerStatus\Http\Middleware;

use Azuriom\Plugin\ServerStatus\Models\StatusIncident;
use Azuriom\Plugin\ServerStatus\Support\MonitoredServers;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * Shows a small banner about the most relevant incident (an ongoing outage,
 * or an upcoming/ongoing maintenance) on every front-end page, not just the
 * status page itself. Azuriom's front layout gives plugins no hook to inject
 * markup site-wide, so this works the same way as Appeals' own banner
 * middleware: splice real HTML into the already-rendered response, right
 * after the opening <body> tag.
 */
class SitewideBannerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($request->is('admin*') || $request->routeIs('serverstatus.*') || ! setting('serverstatus.show_sitewide_banner', true)) {
            return $response;
        }

        if (! str_contains((string) $response->headers->get('Content-Type'), 'text/html')) {
            return $response;
        }

        $content = $response->getContent();

        if (! is_string($content) || ! str_contains($content, '<body')) {
            return $response;
        }

        $banner = $this->banner();

        if ($banner === null) {
            return $response;
        }

        $response->setContent(preg_replace_callback(
            '/<body([^>]*)>/',
            fn ($matches) => '<body'.$matches[1].'>'.$banner,
            $content,
            1
        ));

        return $response;
    }

    /**
     * Renders the banner for the most relevant incident, cached briefly since
     * this runs on every front-end page view.
     */
    private function banner(): ?string
    {
        $html = Cache::remember('serverstatus.sitewide_banner', now()->addMinutes(2), function () {
            $incident = $this->relevantIncident();

            if ($incident === null) {
                return '';
            }

            return view('serverstatus::banner', ['incident' => $incident])->render();
        });

        return $html !== '' ? $html : null;
    }

    /**
     * The single incident worth bannering site-wide: an ongoing outage takes
     * priority, otherwise the soonest upcoming or already-started maintenance
     * (or other staff-reported incident) within the configured notice window.
     */
    private function relevantIncident(): ?StatusIncident
    {
        $serverIds = MonitoredServers::ids();
        $noticeHours = (int) setting('serverstatus.maintenance_notice_hours', 24);

        return StatusIncident::with('server')
            ->where(function ($query) use ($serverIds) {
                $query->whereIn('server_id', $serverIds)->orWhereNull('server_id');
            })
            ->where(function ($query) use ($noticeHours) {
                $query->where(function ($ongoing) {
                    $ongoing->whereNull('resolved_at')->where('started_at', '<=', now());
                })->orWhere(function ($upcoming) use ($noticeHours) {
                    $upcoming->whereNull('resolved_at')
                        ->where('started_at', '>', now())
                        ->where('started_at', '<=', now()->addHours($noticeHours));
                });
            })
            ->orderByRaw("type = 'outage' desc")
            ->orderBy('started_at')
            ->first();
    }
}
