<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponses
{
    /**
     * Success Response.
     *
     * @param  mixed  $data
     * @param  int  $statusCode
     * @return JsonResponse
     */
 



    public function successResponse(mixed $data, int $statusCode = Response::HTTP_OK, $message = null ): JsonResponse | RedirectResponse
    {
        return new JsonResponse([
            'statusCode'    => $statusCode,
            'message'   => $message,
            'data'=>$data,
            

        ], $statusCode);
    }








    /**
     * Error Response.
     *
     * @param  mixed  $data
     * @param  string  $message
     * @param  int  $statusCode
     * @return JsonResponse
     */
    public function errorResponse(mixed $data, string $message = '', int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse | RedirectResponse
    {
        // dd($data);


            if (!$message) {
                $message = $data->first();
            }

            $data1 = [
                'statusCode'    => $statusCode,
                'message' => $message,
                'errors' => $data,
            ];

            return new JsonResponse($data1, $statusCode);

    }

    /**
     * Response with status code 200.
     *
     * @param  mixed  $data
     * @return JsonResponse
     */
    public function okResponse(mixed $data,$message = null): JsonResponse | RedirectResponse
    {
         if(request()->wantsJson()){
            return $this->successResponse($data,200,$message);
        }
    }



    /**
     * Response with status code 201.
     *
     * @param  mixed  $data
     * @return JsonResponse
     */
    public function createdResponse(mixed $data): JsonResponse | RedirectResponse
    {
        return $this->successResponse($data, Response::HTTP_CREATED);
    }

    /**
     * Response with status code 204.
     *
     * @return JsonResponse
     */
    public function noContentResponse(): JsonResponse | RedirectResponse
    {
        return $this->successResponse([], Response::HTTP_NO_CONTENT);
    }

    /**
     * Response with status code 400.
     *
     * @param  mixed  $data
     * @param  string  $message
     * @return JsonResponse
     */
    public function badRequestResponse(mixed $data, string $message = ''): JsonResponse | RedirectResponse
    {
        if(request()->wantsJson()){

            return $this->errorResponse($data, $message, Response::HTTP_BAD_REQUEST);
        }else{
            return back()->withInput()->with('error',$message);
        }
    }

    /**
     * Response with status code 401.
     *
     * @param  mixed  $data
     * @param  string  $message
     * @return JsonResponse
     */
    public function unauthorizedResponse(mixed $data, string $message = ''): JsonResponse | RedirectResponse
    {
        if(request()->wantsJson()){
            return $this->errorResponse($data, $message, Response::HTTP_UNAUTHORIZED);
        }else{
            return back()->with('error',$message)->withInput();
        }
    }

    /**
     * Response with status code 403.
     *
     * @param  mixed  $data
     * @param  string  $message
     * @return JsonResponse
     */
    public function forbiddenResponse(mixed $data, string $message = ''): JsonResponse | RedirectResponse
    {
        return $this->errorResponse($data, $message, Response::HTTP_FORBIDDEN);
    }

    /**
     * Response with status code 404.
     *
     * @param  mixed  $data
     * @param  string  $message
     * @return JsonResponse
     */
    public function notFoundResponse(mixed $data, string $message = ''): JsonResponse | RedirectResponse
    {
        return $this->errorResponse($data, $message, Response::HTTP_NOT_FOUND);
    }

    /**
     * Response with status code 409.
     *
     * @param  mixed  $data
     * @param  string  $message
     * @return JsonResponse
     */
    public function conflictResponse(mixed $data, string $message = ''): JsonResponse | RedirectResponse
    {
        return $this->errorResponse($data, $message, Response::HTTP_CONFLICT);
    }

    /**
     * Response with status code 422.
     *
     * @param  mixed  $data
     * @param  string  $message
     * @return JsonResponse
     */
    public function unprocessableResponse(mixed $data = null, string $message = ''): JsonResponse | RedirectResponse
    {
        if (request()->wantsJson()) {
            $errors = $data && method_exists($data, 'errors') ? $data->errors() : null;
            return $this->errorResponse($errors, $message, Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            return $this->errorResponse($message, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
