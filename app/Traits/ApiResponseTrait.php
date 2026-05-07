<?php
namespace App\Traits;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
trait ApiResponseTrait {
    public function error(mixed $errors,string $message,int $code=400): JsonResponse{
        return response()->json([
            'status'=>false,
            'message'=>$message,
            'errors'=>$errors
        ],$code);
    }
    public function success(mixed $data,string $message,int $code=200): JsonResponse{
        return response()->json([
            'status'=>true,
            'message'=>$message,
            'data'=>$data
        ],$code);
    }
    public function paginatedResponse(LengthAwarePaginator $data, string $message,int $code=200): JsonResponse
    {
        return response()->json([
            'status'=>true,
            'message'=>$message,
            'data'=>[
                'items'=> $data->items(),
                'pagination'=>[
                    'total'=> $data->total(),
                    'current_page'=> $data->currentPage(),
                    'last_page'=> $data->lastPage(),
                    'per_page'=> $data->perPage(),
                    'prev_page_url'=>$data->previousPageUrl(),
                    'next_page_url'=>$data->nextPageUrl(),
                    'from'=> $data->firstItem(),
                    'to'=> $data->lastItem()
                ]
            ]
        ],$code);
    }


}
