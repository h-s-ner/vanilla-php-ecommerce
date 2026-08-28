<?php
class AdminOrderController {

    public function __construct(
        private readonly AdminOrderService $adminOrderService
    ){}

    public function index()
    {
        $response = $this->adminOrderService->index();
        return $response;
    }

    public function updateStatus($order_id , OrderStatus $status)
    {
        return $this->adminOrderService->updateStatus($order_id, $status);
    }

    /*public function show($id)
    {
        return $this->adminOrderService->show($id);
    }
    public function store($request, $files): bool|array{
        return $this->adminOrderService->store($request, $files);
    }

    public function update($id, $request, $files): bool|array
    {
        return $this->adminOrderService->update($id,$request,$files);
    }

    public function destroy($id)
    {
        $this->adminOrderService->destroy($id);

    }*/
}
?>