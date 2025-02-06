<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Http\Requests\StoreFeeRequest;
use App\Http\Requests\UpdateFeeRequest;
use App\Http\Responses\Response;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;

class FeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fees = Fee::query()->with('student')->get();
        return Response::Success($fees, 'These are all fees in our application');
    }

    public function proccessPayment(Request $request, $studentId) {
        $fee = Fee::where('student_id', $studentId)->first();

        if (!$fee) {
            return response()->json(['message' => 'Fee record not found'], 404);
        }

        $validated = $request->validate([
            'payment_amount' => 'required|numeric|min:1'
        ]);

        $paymentAmount = $validated['payment_amount'];

        if ($paymentAmount > $fee->remaining_amount) {
            return response()->json(['message' => 'Payment exceeds remaining amount'], 400);
        }

        $deadLineError = $this->validatePaymentDate($fee);
        if ($deadLineError) {
            return $deadLineError;
        }

        $fee->payment_amount += $paymentAmount;
        $fee->remaining_amount -= $paymentAmount;


        if ($fee->remaining_amount == 0) {
            $fee->status = 'is_paid';
        }

        $fee->save();

        return Response::Success($fee, 'Payment processed successfully');
    }



    public function validatePaymentDate(Fee $fee){
        $today = now()->day()->toDateString();

        if ($fee->remaining_amount > 200 && $today > $fee->first_payment_date) {
            return response()->json(['message' => 'First payment deadline has passed'], 400);
        }

        if ($fee->remaining_amount > 0 && $today > $fee->final_payment_date) {
            return response()->json(['message' => 'Final payment deadline has passed'], 400);
        }

        return null;
    }

    public function store(StoreFeeRequest $request)
    {
        $validateData = $request->validated();
        $fee = Fee::create([
            'student_id' => $validateData['student_id'],
            'amount' => $validateData['amount'],
            'payment_date' => $validateData['payment_date'],
            'due_date' => $validateData['due_date'],
            'status' => $validateData['status'],
            'payment_method' => $validateData['payment_method'],
            'transaction_id' => $validateData['transaction_id']
        ]);

        return Response::Success($fee, 'fee is created successfully for student', 201);
    }

    public function getPendingFees($studentId)
    {
        $fees = Fee::where('student_id', $studentId)->where('status', 'is not paid')->get();
        return response()->json($fees);
    }

    public function getPaidFees($studentId)
    {
        $fees = Fee::where('student_id', $studentId)->where('status', 'is paid')->get();
        return response()->json($fees);
    }


    /**
     * Display the specified resource.
     */
    public function show($student_id)
    {
        $fees = Fee::where('student_id', $student_id)->get();
        return Response::Success($fees, 'These are all fees for this student in our application');
    }


    public function update(UpdateFeeRequest $request, Fee $fee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fee $fee)
    {
        //
    }
}
