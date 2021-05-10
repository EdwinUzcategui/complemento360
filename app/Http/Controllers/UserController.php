<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Travel;

use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'DESC')->get();

        $travels = Travel::with('user')->orderBy('created_at', 'DESC')->get();

        return view('index', compact('users', 'travels'));
    }

    public function show($id)
    {

        $user = User::whereId($id)->with('travels')->first();

        return view('details', compact('user'));
    }

    public function getUsers(Request $request)
    {
        try {

            $query = User::query();

            $search = $request->search;
            $email = $request->email;

            if (!$email) {
                $query->when($search, function ($q, $search) {
                    return $q->where('name', 'like', "%$search%")
                        ->orWhere('lastname', 'like', "%$search%");
                });
            }

            if (!$search) {
                $query->when($email, function ($q, $email) {
                    return $q->where('email', 'like', "%$email%");
                });
            }

            $perPage = 10;

            if ($request->perPage) $perPage = $request->perPage;

            $users = $query->paginate($perPage);

            $response = createResponse($users, 'Usuarios consultados satisfactoriamente');

        } catch (\Exception $error) {
			$response = createError($error->getMessage());
		}

		return responseApi($response);
    }

    public function storeUser(Request $request)
    {
        try {

            $rules = [
                'name' => 'required|max:30',
                'lastname' => 'required|max:30',
                'phone' => 'required|max:30',
                'email' => 'required|email|unique:users|max:30',
                'address' => 'required|max:255',
            ];

            $photo = $request->file('photo');

            if ($photo) $rules['photo'] = 'file|mimes:jpg,png,jpeg';

            $validate = validateFields($rules, $request->all());

            if ($validate->fail) return response()->json($validate->response);

            $user = new User;
            $user->name = $request->name;
            $user->lastname = $request->lastname;
            $user->phone = $request->phone;
            $user->email = $request->email;
            $user->address = $request->address;
            $user->save();

            if ($photo) {
                $file_name = \Str::slug($user->id.$request->name, '-').'.'.$photo->getClientOriginalExtension();
                $path = 'photos/'.$file_name;

                uploadSingleFile($path, $photo);

                $user->photo = $file_name;
                $user->save();
            }

            $response = createResponse($user, 'Usuario registrado satisfactoriamente');

        } catch (\Exception $error) {
			$response = createError($error->getMessage());
		}

		return responseApi($response);
    }

    public function updateUser(Request $request)
    {
        try {

            $rules = [
                'id' => 'required',
                'name' => 'required|max:30',
                'lastname' => 'required|max:30',
                'phone' => 'required|max:30',
                'email' => ['required', 'email', Rule::unique('users')->ignore($request->id)],
                'address' => 'required|max:255',
            ];

            $photo = $request->file('photo');

            if ($photo) $rules['photo'] = 'file|mimes:jpg,png,jpeg';

            $validate = validateFields($rules, $request->all());

            if ($validate->fail) return response()->json($validate->response);

            $user = User::findOrFail($request->id);

            $file_name = $user->photo;

            if ($photo) {
                \Storage::disk('public')->delete('photos/'.$user->photo);

                $file_name = \Str::slug($user->id.$request->name, '-').'.'.$photo->getClientOriginalExtension();
                $path = 'photos/'.$file_name;

                uploadSingleFile($path, $photo);
            }

            $user->update([
                'name' => $request->name,
                'lastname' => $request->lastname,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'photo' => $file_name
            ]);

            $response = createResponse($user, 'Usuario editado satisfactoriamente');

        } catch (\Exception $error) {
			$response = createError($error->getMessage());
		}

		return responseApi($response);
    }

    public function deleteUser(Request $request)
    {
        try {

            $rules = [
                'id' => 'required'
            ];

            $validate = validateFields($rules, $request->all());

            if ($validate->fail) return response()->json($validate->response);

            $user = User::with('travels')->findOrFail($request->id);

            if (count($user->travels)) $user->travels()->delete();

            \Storage::disk('public')->delete('photos/'.$user->photo);

            $user->delete();

            $response = createResponse([], 'Usuario eliminado satisfactoriamente');

        } catch (\Exception $error) {
			$response = createError($error->getMessage());
		}

		return responseApi($response);
    }

    public function destroy($id)
    {

        $user = User::with('travels')->findOrFail($id);

        if (count($user->travels)) $user->travels()->delete();

        \Storage::disk('public')->delete('photos/'.$user->photo);

        $user->delete();

        return back()->with(['success' => 'User removed successfully.', 'tab' => 'user']);
    }
}
