<?php

namespace App\API\V1\Products\Presentation\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Throwable;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use App\API\Common\Presentation\APIErrorResponse;
use App\API\Common\Presentation\APISuccessResponse;
use App\API\V1\Products\Contracts\ProductServiceInterface;
use App\API\V1\Products\Presentation\Requests\CreateFormRequest;
use App\API\V1\Products\Presentation\Requests\UpdateFormRequest;
use App\API\V1\Products\Presentation\Resources\ProductResource;
use App\API\V1\Products\Presentation\Resources\ProductResourceCollection;

class ProductController extends Controller
{
    public function __construct(private ProductServiceInterface $productService)
    {
        
    }

    /**
     * Display a listing of the resource.
    */
    public function index(Request $request)
    {
        $errMsg = "Could not get data";
        
        try {
            $message = "Success";
            $data = [];
            if($request->has('page'))  {
                $itemsPerPage = ($request->items_per_page)? (($request->items_per_page > 100)? 10 : $request->items_per_page): 10;
                $request->items_per_page = $itemsPerPage;
                $data = $this->productService->paginate($request->page, $itemsPerPage);
            }
            else {
                $data = $this->productService->all();
            }
            return new APISuccessResponse(new ProductResourceCollection(ProductResource::collection($data)), ["message" => $message]);
        } catch (ValidationException $ve) {
            return new APIErrorResponse($errMsg, $ve, Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $e) {
            return new APIErrorResponse($errMsg, $e);
        }
        return new APIErrorResponse($errMsg);
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
    public function store(CreateFormRequest $request)
    {
        $errMsg = "Could not save";
        
        try {
            $message = "Successfully saved";
            $data = $this->productService->create($request->validated());
            return new APISuccessResponse(new ProductResource($data), ["message" => $message]);
        } catch (ValidationException $ve) {
            return new APIErrorResponse($errMsg, $ve, Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $e) {
            return new APIErrorResponse($errMsg, $e);
        }
        return new APIErrorResponse($errMsg);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $errMsg = "Not found";
        
        try {
            $message = "";
            $data = $this->productService->find($id);
            return new APISuccessResponse(new ProductResource($data), ["message" => $message]);
        } catch (ValidationException $ve) {
            return new APIErrorResponse($errMsg, $ve, Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $e) {
            return new APIErrorResponse($errMsg, $e);
        }
        return new APIErrorResponse($errMsg);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFormRequest $request, string $id)
    {
        $errMsg = "Could not update";
        
        try {
            $message = "Successfully updated";
            $data = $this->productService->update($id, $request->validated());
            return new APISuccessResponse(new ProductResource($data), ["message" => $message]);
        } catch (ValidationException $ve) {
            return new APIErrorResponse($errMsg, $ve, Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $e) {
            return new APIErrorResponse($errMsg, $e);
        }
        return new APIErrorResponse($errMsg);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $errMsg = "Could not delete";
        
        try {
            $message = "Successfully deleted";
            $this->productService->delete($id);
            $data = [];
            return new APISuccessResponse($data, ["message" => $message]);
        } catch (ValidationException $ve) {
            return new APIErrorResponse($errMsg, $ve, Response::HTTP_BAD_REQUEST);
        } catch (\Throwable $e) {
            return new APIErrorResponse($errMsg, $e);
        }
        return new APIErrorResponse($errMsg);
    }
}
