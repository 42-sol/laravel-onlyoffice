<?php

namespace 42sol\LaravelOnlyoffice\Http\Controllers;

use App\Services\OnlyOfficeService;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class OnlyOfficeController extends Controller
{
    public function open(Request $request): \Illuminate\Contracts\View\View
    {
        $docPath = $request->get('document');

        if (Storage::exists($docPath)) {
            $docMeta = OnlyOfficeService::getDocumentMeta($docPath);
            $token = JWT::encode($docMeta, config('onlyoffice.jwt_secret'), "HS256");

            return view('onlyofficeEditor', [
                'document' => $docMeta,
                'token' => $token,
                'callbackUrl' => route('onlyoffice.listen'),
                'documentType' => OnlyOfficeService::getDocumentKind(Arr::get($docMeta, 'fileType')),
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

                case 2:
                    OnlyOfficeService::save($request->get('url'), $request->get('key'));
                    break;

                case 3: // error during saving
                    Log::error("Onlyoffice error: " . "");
                    break;
                case 4: // closed with no change
                    break;

                case 6: //document is being edited, but the current state is saved
                    $staus = OnlyOfficeService::save($request->get('url'), $request->get('key'));
                    return new \Illuminate\Http\JsonResponse([
                        'error' => 0
                    ]);
                case 7: // error while force saving
                    Log::error("Onlyoffice error: " . "");
                    break;
            }
        }

        return new \Illuminate\Http\JsonResponse([
            'error' => 1
        ]);
    }
}
