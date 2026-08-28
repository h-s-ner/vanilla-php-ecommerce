<?php
class AdminProductController {

    public function __construct(
        private readonly AdminProductService $adminProductService
    ){}

    public function index()
    {
        $response = $this->adminProductService->index();
        return $response;
    }

    public function show($id)
    {
        return $this->adminProductService->show($id);
    }
    public function store($request, $files): bool|array{
        return $this->adminProductService->store($request, $files);
    }

    public function update($id, $request, $files): bool|array
    {
        return $this->adminProductService->update($id,$request,$files);
    }

    public function destroy($id)
    {
        $this->adminProductService->destroy($id);
        
    }
}
?>