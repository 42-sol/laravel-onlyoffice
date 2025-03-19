<?php

namespace sol42\LaravelOnlyoffice\Http\Controllers;

use sol42\LaravelOnlyoffice\Services\OnlyOfficeService;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class OnlyOfficeController extends Controller
{
    public function open(Request $request): \Illuminate\Contracts\View\View
    {
        $onlyoffcieHost = config('onlyoffice.host');
        $onlyoffcieSecret = config('onlyoffice.jwt_secret');

        if(!$onlyoffcieHost) {
            return view('onlyoffice::editor', [
                'error' => __('onlyoffice::package.error.noHost'),
            ]);
        }

        if(!$onlyoffcieSecret) {
            return view('onlyoffice::editor', [
                'error' => __('onlyoffice::package.error.noSecret'),
            ]);
        }

        $docPath = $request->get('document');

        if (Storage::exists($docPath)) {
            try {
                $docMeta = OnlyOfficeService::getDocumentMeta($docPath);
                $token = JWT::encode($docMeta, $onlyoffcieSecret, "HS256");
                $embeddingScript = $onlyoffcieHost . "/web-apps/apps/api/documents/api.js";

                return view('onlyoffice::editor', [
                    'document' => $docMeta,
                    'token' => $token,
                    'callbackUrl' => route('onlyoffice.listen'),
                    'documentType' => OnlyOfficeService::getDocumentKind(Arr::get($docMeta, 'fileType')),
                    'embeddingScript' => $embeddingScript,
                ]);
            } catch (\Error|\Exception $e) {
                return view('onlyoffice::editor', [
                    'error' => "Error during document handling: " . $e->getMessage(),
                ]);
            }
        } else {
            return view('onlyoffice::editor', [
                'error' => __('onlyoffice::package.error.noFile'),
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
