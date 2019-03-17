<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterFormRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Repositories\UserRepository;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\Request;
use App;
use TimeHunter\LaravelGoogleReCaptchaV3\Facades\GoogleReCaptchaV3;

class AuthController extends Controller
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * AuthController constructor.
     * @param User $user
     * @param UserRepository $userRepository
     */
    public function __construct(User $user, UserRepository $userRepository)
    {
        $this->user = $user;
        $this->userRepository = $userRepository;
    }

    /**
     * @param RegisterFormRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function register(RegisterFormRequest $request)
    {
        $newUser = $this->userRepository->register($request);

        if ($newUser) {
            event(new Registered($newUser));
        }

        return response(
            [
            'status' => 'success',
            'data' => $this->user],
            200
        );
    }

    /**
     * @param LoginRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        $user = User::query()->where('name', $request->get('name'))
            ->where('passwd', md5($request->get('name') . $request->get('password')))
            ->first();

        if (!$user) {
            return response(
                [
                'status' => 'error',
                'error' => 'invalid.credentials',
                'msg' => 'Invalid Credentials.'],
                422
            );
        }
        return response(
            [
                'status' => 'success']
        )->header('Authorization', auth('api')->login($user));
    }

    /**
     * @param ResetPasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = User::query()->where('email', '=', $request['email'])->first();

        if ($user) {
            event(new ResetPassword($request['token']));
        } else
            return response()->json(['email' => 'incorrect'], 422);

    }

    /**
     * @param Request $request
     */
    public function checkUnique(Request $request)
    {
        $request->validate(
            [
                'email' => 'unique:users,email',
                'login' => 'unique:users,name']
        );
    }

    public function verifyCaptcha(Request $request)
    {
        return response()->json(GoogleReCaptchaV3::setScore(config('app.recaptcha.score'))
            ->verifyResponse($request['token'])
            ->isSuccess());
    }
}
