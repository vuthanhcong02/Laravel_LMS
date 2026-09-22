<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SynthesizeTtsRequest;
use App\Services\TtsService;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TtsController extends Controller
{
    /**
     * TTS Service instance.
     */
    protected TtsService $ttsService;

    public function __construct(TtsService $ttsService)
    {
        $this->ttsService = $ttsService;
    }

    /**
     * Synthesize and stream speech audio from Chinese text.
     *
     * @param SynthesizeTtsRequest $request
     * @return BinaryFileResponse|JsonResponse
     */
    public function synthesize(SynthesizeTtsRequest $request): BinaryFileResponse|JsonResponse
    {
        $text = $request->getText();
        $voice = $request->getVoice();
        $rate = $request->getRate();

        try {
            $filePath = $this->ttsService->synthesize($text, $voice, $rate);

            return response()->file($filePath, [
                'Content-Type'  => 'audio/mpeg',
                'Cache-Control' => 'public, max-age=31536000, immutable',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Speech synthesis failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}
