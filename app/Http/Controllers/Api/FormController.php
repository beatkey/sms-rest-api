<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreFormRequest;
use App\Http\Requests\UpdateFormRequest;
use App\Models\Form;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FormController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = Form::all();

            /*$statusList = [
                "pending",
                "approved",
                "rejected",
                "auto-rejected"
            ];*/

            if (request()->status) {
                /*if (!in_array(request()->status, $statusList)){
                    return $this->setError("Status value is not correct");
                    return $this->setSuccess([]);
                }*/

                $data = Form::where("status", request()->status)->get();
            }

            return $this->setSuccess($data);
        } catch (\Exception $e) {
            return $this->setError($e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFormRequest $request)
    {
        try {
            DB::beginTransaction();

            $lastRecord = Form::where(function ($query) use ($request) {
                $query->where('email', $request->email)
                    ->orWhere('phone_number', $request->phone_number);
            })
                ->where('created_at', '>', Carbon::now()->subHours(24))
                ->exists();

            if ($lastRecord) {
                return $this->setError("You can send a maximum of 1 request in the last 24 hours.");
            }

            $query = Form::create([
                "name" => $request->name,
                "surname" => $request->surname,
                "phone_number" => $request->phone_number,
                "email" => $request->email,
                "birthdate" => $request->birthdate,
                "status" => "pending"
            ]);

            $sendSms = sendSms($request->phone_number, "Talebiniz alınmıştır.");
            if (!$sendSms["status"]) {
                return $this->setError("Sms send error: " . $sendSms["message"]);
            }

            DB::commit();
            return $this->setSuccess($query);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->setError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Form $form)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Form $form)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFormRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $data = Form::where("id", $id)
                ->first();

            if (!$data) {
                return $this->setError("Form not found", null, 404);
            }

            $data->update([
                "status" => $request->status
            ]);

            if ($request->status == "approved") {
                // buraya okulun telefon numarası ve teklif detayları gelmeli
                /*$sendSms = sendSms($request->phone_number, "teklif detayları");
                if (!$sendSms["status"]) {
                    return $this->setError("Sms send error: " . $sendSms["message"]);
                }*/
            }

            DB::commit();
            return $this->setSuccess([
                "id" => $data->id,
                "status" => $data->status
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->setError($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Form $form)
    {
        //
    }
}
