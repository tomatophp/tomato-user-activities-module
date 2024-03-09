<?php

namespace Modules\TomatoUserActivities\App\Services;

use Illuminate\Support\Collection;
use Modules\TomatoUserActivities\App\Interpolations\RequestInterpolation;
use Modules\TomatoUserActivities\App\Interpolations\ResponseInterpolation;
use Modules\TomatoUserActivities\App\Loggers\RequestLogger;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Modules\TomatoUserActivities\App\Models\Activity;

/**
 * Class RequestLoggerService
 */
class RequestLoggerService
{
    protected Collection $collectLog;
    /**
     *
     */
    protected const LOG_CONTEXT = 'RESPONSE';
    /**
     * @var array
     */
    protected array $formats = [
        'full' => '{request-hash} | HTTP/{http-version} {status} | {remote-addr} | {user} | {method} {url} {query} | {response-time} s | {user-agent} | {referer}',
        'combined' => '{remote-addr} - {remote-user} [{date}] "{method} {url} HTTP/{http-version}" {status} {content-length} "{referer}" "{user-agent}"',
        'common' => '{remote-addr} - {remote-user} [{date}] "{method} {url} HTTP/{http-version}" {status} {content-length}',
        'dev' => '{method} {url} {status} {response-time} s - {content-length}',
        'short' => '{remote-addr} {remote-user} {method} {url} HTTP/{http-version} {status} {content-length} - {response-time} s',
        'tiny' => '{method} {url} {status} {content-length} - {response-time} s'
    ];
    /**
     * @var RequestInterpolation
     */
    protected RequestInterpolation $requestInterpolation;
    /**
     * @var ResponseInterpolation
     */
    protected ResponseInterpolation $responseInterpolation;
    /**
     * @var RequestLogger
     */
    protected RequestLogger $logger;

    /**
     * RequestLoggerService constructor.
     *
     * @param RequestLogger $logger
     * @param RequestInterpolation $requestInterpolation
     * @param ResponseInterpolation $responseInterpolation
     */
    public function __construct(
        RequestLogger $logger,
        RequestInterpolation $requestInterpolation,
        ResponseInterpolation $responseInterpolation
    ) {
        $this->logger = $logger;
        $this->requestInterpolation = $requestInterpolation;
        $this->responseInterpolation = $responseInterpolation;
        $this->collectLog = collect([]);
    }

    /**
     * @param Request $request
     * @param Response $response
     */
    public function log(Request $request, Response $response): void
    {
        $this->requestInterpolation->setRequest($request);

        $this->responseInterpolation->setResponse($response);

        if (config('tomato-user-activities.request.enabled')) {
            $format = config('tomato-user-activities.request.format', 'full');
            $format = Arr::get($this->formats, $format, $format);

            $message = $this->responseInterpolation->interpolate($format);

            $this->collectLog->push($this->responseInterpolation->getLogger()->toArray());

            $message = $this->requestInterpolation->interpolate($message);

            $this->collectLog->push($this->requestInterpolation->getLogger()->toArray());

            $logArray = array_merge($this->collectLog->toArray()[0], $this->collectLog->toArray()[1]);

            if(in_array('auth:accounts', $request->route()->middleware()) && $request->user('accounts')){
                Activity::create([
                    'model_id' => $request->user()->id,
                    'model_type' => config('tomato-crm.model'),
                    'request_hash' => $logArray['request-hash'],
                    'response_time' => $logArray['response-time'],
                    'status' => $logArray['status'],
                    'method' => $logArray['method'],
                    'url' => $logArray['url'],
                    'referer' => $logArray['referer'],
                    'query' => $logArray['query'],
                    'remote_address' =>$logArray['remote-addr'],
                    'user_agent' => $request->userAgent(),
                    'level' => config('tomato-user-activities.request.level', 'info'),
                    'user' => $logArray['user'],
                ]);
            }

            $this->logger->log(config('tomato-user-activities.request.level', 'info'), $message, [
                static::LOG_CONTEXT
            ]);
        }
    }
}
