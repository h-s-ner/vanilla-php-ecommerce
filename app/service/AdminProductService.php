<?php

class AdminProductService{
    private $errors = [];
    
    public function __construct(
        private readonly ?FormService $formService = null,
        private readonly ?ProductModel $productModel = null)
    {
       
    }
    
    public function index()
    {
        return $this->productModel->adminIndex();
    }
    
    public function show($id)
    {
        return $this->productModel->adminShow($id);
    }
    
    public function destroy($id)
    {
        return  $this->productModel->destroy($id);
    }
 
    public function store($request, $files): bool|array
    {
        Middleware::validateCsrfToken();
        Middleware::reGenerateCsrfToken();

        // Form Werte
        $name = $request['name'] ?? '';
        $price = $request['price'] ?? '';
        $description = $request['description'] ?? '';
        $stock = $request['stock'] ?? '';
        $active = isset($request['active']) ? 1 : 0;
        $imageFile = $files['product_image'] ?? null;
        $imageName = $imageFile && !empty($imageFile['name']) ? time().'_'.$imageFile['name'] : null;

        // Validierungen
        $this->errors = $this->formService->validateRequired([
            'name' => $name,
            'description' => $description
        ]);
        $this->errors = array_merge(
            $this->errors,
            $this->formService->validatePrice($price)
            );
        $this->errors = array_merge(
            $this->errors,
            $this->formService->validateStock($stock)
            );
        if ($imageFile) {
            $this->errors = array_merge($this->errors,
            $this->formService->validateImage($imageFile)
            );
        }
        if (!empty($this->errors)) {
            return $this->errors;
        }
        // Upload Image
        if ($imageName) {
            $target_path = "images/".$imageName;
            move_uploaded_file($imageFile["tmp_name"], $target_path);
        }
        // Speichern
        $this->productModel->store(
            $name,
            $price,
            $description,
            $stock, $active,
            $imageName
        );
        return true;
    }

    public function update($id, $request, $files): bool|array
    {
        Middleware::validateCsrfToken();
        Middleware::reGenerateCsrfToken();

        $name = $request['name'] ?? '';
        $price = $request['price'] ?? '';
        $description = $request['description'] ?? '';
        $stock = $request['stock'] ?? '';
        $active = isset($request['active']) ? 1 : 0;
        $is_delete_image = isset($request['delete_image']);
        $imageFile = $files['product_image'] ?? null;

    

        // --- Validierungen über FormService ---
        $this->errors=$this->formService->validateRequired([
            'name' => $name,
            'description' => $description
        ]);
        $this->errors = array_merge($this->errors, $this->formService->validatePrice($price));
        $this->errors = array_merge($this->errors, $this->formService->validateStock($stock));
        if ($imageFile && !empty($imageFile['name'])) {
            $this->errors = array_merge($this->errors, $this->formService->validateImage($imageFile));
        }
        if (!empty($this->errors)) {
            return $this->errors;
        }
        // --- Update Produktdaten ---
        $this->productModel->update($id, $name, $price, $description, $stock, $active);
        // delete old image
        if ($is_delete_image) {
            $this->productModel->updateImage($id, null);
        }
        // --- Neues Bild hochladen ---
        if ($imageFile && !empty($imageFile['name'])) {
            $ext = strtolower(pathinfo($imageFile['name'], PATHINFO_EXTENSION));
            $imageName = time() . '_' . bin2hex(random_bytes(5)) . '.' . $ext;
            $target_path = "images/" . $imageName;

            if (!move_uploaded_file($imageFile["tmp_name"], $target_path)) {
                return ['Image upload error'];
            }

            $this->productModel->updateImage($id, $imageName);
        }
        return true;
    }
}
?>