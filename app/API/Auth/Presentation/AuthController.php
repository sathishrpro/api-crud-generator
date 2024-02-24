<?php

namespace App\API\Auth\Presentation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Api\Auth\Presentation\Requests\LoginFormRequest;
use App\API\Auth\Presentation\Resources\UserResource;
use App\API\Auth\Services\AuthService;
use App\API\Common\Presentation\APIErrorResponse;
use App\API\Common\Presentation\APISuccessResponse;
use Exception;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginFormRequest $request)
    {
        $errMsg = "Could not login";

        try {
            $message = "Login success";
            $result = $this->authService->login($request->email, $request->password);
            return new APISuccessResponse($result, ["message" => $message]);
        } catch (ValidationException $ve) {
            return new APIErrorResponse($ve->getMessage(), $ve, Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $e) {
            return new APIErrorResponse($e->getMessage(), $e);
        }
        return response()->json(['error' => $errMsg], Response::HTTP_INTERNAL_SERVER_ERROR);
    }


    public function logout(Request $request)
    {
        $errMsg = "Could not logout.";

        try {
            $logoutResult = $this->authService->logout();
            $result = [];
            $message = "Successfully logged out";
            return new APISuccessResponse($result, ["message" => $message]);
        } catch (ValidationException $ve) {
            return new APIErrorResponse($ve->getMessage(), $ve, Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return new APIErrorResponse($e->getMessage(), $e);
        }
        return response()->json(['error' => $errMsg], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function getUser(Request $request)
    {
        $errMsg = "Could not get user.";

        try {
            $result = [];
            $result['user'] = new UserResource(auth()->user());
            return new APISuccessResponse($result);
        } catch (ValidationException $ve) {
            return new APIErrorResponse($ve->getMessage(), $ve, Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return new APIErrorResponse($e->getMessage(), $e);
        }
        return response()->json(['error' => $errMsg], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
