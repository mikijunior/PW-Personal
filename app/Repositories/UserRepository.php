<?php

namespace App\Repositories;

use App\Http\Requests\RegisterFormRequest;
use App\User;
use Illuminate\Support\Facades\DB;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;

/**
 * Class UserRepository.
 */
class UserRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return User::class;
    }


    /**
     * @param RegisterFormRequest $request
     * @return mixed
     */
    public function register(RegisterFormRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $lastId = $this->model->get(['id'])->last();

            if (!$lastId) {
                $newId = 1024;
            } else
                $newId = $lastId->id + 16;
            while ($this->model->find($newId)) {
                $newId += 16;
            }
            return $this->model->create([
                'id' => $newId,
                'name' => $request['name'],
                'email' => $request['email'],
                'passwd' => 'not_activated',
                'passwd2' => md5($request['name'] . $request['password']),
                'Promt' => $request['Promt'],
                'answer' => $request['answer'],
                'idnumber' => request()->ip(),
                'creatime' => new \DateTime('now'),
            ]);
        });

    }
}
