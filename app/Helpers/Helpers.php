<?php

function validateFields($rules, $request)
{
    $validator = Validator::make($request, $rules);

    $fail = false;

    $response = [
        'success' => false,
        'message' => '',
        'error'  => ''
    ];

    if ($validator->fails()) {
        $fail = true;
        $response['error'] = $validator->errors();
        $response['message']  = 'Parece que falta que rellenes algunos campos obligatorios';
    }

    return (object) [
        'response' => $response,
        'fail'     => $fail
    ];
}

function createResponse($data = false, $message = '')
{
    $data = $data ? $data : collect([]);
    return [
        'success' => true,
        'message' => $message,
        'data'    => $data
    ];
}


/**
 * This function is used to create error response
 * @param  String $error  Message error
 * @return Array  ['success' => false, 'message' => 'Ocurrio un problema', 'errors'  => ['error' => [$error]]]
 */
function createError($error, $message = false, $exception = false)
{

    $data = [
        'success' => false,
        'message' => $message ? $message : 'En estos momentos estamos fuera de servicio, intenta más tarde',
        'error'  => $error
    ];

    if ($exception) {
        $data['line_error'] = $exception->getLine();
        $data['file_error'] = $exception->getFile();
        $data['exception'] = $exception->getMessage();
    }

    return $data;
}

function responseApi($response)
{
    return Response::json($response);
}

function uploadSingleFile($path, $file, $disk = 'public')
{
    Storage::disk($disk)->put(
        $path,
        File::get($file)
    );
}

?>
