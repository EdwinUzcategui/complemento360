<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Travel;


class TravelController extends Controller
{
    public function storeTravel(Request $request)
    {
        try {

            $xml = simplexml_load_string($request->getContent(), "SimpleXMLElement", LIBXML_NOCDATA);
            $json = json_encode($xml);
            $data = json_decode($json,TRUE);

            $rules = [
                'travel_date' => 'required',
                'country' => 'required',
                'city' => 'required',
                'user_email' => 'required',
            ];

            $validate = validateFields($rules, $data);

            if ($validate->fail) return response()->json($validate->response);

            $travel = new Travel;
            $travel->travel_date = $data['travel_date'];
            $travel->country = $data['country'];
            $travel->city = $data['city'];
            $travel->user_email = $data['user_email'];
            $travel->save();

            $response = createResponse($travel, 'Viaje registrado satisfactoriamente');

        } catch (\Exception $error) {
			$response = createError($error->getMessage());
		}

		return responseApi($response);
    }

    public function getAllTravels(Request $request)
    {
        try {

            $travels = Travel::orderBy('created_at', 'DESC')->get();

            $response = createResponse($travels, 'Viajes consultados satisfactoriamente');

        } catch (\Exception $error) {
			$response = createError($error->getMessage());
		}

		return responseApi($response);
    }

    public function destroy($id)
    {

        $travel = Travel::findOrFail($id);

        $travel->delete();

        return back()->with(['success' => 'Travel removed successfully.', 'tab' => 'travel']);
    }


}
