<?php

namespace sol42\LaravelOnlyoffice\Http\Controllers;

use sol42\LaravelOnlyoffice\Services\OnlyOfficeService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class OnlyOfficeController extends Controller
{
    public function open(Request $request): \Illuminate\Contracts\View\View
    {
        $handler = config('onlyoffice.handler', OnlyOfficeService::class);

        try {
            $docInfo = $handler::prepareDocumentInfo($request->get('document'));
        } catch (\Error|\Exception $e) {
            return view('onlyofficeEditor', [
                'error' => "Error during document handling: " . $e->getMessage(),
            ]);
        }

        if (!Arr::has($docInfo, 'error')) {
            return view('onlyofficeEditor', $docInfo);
        } else {
            return view('onlyofficeEditor', [
                'error' => Arr::get($docInfo, 'error'),
            ]);
        }
    }

    public function listen(Request $request) {
        $status = $request->get('status');

        if ($status) {
            switch ($status) {
                case 1:
                    return new \Illuminate\Http\JsonResponse([
                        'error' => 0
                    ]);

                case 2: // closed and sent to be saved
                    OnlyOfficeService::save($request->get('url'), $request->get('key'));
                    break;

                case 3: // error during saving
                case 7: // error during FORCE saving
                    Log::error("Onlyoffice document saving error error. Status: $status");
                    break;

                case 4: // closed with no change
                    // does not really matter what is returned here, since basically nothing happened
                    return new \Illuminate\Http\JsonResponse([
                        'error' => 0
                    ]);

                case 6: // force save
                    $status = OnlyOfficeService::save($request->get('url'), $request->get('key'));

                    if ($status) {
                        return new \Illuminate\Http\JsonResponse([
                            'error' => 0
                        ]);
                    }

                    break;
            }
        }

        return new \Illuminate\Http\JsonResponse([
            'error' => 1
        ]);
    }
}
