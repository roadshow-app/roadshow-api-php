<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller {

    public function create(Request $request) {
        $user = auth('api')->user();
        if (!$user) return errorResponse('Unauthorized', 401);

        $validator = Validator::make($request->all(),[
            'name' => 'required|max:255',
        ]);
        if($validator->fails()) return errorResponse($validator->errors()->first(), 400);

        $company = Company::create([
            'name' => $request->name,
            'description' => $request->description,
            'owner_id' => $user->id,
        ]);

        return response()->json(new CompanyResource($company), 200);
    }

    public function show($id) {
        $company = Company::find($id);

        if (!$company) return errorResponse('Company not found', 404);

        return response()->json(new CompanyResource($company), 200);
    }

    public function update(Request $request) {
        $user = auth('api')->user();
        if (!$user) return errorResponse('Unauthorized', 401);

        $company = $user->company;

        if ($request->name) {
            $company->name = $request->name;
        }
        if ($request->photo) {
            $company->photo = $request->photo;
        }
        if ($request->description) {
            $company->description = $request->description;
        }

        $company->save();

        return response()->json(new CompanyResource($company), 200);
    }

    public function destroy(Company $company) {
        //
    }
}
